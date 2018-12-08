<style>
@media print{
	.nonPrint{display: none;}
	
	#TicketBorder {
		padding: 10px 10px 10px 10px;
		border-style:solid;
		border-width:2px;
	}
}
</style>
<div id=TicketBorder class="content">
		<% with $Ticket %>
			<figure style="text-align: center;" class="item">
				<img src="{$Up.Link}barcode/$Barcode"></img>
				<figcaption class="caption">$Barcode</figcaption>
			</figure>
			<h1>Sutherland Shire Children's Choir</h1>
			<h1>$Event.Title</h1>
			<h1>Engadine Community Centre Auditorium</h1>
			<h2>$Event.EventStartTime.Nice $Event.EventStartDate.Day $Event.EventStartDate.Long</h2>
			<br>
			<% loop $EventTicketLines %>
				<h2>$Quantity x $EventTicketType.ItemName</h2>
			<% end_loop %>
			<br>
		<% end_with %>
		<br>
		<h1><a class="nonPrint" href="#" id="PrintButton">Click here to print</a></h1>
		<br>
		<h2>Terms and Conditions</h2>
		<h3>There are no refunds on tickets.</h3>
		<h3>All people on this ticket must enter the auditorium together as a group.</h3>

</div>