<?php

namespace Broarm\CookieConsent\Model;

use Broarm\CookieConsent\Control\CookiePolicyPageController;
use \Page;
use Broarm\CookieConsent\CookieConsent;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\Versioned\Versioned;

/**
 * Model for creating a default cookie policy page
 * This is the page where cookie settings can be edited and user can read about what cookies your using
 *
 * @package Broarm
 * @subpackage CookieConsent
 */
class CookiePolicyPage extends Page
{
    private static $table_name = 'CookiePolicyPage';

    private static $controller_name = CookiePolicyPageController::class;

    private static $defaults = array(
        'ShowInMenus' => 0
    );

    public function populateDefaults()
    {
        $this->Title = _t(__CLASS__ . '.Title', 'Cookie Policy');
        $this->Content = _t(__CLASS__ .'.Content', '<p>$CookieConsentForm</p><p>[cookiegrouptable group="Necessary"]</p><p>[cookiegrouptable group="Analytics"]</p><p>[cookiegrouptable group="Marketing"]</p><p>[cookiegrouptable group="Preferences"]</p>');
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
            $page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);
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

    public function canCreate($member = null, $context = [])
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
