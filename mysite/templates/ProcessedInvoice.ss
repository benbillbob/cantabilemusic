<h4>Thank you for your purchase.</h4>

Your purchase of
<ul>
    <% loop $InvoiceLines %>
        <li>$Quantity x $Item.ItemName</li>
    <% end_loop %>
	
	<% if $EventTicket %>
		<a href=$EventTicket.Link()>Ticket Link</a>
	<% end_if %>
</ul>
has been processed
