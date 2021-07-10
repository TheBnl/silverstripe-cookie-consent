<?php

namespace Broarm\CookieConsent\Extensions;

use Broarm\CookieConsent\CookieConsent;
use Broarm\CookieConsent\Control\CookiePolicyPageController;
use Broarm\CookieConsent\Model\CookiePolicyPage;
use Exception;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Security;
use SilverStripe\View\Requirements;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;

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
        if ($this->CookieConsentIsInXHRMode() || !($this->owner instanceof Security) && Config::inst()->get(CookieConsent::class, 'include_css') && !CookieConsent::check()) {
            $module = ModuleLoader::getModule('bramdeleeuw/cookieconsent');
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
     * Check if cookie consent is in XHR mode
     */
    public function CookieConsentIsInXHRMode()
    {
        return CookieConsent::config()->get('xhr_mode');
    }
    
    /**
     * Check if we can promt for concent
     * We're not on a Securty or Cooky policy page and have no concent set
     *
     * @return bool
     */
    public function PromptCookieConsent()
    {
        $controller = Controller::curr();
        $securiy = $controller ? $controller instanceof Security : false;
        $cookiePolicy = $controller ? $controller instanceof CookiePolicyPageController : false;
        $hasConsent = CookieConsent::check();
        $prompt = !$securiy && !$cookiePolicy && !$hasConsent;
        $this->owner->extend('updatePromptCookieConsent', $prompt);
        return $prompt;
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

        // Get the url the same as the redirect back method gets it
        $url = $this->owner->getBackURL()
            ?: $this->owner->getReturnReferer()
                ?: Director::baseURL();

        $cachebust = uniqid();
        if (parse_url($url, PHP_URL_QUERY)) {
            $url = Director::absoluteURL("$url&acceptCookies=$cachebust");
        } else {
            $url = Director::absoluteURL("$url?acceptCookies=$cachebust");
        }

        $this->owner->redirect($url);
    }

    public function getAcceptAllCookiesLink()
    {
        return Controller::join_links('acceptAllCookies', 'acceptAllCookies');
    }
}
