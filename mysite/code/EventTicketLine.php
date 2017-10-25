<?php
class EventTicketLine extends Item {
	private static $db = array (
		'Quantity' => 'Int'
	);
	
	private static $has_one = array(
		'EventTicket' => 'EventTicket',
		'EventTicketType' => 'EventTicketType'
	);
}