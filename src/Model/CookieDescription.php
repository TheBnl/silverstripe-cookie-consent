<?php

namespace Broarm\CookieConsent\Model;

use Broarm\CookieConsent\CookieConsent;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\TextField;

/**
 * A description for a used cookie
 *
 * @package Broarm
 * @subpackage CookieConsent
 *
 * @property string ConfigName
 * @property string Title
 * @property string Provider
 * @property string Purpose
 * @property string Expiry
 * @property string Type
 *
 * @method CookieGroup Group()
 */
class CookieDescription extends DataObject
{
    private static $table_name = 'CookieDescription';

    private static $db = array(
        'ConfigName' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'Provider' => 'Varchar(255)',
        'Purpose' => 'Varchar(255)',
        'Expiry' => 'Varchar(255)'
    );

    private static $has_one = array(
        'Group' => CookieGroup::class
    );

    private static $summary_fields = array(
        'Title',
        'Provider',
        'Purpose',
        'Expiry'
    );

    private static $translate = array(
        'Title',
        'Provider',
        'Purpose',
        'Expiry'
    );

    private static $singular_name = 'Cookie description';

    private static $plural_name = 'Cookie descriptions';

    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root', $mainTab = Tab::create('Main')));
        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title', $this->fieldLabel('Title')),
            TextField::create('Provider', $this->fieldLabel('Provider')),
            TextField::create('Purpose', $this->fieldLabel('Purpose')),
            TextField::create('Expiry', $this->fieldLabel('Expiry'))
        ));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

    /**
     * Cookies without a config definition can be deleted
     *
     * @param null $member
     * @return bool
     */
    public function canDelete($member = null)
    {
        $cookieConfig = Config::inst()->get(CookieConsent::class, 'cookies');
        $found = false;
        foreach ($cookieConfig as $group => $domains) {
            if ($found) break;
            foreach ($domains as $cookies) {
                if ($found) break;
                $found = in_array($this->ConfigName, $cookies);
            }
        }

        return !$found;
    }
}
