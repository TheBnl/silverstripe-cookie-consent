<% if $PromptCookieConsent %>
    <style>
        body {
            overflow: hidden;
        }
    </style>
    <div class="cookie-consent-background">
        <div class="cookie-consent" id="cookie-consent">
            <h3>$SiteConfig.CookieConsentTitle</h3>
            $SiteConfig.CookieConsentContent
            <div class="cookie-consent__buttons">
                <a class="button cookie-consent__button cookie-consent__button--accept" href="$AcceptAllCookiesLink" rel="nofollow">
                    <%t Broarm\\CookieConsent\\CookieConsent.AcceptAllCookies 'Accept all cookies' %>
                </a>
                <a class="button hollow cookie-consent__button cookie-consent__button--manage" href="$CookiePolicyPage.Link">
                    <%t Broarm\\CookieConsent\\CookieConsent.ManageCookies 'Manage cookie settings' %>
                </a>
            </div>
        </div>
    </div>
<% end_if %>
