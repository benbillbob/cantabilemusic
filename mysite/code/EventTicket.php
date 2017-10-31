<?php
//require_once('/mysite/external/picqer/php-barcode-generator/src/BarcodeGeneratorHTML.php');

class EventTicket extends DataObject {
	private static $db = array (
		'Barcode' => 'Varchar'
	);
	
	private static $has_one = array(
		'Event' => 'Event',
		'Member' => 'Member'
	);

	private static $has_many = array(
		'EventTicketLines' => 'EventTicketLine'
	);
	
	public function Link(){
		return $this->Event()->EventContainerPage()->Link('ticket/' . $this->Barcode);
	}
}