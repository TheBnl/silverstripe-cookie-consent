<?php

namespace Broarm\CookieConsent;

use Config;
use Controller;
use Cookie;
use FieldList;
use Form;
use FormAction;
use Session;

/**
 * Class CookieConsentForm
 *
 * @author Bram de Leeuw
 */
class CookieConsentForm extends Form
{
    protected $extraClasses = array('cookie-consent-form');

    public function __construct(Controller $controller, $name)
    {
        $fields = FieldList::create();
        $cookieGroups = CookieGroup::get();
        $data = CookieConsent::getConsent();

        /** @var CookieGroup $cookieGroup */
        foreach ($cookieGroups as $cookieGroup) {
            $fields->add($field = $cookieGroup->createField());
            if (in_array($cookieGroup->ConfigName, $data)) {
                $field->setValue(1);
            }
        }

        $actions = FieldList::create(FormAction::create('submitConsent', _t('CookieConsent.Save', 'Save')));
        parent::__construct($controller, $name, $fields, $actions);
    }

    /**
     * Submit the consent
     *
     * @param $data
     * @param Form $form
     */
    public function submitConsent($data, Form $form)
    {
        CookieConsent::grant(CookieConsent::config()->get('required_groups'));
        foreach (Config::inst()->get(CookieConsent::class, 'cookies') as $group => $cookies) {
            if (isset($data[$group]) && $data[$group]) {
                CookieConsent::grant($group);
            } elseif (!CookieConsent::isRequired($group)) {
                CookieConsent::remove($group);
            }
        }

        $form->sessionMessage(_t('CookieConsentFormMessage', 'Your preferences have been saved'), 'good');
        $this->getController()->redirectBack();
    }
}
