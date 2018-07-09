<?php

use Broarm\CookieConsent\CookieConsent;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DB;

/**
 * Model for creating a default privacy policy page
 *
 * @package Broarm
 * @subpackage CookieConsent
 */
class PrivacyPolicyPage extends Page
{
    private static $defaults = array(
        'ShowInMenus' => 0
    );

    public function populateDefaults()
    {
        $this->Title = _t('CookieConsent.PrivacyPolicyPageTitle', 'Privacy Policy');
        $this->Content = _t('CookieConsent.PrivacyPolicyPageContent', '<p>Default privacy policy</p>');
        parent::populateDefaults();
    }

    /**
     * @throws Exception
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        if (Config::inst()->get(CookieConsent::class, 'create_default_pages') && !self::get()->exists()) {
            $page = self::create();
            $page->write();
            $page->flushCache();
            DB::alteration_message('Privacy Policy page created', 'created');
        }
    }
}
