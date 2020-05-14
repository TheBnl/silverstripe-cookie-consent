<h3>$Title</h3>
$Content
<div class="table-scroll">
    <table class="cookie-group-table">
        <thead class="cookie-group-table__header">
        <tr class="cookie-group-table__header-row">
            <th class="cookie-group-table__header-col cookie-group-table__header-col--name"><%t Broarm\\CookieConsent\\Shortcode\\CookieGroupTable.Title 'Name cookie' %></th>
            <th class="cookie-group-table__header-col cookie-group-table__header-col--provider"><%t Broarm\\CookieConsent\\Shortcode\\CookieGroupTable.Provider 'Placed by' %></th>
            <th class="cookie-group-table__header-col cookie-group-table__header-col--purpose"><%t Broarm\\CookieConsent\\Shortcode\\CookieGroupTable.Purpose 'Purpose' %></th>
            <th class="cookie-group-table__header-col cookie-group-table__header-col--expiry"><%t Broarm\\CookieConsent\\Shortcode\\CookieGroupTable.Expiry 'Expiry' %></th>
        </tr>
        </thead>
        <tbody class="cookie-group-table__body">
        <% loop $Cookies %>
            <tr class="cookie-group-table__body-row">
                <td class="cookie-group-table__body-col cookie-group-table__body-col--name">$Title</td>
                <td class="cookie-group-table__body-col cookie-group-table__body-col--provider">$Provider</td>
                <td class="cookie-group-table__body-col cookie-group-table__body-col--purpose">$Purpose</td>
                <td class="cookie-group-table__body-col cookie-group-table__body-col--expiry">$Expiry</td>
            </tr>
        <% end_loop %>
        </tbody>
    </table>
</div>
