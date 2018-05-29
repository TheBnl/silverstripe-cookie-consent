<?php

use Broarm\CookieConsent\CookieConsent;
use Broarm\CookieConsent\CookieConsentForm;

/**
 * Model for creating a default cookie policy page
 * This is the page where cookie settings can be edited and user can read about what cookies your using
 *
 * @package Broarm
 * @subpackage CookieConsent
 */
class CookiePolicyPage extends Page
{
    private static $defaults = array(
        'ShowInMenus' => 0
    );

    public function populateDefaults()
    {
        $this->Title = _t('CookieConsent.CookiePolicyPageTitle', 'Cookie Policy');
        $this->Content = _t('CookieConsent.CookiePolicyPageContent', '<p>Default cookie policy</p>');
        parent::populateDefaults();
    }

    /**
     * @throws ValidationException
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        if (Config::inst()->get(CookieConsent::class, 'create_default_pages') && !self::get()->exists()) {
            $page = self::create();
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('Cookie Policy page created', 'created');
        }
    }

    /**
     * Get the active cookie policy page
     *
     * @return CookiePolicyPage|DataObject
     */
    public static function instance()
    {
        return self::get()->first();
    }

    public function canCreate($member = null)
    {
        if (self::get()->exists()) {
            return false;
        } else {
            return parent::canCreate($member);
        }
    }

    public function canDelete($member = null)
    {
        return false;
    }
}

class CookiePolicyPage_Controller extends Page_Controller
{
    private static $allowed_actions = array('CookieConsentForm');

    public function init()
    {
        parent::init();
        Requirements::block('cookieconsent/javascript/dist/cookieconsentpopup.js');
    }

    public function CookieConsentForm()
    {
        return CookieConsentForm::create($this, 'CookieConsentForm');
    }
}
