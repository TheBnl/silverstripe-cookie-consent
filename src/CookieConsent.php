<?php

namespace Broarm\CookieConsent;

use Broarm\CookieConsent\Model\CookieGroup;
use Exception;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\Cookie;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class CookieConsent
 *
 * @package Broarm
 * @subpackage CookieConsent
 */
class CookieConsent
{
    use Extensible;
    use Injectable;
    use Configurable;

    const COOKIE_NAME = 'CookieConsent';
    const NECESSARY = 'Necessary';
    const ANALYTICS = 'Analytics';
    const MARKETING = 'Marketing';
    const PREFERENCES = 'Preferences';

    private static $required_groups = [
        self::NECESSARY
    ];

    private static $cookies = [];

    private static $include_javascript = true;

    private static $include_css = true;

    private static $create_default_pages = true;
    
    private static $xhr_mode = false;

    /**
     * Check if there is consent for the given cookie
     *
     * @param $group
     * @return bool
     * @throws Exception
     */
    public static function check($group = CookieConsent::NECESSARY)
    {
        $cookies = self::config()->get('cookies');
        if (!isset($cookies[$group])) {
            throw new Exception(sprintf(
                "The cookie group '%s' is not configured. You need to add it to the cookies config on %s",
                $group,
                self::class
            ));
        }

        $consent = self::getConsent();
        return array_search($group, $consent) !== false;
    }

    /**
     * Grant consent for the given cookie group
     *
     * @param $group
     */
    public static function grant($group)
    {
        $consent = self::getConsent();
        if (is_array($group)) {
            $consent = array_merge($consent, $group);
        } else {
            array_push($consent, $group);
        }
        self::setConsent($consent);
    }

    /**
     * Grant consent for all the configured cookie groups
     */
    public static function grantAll()
    {
        $consent = array_keys(Config::inst()->get(CookieConsent::class, 'cookies'));
        self::setConsent($consent);
    }

    /**
     * Remove consent for the given cookie group
     *
     * @param $group
     */
    public static function remove($group)
    {
        $consent = self::getConsent();
        $key = array_search($group, $consent);
        $cookies = Config::inst()->get(CookieConsent::class, 'cookies');
        if (isset($cookies[$group])) {
            foreach ($cookies[$group] as $host => $cookies) {
                $host = ($host === CookieGroup::LOCAL_PROVIDER)
                    ? Director::host()
                    : str_replace('_', '.', $host);
                foreach ($cookies as $cookie) {
                    Cookie::force_expiry($cookie, null, $host);
                }
            }
        }

        unset($consent[$key]);
        self::setConsent($consent);
    }

    /**
     * Get the current configured consent
     *
     * @return array
     */
    public static function getConsent()
    {
        return explode(',', Cookie::get(CookieConsent::COOKIE_NAME));
    }

    /**
     * Save the consent
     *
     * @param $consent
     */
    public static function setConsent($consent)
    {
        $consent = array_filter(array_unique(array_merge($consent, self::config()->get('required_groups'))));
        $domain = self::config()->get('domain') ?: null;
        Cookie::set(CookieConsent::COOKIE_NAME, implode(',', $consent), 730, null, $domain, false, false);
    }

    /**
     * Check if the group is required
     *
     * @param $group
     * @return bool
     */
    public static function isRequired($group)
    {
        return in_array($group, self::config()->get('required_groups'));
    }
}
