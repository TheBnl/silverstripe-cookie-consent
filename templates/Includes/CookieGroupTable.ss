<h3>$Title</h3>
$Content
<table class="table-scroll">
    <thead>
    <tr>
        <th><%t CookieConsent.CookieGroupTableTitle 'Name cookie' %></th>
        <th><%t CookieConsent.CookieGroupTableProvider 'Placed by' %></th>
        <th><%t CookieConsent.CookieGroupTablePurpose 'Purpose' %></th>
        <th><%t CookieConsent.CookieGroupTableExpiry 'Expiry' %></th>
    </tr>
    </thead>
    <tbody>
    <% loop $Cookies %>
        <tr>
            <td>$Title</td>
            <td>$Provider</td>
            <td>$Purpose</td>
            <td>$Expiry</td>
        </tr>
    <% end_loop %>
    </tbody>
</table>
