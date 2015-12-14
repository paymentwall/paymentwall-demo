<?php

class PaymentSystem {
	public function getPaymentSystemsFor($country)
	{
		$parameters = array(
			'country_code' => $country['code'],
			'key' => PUBLIC_KEY,
			'sign_version' => SIGNATURE_VERSION
		);

		$parameters['sign'] = (new Paymentwall_Signature_Widget())->calculate(
		    $parameters,
		    SIGNATURE_VERSION
		);

		$url = 'https://api.paymentwall.com/api/payment-systems/?' . http_build_query($parameters);

		// Mock list
		// $result = file_get_contents(dirname(__FILE__).'/mock');

		$result = file_get_contents($url);
		
		$paymentSystems = json_decode($result, true);

		return $paymentSystems;
	}
}