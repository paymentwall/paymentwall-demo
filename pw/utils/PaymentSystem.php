<?php

class PaymentSystem {
	public function getPaymentSystemsFor($country)
	{
		$parameters = array(
			'country_code' => $country['code'],
			'key' => $_SESSION['publicKey'],
			'sign_version' => 2
		);

		$parameters['sign'] = (new Paymentwall_Signature_Widget())->calculate(
		    $parameters,
		    2
		);

		$url = 'https://api.paymentwall.com/api/payment-systems/?' . http_build_query($parameters);

		// Mock list
		// $result = file_get_contents(dirname(__FILE__).'/mock');

		$result = @file_get_contents($url);

		if (!$result) throw new Exception("Error!");
		
		$paymentSystems = json_decode($result, true);

		return $paymentSystems;
	}
}