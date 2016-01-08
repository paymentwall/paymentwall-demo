<?php
    require_once('utils/bootstrap.php');

    Paymentwall_Config::getInstance()->set(array(
	    'private_key' => 't_6618df4820214dd250187c9ecd6996'
	));

	$parameters = $_POST;
	$cardInfo = array(
	    'email' => $parameters['email'],
	    'amount' => 9.99,
	    'currency' => 'USD',
	    'token' => $parameters['brick_token'],
	    'fingerprint' => $parameters['brick_fingerprint'],
	    'description' => 'Order #123'
	);

	$charge = new Paymentwall_Charge();
	$charge->create($cardInfo);
	$response = $charge->getPublicData();

	if ($charge->isSuccessful()) {
	    if ($charge->isCaptured()) {
	        // deliver a product
	    } elseif ($charge->isUnderReview()) {
	        // decide on risk charge
	    }
	} else {
	    $errors = json_decode($response, true);
	}

	echo $response;
