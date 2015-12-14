<?php

require('vendor/autoload.php');

$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '25bdb52fc296eb08162e65aefcec0cac',    // The client ID assigned to you by the Provider
    'redirectUri'             => $_SERVER['HTTP_HOST'] . '/access.php',
    'urlAuthorize'            => 'https://api.paymentwall.com/pwaccount/oauth/authorize',
    'urlAccessToken'          => 'https://api.paymentwall.com/pwaccount/oauth/token',
    'urlResourceOwnerDetails' => 'https://api.paymentwall.com/pwapi/pwaccount/',
    'scopes'                  => 'default,pwaccount.email.get,merchant.application.get,merchant.application.update,merchant.application.create'
]);