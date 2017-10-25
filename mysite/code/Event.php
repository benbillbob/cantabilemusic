<?php
class Event extends DataObject {
	private static $db = array (
		'Title' => 'Varchar',
		'Content' => 'HTMLText',
		'EventStartDate' => 'Date',
		'EventStartTime' => 'Time',
		'EventEndDate' => 'Date',
		'EventEndTime' => 'Time',
		'TotalTickets' => 'int',
		'OnSaleStartDate' => 'Date',
		'OnSaleStartTime' => 'Time',
		'OnSaleEndDate' => 'Date',
		'OnSaleEndTime' => 'Time'
	);
	
	private static $has_one = array(
		'EventContainerPage' => 'EventContainerPage'
	);
	
	private static $has_many = array(
		'EventTickets' => 'EventTicket',
		'EventTicketTypes' => 'EventTicketType'
	);
	
	public function getCMSFields() {
		$insertableFieldKeys = str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($this->InsertableFields(), true) . ' ?>', true ) );
		
		$fields = FieldList::create(
			TextField::create('Title'),
			ReadOnlyField::create('Fields', $insertableFieldKeys, 'Fields replaced in content'),
			HTMLEditorField::create('Content'),
			DateField::create('EventStartDate'),
			TimeField::create('EventStartTime'),
			DateField::create('EventEndDate'),
			TimeField::create('EventEndTime'),
			NumericField::create('TotalTickets'),
			DateField::create('OnSaleStartDate'),
			TimeField::create('OnSaleStartTime'),
			DateField::create('OnSaleEndDate'),
			TimeField::create('OnSaleEndTime'),
			GridField::create('EventTicketTypes', 'EventTicketTypes', $this->EventTicketTypes(), GridFieldConfig_RecordEditor::create())
		);
		
		return $fields;
	}
	
	private function InsertableFields()
	{
		return array(
			'$Title' => $this->Title,
			'$EventStartDate' => (new DateTime($this->EventStartDate))->format('d/m/Y'),
			'$EventEndDate' => (new DateTime($this->EventEndDate))->format('d/m/Y'),
			'$OnSaleStartDate' => (new DateTime($this->OnSaleStartDate))->format('d/m/Y'),
			'$OnSaleEndDate' => (new DateTime($this->OnSaleEndDate))->format('d/m/Y'),
			'$EventStartTime' => $this->EventStartTime,
			'$EventEndTime' => $this->EventEndTime,
			'$OnSaleStartTime' => $this->OnSaleStartTime,
			'$OnSaleEndTime' => $this->OnSaleEndTime
		);
	}
	
	public function ParsedContent(){
		$content = $this->Content;

		foreach ($this->InsertableFields() as $key => $value) {
			$content = str_replace($key, $value, $content);
		}
		
		return $content;
	}
	
	public function EventStartDateTime(){
		$date = $this->EventStartDate;
		$time = $this->EventStartTime;
		
		return Event::ISODateTimeString($date, $time);
	}

	public function EventEndDateTime(){
		$date = $this->EventEndDate;
		$time = $this->EventEndTime;
		
		return Event::ISODateTimeString($date, $time);
	}
	
	public function OnSaleStartDateTime(){
		$date = $this->OnSaleStartDate;
		$time = $this->OnSaleStartTime;
		
		return Event::ISODateTimeString($date, $time);
	}

	public function OnSaleEndDateTime(){
		$date = $this->OnSaleEndDate;
		$time = $this->OnSaleEndTime;
		
		return Event::ISODateTimeString($date, $time);
	}

	public static function ISODateTimeString($date, $time){
		if (!$date){
			return null;
		}
		
		$str = $date;
		if ($time){
			$str .=' '.$time;
		}
		
		$merge = new DateTime($str);
		return $merge->format(DateTime::ATOM);
	}
	
	public function OnSaleNow(){
		$start = $this->OnSaleStartDateTime();
		$end = $this->OnSaleEndDateTime();
		
		if (!$start || !$end){
			return false;
		}
		
		$now = (new DateTime('NOW'))->format(DateTime::ATOM);
		Debug::Show($now);
		return $start < $now && $now < $end;
	}
	
	
	public function Link(){
		return $this->EventContainerPage()->Link('show/' . $this->ID);
	}
}