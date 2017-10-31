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
			<ul>
			<h1>$Event.Title</h1>
			<h4>$Event.EventStartDate.Nice</h4>
			<h4>$Event.EventStartTime.Nice</h4>
			<% loop $EventTicketLines %>
				<li><h3>$Quantity x $EventTicketType.ItemName</h3></li>
			<% end_loop %>
			</ul>
		<% end_with %>
		<h1><a class="nonPrint" href="#" id="PrintButton">Click here to print</a></h1>
</div>