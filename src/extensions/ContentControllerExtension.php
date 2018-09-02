<?php

namespace Broarm\CookieConsent;

use CookiePolicyPage;
use Exception;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Security;
use SilverStripe\View\Requirements;
use SilverStripe\Control\Controller;

/**
 * Class ContentControllerExtension
 * @package Broarm\CookieConsent
 * @property ContentController owner
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
        $security = $this->owner instanceof Security;
        $module = ModuleLoader::getModule('bramdeleeuw/cookieconsent');
        if (!$security && Config::inst()->get(CookieConsent::class, 'include_javascript')) {
            Requirements::javascript($module->getResource('javascript/dist/cookieconsent.js')->getRelativePath());
            if (!CookieConsent::check(CookieConsent::NECESSARY)) {
                Requirements::javascript($module->getResource('javascript/dist/cookieconsentpopup.js')->getRelativePath());
            }
        }

        if (!$security && Config::inst()->get(CookieConsent::class, 'include_css')) {
            Requirements::css($module->getResource('css/cookieconsent.css')->getRelativePath());
        }
    }

    /**
     * Method for checking cookie consent in template
     *
     * @param $group
     * @return bool
     * @throws Exception
     */
    public function CookieConsent($group = CookieConsent::NECESSARY)
    {
        return CookieConsent::check($group);
    }

    /**
     * Get an instance of the cookie policy page
     *
     * @return CookiePolicyPage|DataObject
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
        return Controller::join_links('acceptAllCookies', 'acceptAllCookies');
    }
}
