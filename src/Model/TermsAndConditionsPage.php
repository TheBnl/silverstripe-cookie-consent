<?php

namespace Broarm\CookieConsent\Model;

use \Page;
use Broarm\CookieConsent\CookieConsent;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DB;

/**
 * Model for creating a default terms and conditions page
 *
 * @package Broarm
 * @subpackage CookieConsent
 */
class TermsAndConditionsPage extends Page
{
    private static $table_name = 'TermsAndConditionsPage';

    private static $defaults = array(
        'ShowInMenus' => 0
    );

    public function populateDefaults()
    {
        $this->Title = _t(__CLASS__ . '.Title', 'Terms and Conditions');
        $this->Content = _t(__CLASS__ .'.Content', '<p>Default terms and conditions</p>');
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
            DB::alteration_message('Terms and Conditions page created', 'created');
        }
    }
}
