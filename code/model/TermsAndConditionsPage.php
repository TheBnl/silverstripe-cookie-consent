<?php

use Broarm\CookieConsent\CookieConsent;

/**
 * Model for creating a default terms and conditions page
 *
 * @package Broarm
 * @subpackage CookieConsent
 */
class TermsAndConditionsPage extends Page
{
    private static $defaults = array(
        'ShowInMenus' => 0
    );

    public function populateDefaults()
    {
        $this->Title = _t('CookieConsent.TermsAndConditionsPageTitle', 'Terms and Conditions');
        $this->Content = _t('CookieConsent.TermsAndConditionsPageContent', '<p>Default terms and conditions</p>');
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
            $page->flushCache();
            DB::alteration_message('Terms and Conditions page created', 'created');
        }
    }
}

class TermsAndConditionsPage_Controller extends Page_Controller
{
    public function init()
    {
        parent::init();
    }
}
