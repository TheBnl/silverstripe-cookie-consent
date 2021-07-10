# Silverstripe Cookie Consent
GDPR compliant cookie bar and consent checker

## Installation
Install the module trough composer:
```bash
composer require bramdeleeuw/cookieconsent
``` 

Include the popup template in your base Page.ss
```html
<% include CookieConsent %>
```

## Configuration
You can configure the cookies and cookie groups trough the yml config. You need to configure by provider, for providers the dots are converted to underscores e.g. ads.marketingcompany.com becomes ads_marketingcompany_com.

By configuring cookies trough yml you can check for consent in your code and make the necessary changes e.g. require the analytics or other cookies or skip placing them.

The texts for the configured cookies are editable trough the Site Config, here other cookies can also be added by CMS users. 
For example if a site user decides to embed a Youtube video he or she can specify the cookies that are placed by Youtube.
I reccomend the following three groups to be created, these have default content, of course you are free to configure groups as you see fit.
```yaml
Broarm\CookieConsent\CookieConsent:
  cookies:
    Necessary:
      local:
        - CookieConsent
        - ShowPopUp
    Marketing:
      ads_marketingcompany_com:
        - _track
    Analytics:
      local:
        - _ga
        - _gid
```

This module comes with some default content for cookies we've encountered before. If you want to set default content for these cookies yourself that is possible trough the lang files. If you have cookie descriptions that are not in this module, contributions to the lang files are much appreciated! Translations are managed trough [Transifex](https://www.transifex.com/xd/cookie-consent).

The files are structured as such:
```yaml
en:
  CookieConsent_{provider}:
    {cookie}_Purpose: 'Cookie description'
    {cookie}_Expiry: 'Cookie expire time'
  # for cookies from your own domain:
  CookieConsent_local:
    PHPSESSID_Purpose: 'Session'
    PHPSESSID_Expiry: 'Session'
  # for cookies from an external domain:
  CookieConsent_ads_marketingcompany_com:
    _track_Purpose: 'Cookie description'
    _track_Expiry: 'Cookie expire time'
```

Then you can check for consent in your code by calling
```php
if (CookieConsent::check('Analytics')) {
    // include google analytics
}
```

You can also configure the requirement of the default js and css. 
Make sure you combine at least the javascript in you bundle if you chose not to require by default!
```yaml
Broarm\CookieConsent\CookieConsent:
  include_javascript: true
  include_css: true
  create_default_pages: true
```

### Enable XHR mode
When you use static publishing, you'll want to enable XHR mode. XHR mode accepts the cookies trough an xhr request and shows/hides the consent popup with the help of some javascript.

In your yml config set `xhr_mode` to `true`
```yaml
Broarm\CookieConsent\CookieConsent:
  xhr_mode: true
```

In your javascript, you can make use of the utility class. This handles the xhr request and visibility of the popup:
```js
import CookieConsent from '../vendor/bramdeleeuw/cookieconsent/javascript/src/cookieconsent';
const consent = new CookieConsent();
consent.enableXHRMode();
```

### Include assets in your bundle
If you want to include the scss or js in your own bundle you can do that by:
```js
// Import the CookieConsent utility 
import CookieConsent from '../vendor/bramdeleeuw/cookieconsent/javascript/src/cookieconsent';

const consent = new CookieConsent();

// This tool let's you check for cookie consent in your js files before you apply any cookies
if (consent.check('Marketing')) {
  // add marketing cookie
}

// If you use Google Tag Manager this tool can also push the consent into the dataLayer object
consent.pushToDataLayer();
```

For the scss you can just import the scss file
```scss
@import "cookieconsent/scss/cookieconsent";
```
Make sure the relative paths to the files match your use case.

## Default Pages
This module also sets up 3 default pages on running dev/build. 
If you want to prevent that behaviour you should disable the `create_default_pages` config setting.
The pages created are a CookiePolicyPage, PrivacyPolicyPage and TermsAndConditionsPage and are filled with bare bones content for each of the page types.
_Of course it is your or your CMS users responsibility to alter these texts to make them fitting to your use case!_

### Maintainers 
[Bram de Leeuw](http://www.twitter.com/bramdeleeuw)
