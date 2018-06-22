export const CookieConsent = function () {
    this.cookieName = 'CookieConsent';
    this.cookieJar = [];
    this.consent = [];

    this.init = function () {
        let cookies = document.cookie ? document.cookie.split('; ') : [];
        for (let i = 0; i < cookies.length; i++) {
            let parts = cookies[i].split('=');
            let key = parts[0];
            this.cookieJar[key] = parts.slice(1).join('=');
        }

        this.consent = this.isSet()
            ? decodeURIComponent(this.cookieJar[this.cookieName]).split(',')
            : [];
    };

    this.isSet = function () {
        return this.cookieJar[this.cookieName] !== undefined;
    };

    this.check = function (group) {
        return this.consent.indexOf(group) !== -1;
    };

    this.pushToDataLayer = function() {
        if (typeof dataLayer !== 'undefined') {
            if (this.check('Prefrences')) {
                dataLayer.push({'event':'cookieconsent_preferences'});
            }
            if (this.check('Analytics')) {
                dataLayer.push({'event':'cookieconsent_analytics'});
            }
            if (this.check('Marketing')) {
                dataLayer.push({'event':'cookieconsent_marketing'});
            }
        }
    };

    this.init();
};
