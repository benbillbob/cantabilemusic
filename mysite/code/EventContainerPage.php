<?php
require_once('InvoicePage.php'); 

class EventContainerPage extends InvoicePage {
	private static $db = array ();
	private static $has_many = array(
        'Events' => 'Event'
    );
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Events', GridField::create('Events', 'Events', $this->Events(), GridFieldConfig_RecordEditor::create()));
		return $fields;
	}}


class EventContainerPage_Controller extends InvoicePage_Controller 
{
	public function init() 
	{
		Requirements::set_write_js_to_body(false);
		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
		Requirements::javascript("mysite/external/fullcalendar/moment.min.js");
		Requirements::javascript("mysite/external/fullcalendar/fullcalendar.min.js");
		Requirements::css("mysite/external/fullcalendar/fullcalendar.min.css");
		
		Requirements::customScript('
		$(document).ready(function() {
			jQuery("#calendar").fullCalendar({
				events: "'.$this->data()->Link().'events"
			});
			jQuery("#PrintButton").click(function()
			{
				window.print();
				return false;
			});
		});');
		parent::init();
	}
	
	private static $allowed_actions = array(
        'createInvoice',
		'show',
		'events',
		'ticket',
		'barcode',
		'tickets'
	);
	
	public function events(SS_HTTPRequest $request) {
		$this->getResponse()->addHeader('Content-type', 'application/json');		

		$start = $request->getVars()['start'];
		$end = $request->getVars()['end'];
		
		$format = '{
			"title": "%s",
			"start": "%s",
			"end": "%s",
			"url": "%s"},
		';
		
		$events = Event::get();

		if ($start && $end){
			$events = $events->filter(array('EventStartDate:GreaterThan' => $start, 'EventStartDate:LessThan' => $end));
		}

		if (count($events) == 0){
			return '';
		}
		
		$data = '[';
		
		foreach($events as $event) {
			$data = $data . sprintf($format, $event->Title, $event->EventStartDateTime(), $event->EventEndDateTime(), $event->Link());
		}
		
		$data = substr(trim($data), 0, -1);
		$data = $data.']';
		
		return $data;
	}
	
	public function show(SS_HTTPRequest $request) {
		if ($request->param('OtherID') == 'createInvoice'){
			return $this->createInvoice($request);
		}
		
		$event = Event::get()->byID($request->param('ID'));
		if (!$event){
			return $this->httpError(404, 'Could not find event');
		}
		
		return array('Event' => $event);
	}
	
	public function barcode(SS_HTTPRequest $request) {
		$code = $request->param('ID');
		$type = "PDF417,5,1";

		$barcodeobj = new TCPDF2DBarcode($code, $type);

		return $barcodeobj->getBarcodePNG();
	}
	
	public function ticket(SS_HTTPRequest $request) {
		$ticket = EventTicket::get()->filter(array('Barcode' => $request->param('ID')))->first();
		if (!$ticket){
			return $this->httpError(404, 'Could not find ticket');
		}

		return array('Ticket' => $ticket);
	}
	
	public function tickets(SS_HTTPRequest $request) {
		//if (!Permission::check('ADMIN')){
		//	return $this->httpError(401, 'Not Authorized');
		//}
		
		$this->getResponse()->addHeader("Content-type", "text/plain");
		$items = [];
		$tickets = EventTicket::get();
		
		foreach($tickets as $ticket) {
			$lines = $ticket->EventTicketLines();
			$adult = 0;
			$child = 0;
			$concession = 0;
			foreach($lines as $line){
				$ticketType = $line->EventTicketTypeID;
				if ($ticketType == 359){
					$adult = $line->Quantity;
				}
				else if ($ticketType == 360){
					$child = $line->Quantity;
				}
				else if ($ticketType == 361){
					$concession = $line->Quantity;
				}
			}
			$items[] = array('Barcode' => $ticket->Barcode, 'Adult' => $adult, 'Child' => $child, 'Concession' => $concession);
		}
		
		$json = json_encode($items);
		$this->getResponse()->setBody($json);
		return $this->getResponse();
	}
}

