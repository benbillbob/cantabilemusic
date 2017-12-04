<?php
class PurchasePage extends InvoicePage {
	
	private static $has_many = array(
		'Items' => 'Item'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Main', GridField::create('Items', 'Items', $this->Items(), GridFieldConfig_RecordEditor::create()));
		return $fields;
	}
}

class PurchasePage_Controller extends InvoicePage_Controller 
{
	public function init() 
	{
		parent::init();
	}
	
	private static $allowed_actions = array(
        'createInvoice'
	);
	
	public function createInvoice(SS_HTTPRequest $request) {
		$this->getResponse()->addHeader("Content-type", "text/plain");

		$jsonString = $request->getBody();
		$items = json_decode($jsonString, true);
		
		$id = $this->createTxnId();
		
		$invoice = Invoice::create();
		$invoice->TxnId = $id;
		$invoice->Status = Invoice::STATUS_PENDING;
		$invoice->Processed = false;
		$invoice->write();
		
		foreach ($items as $value){
			$itemNumber = $value['item_number'];
			$item = Item::get()->filter('ItemNumber', $itemNumber)->first();
			
			if (!$item)
			{
				throw new Exception('Item - ' . $itemNumber . ' not found.');
			}
			
			$invoiceLine = InvoiceLine::create();
			$invoiceLine->InvoiceID = $invoice->ID;
			$invoiceLine->ItemID = $item->ID;
			$invoiceLine->Amount = $value['amount'];
			$invoiceLine->Quantity = $value['quantity'];
			$invoiceLine->write();
		}
		
		// if (Permission::check('ADMIN')){
			// $error = $invoice->processPurchase();
		
			// if ($error){
				// $this->Content = $error;
				// return $error;
			// }
		
			// $this->getResponse()->setBody(EventTicket::get()->byID($invoice->EventTicketID)->Link());
		// }
		// else{
			$this->getResponse()->setBody($id);
		// }

		return $this->getResponse();
    }
	
	private function createTxnId()
	{
		return DB::query('SELECT uuid()')->value();
	}
}

