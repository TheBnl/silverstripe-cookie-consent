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
        $this->Content = _t('CookieConsent.CookiePolicyPageContent', '<p>$CookieConsentForm</p><p>[cookiegrouptable]</p>');
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

/**
 * Class CookiePolicyPage_Controller
 * @mixin CookiePolicyPage
 */
class CookiePolicyPage_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'Form'
    );

    /**
     * Make sure we don't trigger the cookie consent popup on this page
     *
     * @return SS_HTTPResponse|void
     */
    public function init()
    {
        parent::init();
        Requirements::block('cookieconsent/javascript/dist/cookieconsentpopup.js');
    }

    /**
     * Using $CookieConsentForm in the Content area of the page shows
     * where the form should be rendered into. If it does not exist
     * then default back to $Form.
     *
     * Blatantly ripped off with love from UserDefinedForm
     *
     * @return array
     */
    public function index()
    {
        if ($this->Content && $form = $this->Form()) {
            $hasLocation = stristr($this->Content, '$CookieConsentForm');
            if ($hasLocation) {
                $content = preg_replace('/(<p[^>]*>)?\\$CookieConsentForm(<\\/p>)?/i', $form->forTemplate(), $this->Content);
                return array(
                    'Content' => DBField::create_field('HTMLText', $content),
                    'Form' => ""
                );
            }
        }

        return array(
            'Content' => DBField::create_field('HTMLText', $this->Content),
            'Form' => $this->Form()
        );
    }

    /**
     * Get the CookieConsentForm
     *
     * @return CookieConsentForm
     */
    public function Form()
    {
        return CookieConsentForm::create($this, 'Form');
    }
}
