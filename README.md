# Silverstripe Cookie Consent
GDPR compliant cookie bar and consent checker

## Installation
Install the module trough composer:
```bash
composer require bramdeleeuw/cookieconsent
``` 

## Configuration
You can configure the cookies and cookie groups trough the yml config. 
By configuring cookies trough yml you can check for consent in your code and make the necessary changes e.g. require the analytics or other cookies or skip placing them.
The texts for the configured cookies are editable trough the Site Config, here other cookies can also be added by CMS users. 
For example if a site user decides to embed a Youtube video he or she can specify the cookies that are placed by Youtube.
I reccomend the following three groups to be created, these have default content, of course you are free to configure groups as you see fit.
```yaml
XD\CookieConsent\CookieConsent:
  cookies:
    Necessary:
      - CookieConsent
      - ShowPopUp
    Marketing:
      - _track
    Analytics:
      - _ga
      - _gid
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
XD\CookieConsent\CookieConsent:
  include_javascript: true
  include_css: true
  create_default_pages: true
```

### Include assets in your bundle
If you want to include the scss or js in your own bundle you can do that by:
```js
// Import the CookieConsent utility 
import {CookieConsent} from 'cookieconsent/javascript/src/cookieconsent';

// This tool let's you check for cookie consent in your js files before you apply any cookies
if (CookieConsent.check('Marketing')) {
  // add marketing cookie
}

// Import the CookieConsentPopup
import {initCookieConsentPopup} from 'cookieconsent/javascript/src/cookieconsentpopup';

// Run this where you initialize your scripts on document ready
$(document).ready(function () {
    initCookieConsentPopup();
});
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
