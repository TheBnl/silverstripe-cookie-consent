import 'jquery';
import 'foundation-sites/js/foundation.core';
import {Reveal} from 'foundation-sites/js/foundation.reveal';
import {CookieConsent} from './cookieconsent'

(($) => {
    'use strict';

    $(document).ready(function () {
        initCookieConsentPopup();
    });

})(jQuery);

export const initCookieConsentPopup = function () {
    const Consent = new CookieConsent();
    if (!Consent.isSet()) {
        new Reveal($('#cookie-consent'), {
            closeOnClick: false,
            closeOnEsc: false
        }).open();
    }
};
