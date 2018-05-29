<?php

namespace Broarm\CookieConsent;

use CookiePolicyPage;
use Extension;
use Config;
use Requirements;

/**
 * Class ContentControllerExtension
 * @package Broarm\CookieConsent
 * @property \ContentController owner
 */
class ContentControllerExtension extends Extension
{
    private static $allowed_actions = array('acceptAllCookies');

    /**
     * Place the necessary js and css
     *
     * @throws \Exception
     */
    public function onAfterInit()
    {
        if (Config::inst()->get(CookieConsent::class, 'include_javascript')) {
            Requirements::javascript('cookieconsent/javascript/dist/cookieconsent.js');
            if (!CookieConsent::check(CookieGroup::REQUIRED_DEFAULT)) {
                Requirements::javascript('cookieconsent/javascript/dist/cookieconsentpopup.js');
            }
        }

        if (Config::inst()->get(CookieConsent::class, 'include_css')) {
            Requirements::css('cookieconsent/css/cookieconsent.css');
        }
    }

    /**
     * Method for checking cookie consent in template
     *
     * @param $group
     * @return bool
     * @throws \Exception
     */
    public function CookieConsent($group = CookieGroup::REQUIRED_DEFAULT)
    {
        return CookieConsent::check($group);
    }

    /**
     * Get an instance of the cookie policy page
     *
     * @return CookiePolicyPage|\DataObject
     */
    public function getCookiePolicyPage()
    {
        return CookiePolicyPage::instance();
    }

    public function acceptAllCookies()
    {
        CookieConsent::grantAll();
        $this->owner->redirectBack();
    }

    public function getAcceptAllCookiesLink()
    {
        return $this->owner->Link('acceptAllCookies');
    }
}
