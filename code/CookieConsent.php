<?php

namespace Broarm\CookieConsent;

use Config;
use Cookie;
use Director;
use Exception;
use Object;

class CookieConsent extends Object
{
    const COOKIE_NAME = 'CookieConsent';

    private static $cookies = array();

    private static $include_javascript = true;

    private static $include_css = true;

    private static $create_default_pages = true;

    /**
     * Check if there is consent for the given cookie
     *
     * @param $group
     * @return bool
     * @throws Exception
     */
    public static function check($group = CookieGroup::REQUIRED_DEFAULT)
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
        array_push($consent, $group);
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
            foreach ($cookies[$group] as $cookie) {
                $host = Director::protocolAndHost();
                $host = str_replace(Director::protocol(), '', $host);
                $host = str_replace('www.', '', $host);
                Cookie::force_expiry($cookie, null, ".$host");
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
        array_push($consent, CookieGroup::REQUIRED_DEFAULT);
        Cookie::set(CookieConsent::COOKIE_NAME, implode(',', array_unique($consent)), 90, null, null, false, false);
    }
}
