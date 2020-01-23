<?php

use Broarm\CookieConsent\CookieGroupTable;

if (!class_exists('SS_Object'))
{
	class_alias('Object', 'SS_Object');
}

CookieGroupTable::register();
