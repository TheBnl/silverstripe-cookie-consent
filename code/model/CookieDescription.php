<?php

namespace Broarm\CookieConsent;

use DataObject;
use FieldList;
use HiddenField;
use Tab;
use TabSet;
use TextField;

/**
 * A description for a used cookie
 *
 * @package Broarm
 * @subpackage CookieConsent
 *
 * @property string ConfigName
 * @property string Title
 * @property string Provider
 * @property string Purpose
 * @property string Expiry
 * @property string Type
 *
 * @method CookieGroup Group()
 */
class CookieDescription extends DataObject
{
    private static $db = array(
        'ConfigName' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'Provider' => 'Varchar(255)',
        'Purpose' => 'Varchar(255)',
        'Expiry' => 'Varchar(255)'
    );

    private static $indexes = array(
        'ConfigName' => true
    );

    private static $has_one = array(
        'Group' => 'Broarm\\CookieConsent\\CookieGroup'
    );

    private static $summary_fields = array(
        'Title',
        'Provider',
        'Purpose',
        'Expiry'
    );

    private static $translate = array(
        'Title',
        'Provider',
        'Purpose',
        'Expiry'
    );

    private static $singular_name = 'Cookie description';

    private static $plural_name = 'Cookie descriptions';

    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root', $mainTab = Tab::create('Main')));
        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title', $this->fieldLabel('Title')),
            TextField::create('Provider', $this->fieldLabel('Provider')),
            TextField::create('Purpose', $this->fieldLabel('Purpose')),
            TextField::create('Expiry', $this->fieldLabel('Expiry'))
        ));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }
}
