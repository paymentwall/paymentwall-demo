<?php

require_once( __DIR__ . '/../../vendor/autoload.php');
require_once('GeoLocator.php');
require_once('PaymentSystem.php');

Paymentwall_Config::getInstance()->set(array(
    'api_type' => $_SESSION['apiType'],
    'public_key' => $_SESSION['publicKey'],
    'private_key' => $_SESSION['secretKey']
));