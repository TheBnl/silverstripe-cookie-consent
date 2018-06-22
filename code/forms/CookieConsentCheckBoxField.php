<?php

namespace Broarm\CookieConsent;

use CheckboxField;

/**
 * Class CookieConsentCheckBoxField
 *
 * @author Bram de Leeuw
 */
class CookieConsentCheckBoxField extends CheckboxField
{
    protected $template = 'CookieConsentCheckBoxField';

    protected $fieldHolderTemplate = 'CookieConsentField_holder';

    /**
     * @var CookieGroup
     */
    protected $cookieGroup;

    public function __construct(CookieGroup $cookieGroup)
    {
        $this->cookieGroup = $cookieGroup;
        parent::__construct(
            $cookieGroup->ConfigName,
            $cookieGroup->Title,
            $cookieGroup->isRequired()
        );

        $this->setDisabled($cookieGroup->isRequired());
    }

    public function getContent()
    {
        return $this->cookieGroup->dbObject('Content');
    }

    public function getCookieGroup()
    {
        return $this->cookieGroup;
    }
}
