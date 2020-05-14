<?php

namespace Broarm\CookieConsent\Forms;

use Broarm\CookieConsent\CookieConsent;
use Broarm\CookieConsent\Model\CookieGroup;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;

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

        $actions = FieldList::create(FormAction::create('submitConsent', _t(__CLASS__ . '.Save', 'Save')));
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
        foreach (CookieConsent::config()->get('cookies') as $group => $cookies) {
            if (isset($data[$group]) && $data[$group]) {
                CookieConsent::grant($group);
            } elseif ($group !== CookieGroup::REQUIRED_DEFAULT) {
                CookieConsent::remove($group);
            }
        }

        $form->sessionMessage(_t(__CLASS__ . '.FormMessage', 'Your preferences have been saved'), 'good');
        $this->getController()->redirectBack();
    }
}
