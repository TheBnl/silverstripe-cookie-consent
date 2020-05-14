<?php

namespace Broarm\CookieConsent\Extensions;

use Broarm\CookieConsent\Model\CookieGroup;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\FieldList;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Class SiteConfigExtension
 * @package Broarm\CookieConsent
 */
class SiteConfigExtension extends DataExtension
{
    private static $db = array(
        'CookieConsentTitle' => 'Varchar(255)',
        'CookieConsentContent' => 'HTMLText'
    );

    private static $translate = array(
        'CookieConsentTitle',
        'CookieConsentContent'
    );

    /**
     * @param FieldList $fields
     * @return FieldList|void
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.CookieConsent', array(
            TextField::create('CookieConsentTitle', $this->owner->fieldLabel('CookieConsentTitle')),
            HtmlEditorField::create('CookieConsentContent', $this->owner->fieldLabel('CookieConsentContent')),
            GridField::create('Cookies', 'Cookies', CookieGroup::get(), GridFieldConfig_RecordEditor::create())
        ));
    }

    /**
     * Set the defaults this way beacause the SiteConfig is probably already created
     *
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function requireDefaultRecords()
    {
        if ($config = SiteConfig::current_site_config()) {
            if (empty($config->CookieConsentTitle)) {
                $config->CookieConsentTitle = _t(__CLASS__ . '.CookieConsentTitle', 'This website uses cookies');
            }

            if (empty($config->CookieConsentContent)) {
                $config->CookieConsentContent = _t(__CLASS__ . '.CookieConsentContent', '<p>We use cookies to personalise content, to provide social media features and to analyse our traffic. We also share information about your use of our site with our social media and analytics partners who may combine it with other information that you’ve provided to them or that they’ve collected from your use of their services. You consent to our cookies if you continue to use our website.</p>');
            }

            $config->write();
        }
    }
}
