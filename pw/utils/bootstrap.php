<?php

require_once('vendor/lib/paymentwall.php');

Paymentwall_Config::getInstance()->set(array(
    'api_type' => API_TYPE,
    'public_key' => PUBLIC_KEY,
    'private_key' => PRIVATE_KEY
));