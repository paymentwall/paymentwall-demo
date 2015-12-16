<?php

require_once('../vendor/autoload.php');
require_once('GeoLocator.php');
require_once('PaymentSystem.php');

define("PUBLIC_KEY", "e14323f11ea9326b5b38b9f6ce999931");
define("PRIVATE_KEY", "8caa51ff0af65e89c0c48b8bc33a1260");
define("SIGNATURE_VERSION", 2);
define("API_TYPE", 2);
define("USER_ID", 'user@example.com');

Paymentwall_Config::getInstance()->set(array(
    'api_type' => API_TYPE,
    'public_key' => PUBLIC_KEY,
    'private_key' => PRIVATE_KEY
));