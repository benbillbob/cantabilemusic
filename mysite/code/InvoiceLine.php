<?php
class InvoiceLine extends DataObject {
	private static $db = array (
		'Amount' => 'Currency',
		'Quantity' => 'Int'		
	);
	
	private static $has_one = array(
		'Invoice' => 'Invoice',
		'Item' => 'Item'
	);
	
	public function getCMSFields() {
		$fields = FieldList::create(
			CurrencyField::create('Amount')
		);
		
		return $fields;
	}
}
