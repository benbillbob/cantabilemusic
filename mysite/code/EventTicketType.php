<?php
class EventTicketType extends Item {
	private static $db = array (
		'NumberOfTickets' => 'int'
	);
	
	private static $has_one = array(
		'Event' => 'Event'
	);

	private static $has_many = array(
		'EventTicketLines' => 'EventTicketLine'
	);
}