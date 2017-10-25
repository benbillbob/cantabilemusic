<?php
class EventTicketType extends Item {
	private static $db = array (
		'NumberOfTickets' => 'int'
	);
	
	private static $has_one = array(
		'Event' => 'Event'
	);
}