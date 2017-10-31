<?php

class PayPalItem {
	public $itemName;
	public $itemNumber;
	public $itemQuantity;
	public $itemAmount;
}

class PayPalPDT {
	
	private $tx;
	private $ipn;
	private $invoice;
	private $error;

	public function getError()
	{
		return $this->error;
	}
	
	public function getInvoiceForTx($tx)
	{
		if (!isset($tx))
		{
			$this->error = "No transaction id provided. Please contact support.";
			return null;
		}
		
		$invoice = Invoice::get()->filter('PayPalTx', $tx)->first();
		if (!$invoice)
		{
			return null;
		}

		if ($invoice->Status == Invoice::STATUS_COMPLETE || $invoice->Status == Invoice::STATUS_FAILED)
		{
			return $invoice;
		}
		else if ($invoice->Status == Invoice::STATUS_PROCESSING)
		{
			$this->error = 'Already processing';
			return null;
		}

		$invoice->Status = Invoice::STATUS_PROCESSING;
		$invoice->write();
		
		return $invoice;
	}

	public function getResponseFromPayPal($ipn, $tx, $sandbox)
	{
		if (!isset($tx))
		{
			$this->error = "No transaction id provided. Please contact support.";
			return null;
		}
		
		$response = $this->getDetailsFromPayPal($ipn, $tx, $sandbox);
		
		if (!$this->validateResponse($response))
		{
			$this->error = 'PayPal comms failure';
			return null;
		}
		
		return $response;
	}
	
	public function getInvoiceFromResponse($response, $tx)
	{
		$values = $this->getValues($response);
		$invoice = $this->getInvoiceFromValues($values, $tx);
		return $invoice;
	}
	
	public function getInvoiceFromValues($values, $tx)
	{
		if ($values['payment_status'] != "Completed")
		{
			$this->error = 'PayPal payment still processing, refresh page';
			return null;
		}

		$txnId = $values['custom'];
		
		if (!$txnId)
		{
			$this->error = 'No TxnId found. Please contact support.';
			return null;
		}
		
		$invoice = Invoice::get()->filter('TxnId', $txnId)->first();
		if (!$invoice)
		{
			$this->error = 'No invoice found. Please contact support.';
			return null;
		}
		if ($invoice->Status == Invoice::STATUS_COMPLETE || $invoice->Status == Invoice::STATUS_FAILED)
		{
			if (isset($tx))
			{
				$invoice->PayPalTx = $tx;
			}
			$invoice->write();		
			return $invoice;
		}
		else if ($invoice->Status == Invoice::STATUS_PROCESSING)
		{
			$this->error = 'Already processing';
			if (isset($tx))
			{
				$invoice->PayPalTx = $tx;
			}
			$invoice->write();		
			return null;
		}

		if (isset($tx))
		{
			$invoice->PayPalTx = $tx;
		}
		
		$invoice->Status = Invoice::STATUS_PROCESSING;
		$invoice->write();		
		
		$items = $this->getItemsFromPayPal($values);
		
		$invoiceLines = $invoice->InvoiceLines();
		
		if (count($items) != $invoiceLines->count())
		{
			$this->error = 'Txn Details do not match. Please contact support.';
			return null;
		}
		
		$invoiceLines->each(function($invoiceLine) use ($items)
		{
			$item = $this->objArraySearch($items, 'itemNumber', $invoiceLine->Item()->ItemNumber);
			if (!$item)
			{
				$this->error = 'Txn Details do not match items. Please contact support.';
				return null;
			}
			
			// TODO - Check line details here
		});
		
		return $invoice;
	}
	
	private function objArraySearch($array, $index, $value)
    {
        foreach($array as $arrayInf) {
            if($arrayInf->{$index} == $value) {
                return $arrayInf;
            }
        }
        return null;
    }

	private function getItemsFromPayPal($values)
	{
		$itemName = 'item_name';
		$itemNumber = 'item_number';
		$itemQuantity = 'quantity';
		$itemAmount = 'mc_gross_';
		
		$items = array();
		if (isset($values[$itemName]))
		{
			$item = new PayPalItem();
			$item->itemName = $values[$itemName];
			$item->itemNumber = $values[$itemNumber];
			$item->itemQuantity = $values[$itemQuantity];
			$item->itemAmount = $values[$itemAmount];
			
			$items[] = $item;
		}
		else
		{
			$i = 1;
			while(isset($values[$itemName.$i]))
			{
				$item = new PayPalItem();
				$item->itemName = $values[$itemName.$i];
				$item->itemNumber = $values[$itemNumber.$i];
				$item->itemQuantity = $values[$itemQuantity.$i];
				$item->itemAmount = $values[$itemAmount.$i];
				
				$items[] = $item;
				$i++;
			}
		}
		
		return $items;
	}

	private function getValues($data)
	{
		$lines = explode("\n", trim($data));
		$keyarray = array();
		for ($i = 1; $i < count($lines); $i++) {
			$temp = explode("=", $lines[$i],2);
			if (count($temp) > 1)
			{
				$keyarray[urldecode($temp[0])] = urldecode($temp[1]);
			}
			else
			{
				$keyarray[urldecode($temp[0])] = '';
			}
		}
		
		return $keyarray;
	}
	
	private function validateResponse($response)
	{
		if (strpos($response, "SUCCESS") !== false) {
            return true;
		}
		
		return false;
	}
	
	private function getDetailsFromPayPal($ipn, $tx, $sandbox)
	{
		$authToken = SiteConfig::current_site_config()->MiniCartPDTAuthCode;
		$response = $ipn->processTx($tx, $authToken, $sandbox);
		return $response;
	}
}
