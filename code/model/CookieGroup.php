<?php

namespace Broarm\CookieConsent;

use Config;
use DataObject;
use DB;
use Director;
use Exception;
use FieldList;
use GridField;
use HasManyList;
use HtmlEditorField;
use Tab;
use TabSet;
use TextField;

/**
 * CookieGroup that holds type of cookies
 * You can add these groups trough the yml config
 *
 * @package Broarm
 * @subpackage CookieConsent
 *
 * @property string ConfigName
 * @property string Title
 * @property string Content
 *
 * @method HasManyList Cookies()
 */
class CookieGroup extends DataObject
{
    const REQUIRED_DEFAULT = 'Necessary';

    private static $db = array(
        'ConfigName' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'Content' => 'HtmlText',
    );

    private static $indexes = array(
        'ConfigName' => true
    );

    private static $has_many = array(
        'Cookies' => 'Broarm\\CookieConsent\\CookieDescription.Group',
    );

    private static $translate = array(
        'Title',
        'Content'
    );

    /**
     * @return FieldList|mixed
     */
    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root', $mainTab = Tab::create('Main')));
        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title', $this->fieldLabel('Title')),
            GridField::create('Cookies', $this->fieldLabel('Cookies'), $this->Cookies(), GridFieldConfigCookies::create())
        ));

        $fields->addFieldsToTab('Root.Description', array(
            HtmlEditorField::create('Content', $this->fieldLabel('Content'))
        ));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

    /**
     * Check if this group is the required default
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->ConfigName === self::REQUIRED_DEFAULT;
    }

    /**
     * Create a Cookie Consent checkbox based on the current cookie group
     *
     * @return CookieConsentCheckBoxField
     */
    public function createField()
    {
        return new CookieConsentCheckBoxField($this);
    }

    /**
     * @throws Exception
     * @throws \ValidationException
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        if ($cookiesConfig = Config::inst()->get(CookieConsent::class, 'cookies')) {
            if (!isset($cookiesConfig[self::REQUIRED_DEFAULT])) {
                throw new Exception("The required default cookie set is missing, make sure to set the 'Necessary' group");
            }

            foreach ($cookiesConfig as $groupName => $cookies) {
                if (!$group = self::get()->find('ConfigName', $groupName)) {
                    $group = self::create(array(
                        'ConfigName' => $groupName,
                        'Title' => _t("CookieConsent.$groupName", $groupName),
                        'Content' => _t("CookieConsent.{$groupName}_Content")
                    ));

                    $group->write();
                    DB::alteration_message(sprintf('Cookie group "%s" created', $groupName), 'created');
                }

                foreach ($cookies as $cookieName) {
                    if (!$cookie = CookieDescription::get()->find('ConfigName', $cookieName)) {
                        $cookie = CookieDescription::create(array(
                            'ConfigName' => $cookieName,
                            'Title' => $cookieName,
                            'Provider' => _t("CookieConsent.{$cookieName}_Provider", $_SERVER['HTTP_HOST']),
                            'Purpose' => _t("CookieConsent.{$cookieName}_Purpose", ''),
                            'Expiry' => _t("CookieConsent.{$cookieName}_Expiry", '')
                        ));

                        $group->Cookies()->add($cookie);
                        $cookie->flushCache();
                        DB::alteration_message(sprintf('Cookie "%s" created and added to group "%s"', $cookieName, $groupName), 'created');
                    }
                }

                $group->flushCache();
            }
        }
    }

    public function canCreate($member = null)
    {
        return false;
    }

    /**
     * Make deletable if not defined in config
     *
     * @param null $member
     * @return bool
     */
    public function canDelete($member = null)
    {
        $cookieConfig = Config::inst()->get(CookieConsent::class, 'cookies');
        return !isset($cookieConfig[$this->ConfigName]);
    }
}
