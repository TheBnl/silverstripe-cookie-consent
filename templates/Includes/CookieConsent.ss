<% if not $CookieConsent %>
<div class="cookie-consent reveal" id="cookie-consent">
    <h3>$SiteConfig.CookieConsentTitle</h3>
    $SiteConfig.CookieConsentContent
    <div class="cookie-consent__buttons">
        <a class="button cookie-consent__button cookie-consent__button--accept" href="$AcceptAllCookiesLink"><%t CookieConsent.AcceptAllCookies 'Accept all cookies' %></a>
        <a class="button hollow cookie-consent__button cookie-consent__button--manage" href="$CookiePolicyPage.Link"><%t CookieConsent.ManageCookies 'Manage cookies' %></a>
    </div>
</div>
<% end_if %>
