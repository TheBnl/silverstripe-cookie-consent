<div class="cookie-consent-field $Class">
    <div class="cookie-consent-field__label">
        <label for="$ID">$Title</label>
    </div>
    <div class="cookie-consent-field__field">
        $Field
    </div>
    <div class="cookie-consent-field__description">
        $Content
    </div>
</div>
<% if $Message %>
    <div class="cookie-consent-field__message cookie-consent-field__message--$MessageType">
        $Message
    </div>
<% end_if %>
