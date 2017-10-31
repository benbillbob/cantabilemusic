<?php
class PayPalReturnPage extends Page {
}

include('ipn.php');
include('PayPalPDT.php');

class PayPalReturnPage_Controller extends Page_Controller 
{
	public function init() 
	{
		Requirements::customScript('paypal.minicart.reset();', 'minicart_reset');
		parent::init();
	}
	
	private static $allowed_actions = array(
        'ipnReturn',
		'pdtReturn',
		'pdtCancel'
	);
	
	public function pdtReturn(SS_HTTPRequest $request)
	{
		$sandbox = (bool)SiteConfig::current_site_config()->MiniCartTestMode;
		$invoice = $this->getInvoiceFromPDT($sandbox);

		if (!is_a($invoice, Invoice::class))
		{
			return $invoice; // error message
		}
		
		$error = $invoice->processPurchase();
		
		if ($error){
			$this->Content = $error;
			return $error;
		}
		
		$this->Content = $invoice->renderWith('ProcessedInvoice');
		return $this->renderWith(Page::class);
	}
	
	public function pdtCancel(SS_HTTPRequest $request)
	{
		$this->Content = 'Transaction cancelled';
		return $this->renderWith(Page::class);
	}
	
	public function ipnReturn(SS_HTTPRequest $request) {
		$sandbox = (bool)SiteConfig::current_site_config()->MiniCartTestMode;
		$ipn = new Ipn();
		
		$valid = $ipn->processIPN($sandbox);

		if (!$valid)
		{
			return 'Invalid IPN';
		}

        $response = $_POST;
		
		$paypalPDT = new PayPalPDT();

		$invoice = $paypalPDT->getInvoiceFromValues($response);
		if (!$invoice)
		{
			return $paypalPDT->getError();
		}
		
		$invoice->processPurchase();
	}

	private function getInvoiceFromPDT($sandbox)
	{
		$tx = $this->getRequest()->getVar('tx');
		
		$paypalPDT = new PayPalPDT();

		$invoice = $paypalPDT->getInvoiceForTx($tx);
		if (!$invoice)
		{
			$ipn = new Ipn();

			$response = $paypalPDT->getResponseFromPayPal($ipn, $tx, $sandbox);
			if (!$response)
			{
				return $paypalPDT->getError();
			}
			

			$invoice = $paypalPDT->getInvoiceFromResponse($response, $tx);
			if ($invoice)
			{
				$invoice->Logs = $response;
				$invoice->write();
			}
		}
		

		if (!$invoice)
		{
			return $paypalPDT->getError();
		}
		
		return $invoice;
	}
}

