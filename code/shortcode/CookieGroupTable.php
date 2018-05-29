<?php
/**
 * Class ${NAME}
 *
 * @author Bram de Leeuw
 */

namespace Broarm\CookieConsent;

use ShortcodeParser;

class CookieGroupTable
{
    /**
     * Register the cookie group table shortcode parser
     */
    public static function register()
    {
        ShortcodeParser::get('default')->register('cookiegrouptable', function ($arguments, $address, $parser, $shortcode) {
            $group = (isset($arguments['group']) && $arguments['group']) ? $arguments['group'] : CookieGroup::REQUIRED_DEFAULT;
            if ($group = CookieGroup::get()->find('ConfigName', $group)) {
                return $group->renderWith('CookieGroupTable')->getValue();
            }

            return '[cookiegrouptable]';
        });
    }
}
