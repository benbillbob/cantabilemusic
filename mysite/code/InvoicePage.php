<?php
class InvoicePage extends Page {
	private static $db = array ('UseMiniCart' => 'Boolean');
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Main', CheckboxField::create('UseMiniCart'));
		return $fields;
	}
}

class InvoicePage_Controller extends Page_Controller 
{
	public function init() 
	{
		Requirements::set_force_js_to_bottom(true);
		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
		if ($this->UseMiniCart){
			$settings = MiniCart::getMiniCartConfig();
			Requirements::customScript('paypal.minicart.render(' . $settings . ');', 'minicart');
		}
		
		//if (Permission::check('ADMIN')){
		//	Requirements::javascript("mysite/code/CreateInvoiceAdmin.js");		
		//}
		//else{
			Requirements::javascript("mysite/code/CreateInvoice.js");
		//}
		
		parent::init();
	}
	
	private static $allowed_actions = array(
        'createInvoice',
        'info'
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
		
		if (Permission::check('ADMIN')){
			$error = $invoice->processPurchase();
		
			if ($error){
				$this->Content = $error;
				return $error;
			}
		
			$this->getResponse()->setBody(EventTicket::get()->byID($invoice->EventTicketID)->Link());
		}
		else{
			$this->getResponse()->setBody($id);
		}

		return $this->getResponse();
    }
	
	public function info(SS_HTTPRequest $request) {
		if($request->param('OtherID') == 'createInvoice'){
			return $this->createInvoice($request);
		}
		
		return $this;
    }
	
	private function createTxnId()
	{
		return DB::query('SELECT uuid()')->value();
	}
}

