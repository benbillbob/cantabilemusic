<div id="Content">
<h4>Thank you for your purchase.</h4>

<p>Your purchase has been processed</p>
<ul>
    <% loop $InvoiceLines %>
        <li>$Quantity x $Item.ItemName</li>
    <% end_loop %>
	
	<% if $EventTicket %>
		<h3><a href=$EventTicket.Link()>Click Here to view and print your Ticket</a></h3>
	<% end_if %>
</ul>
</div>