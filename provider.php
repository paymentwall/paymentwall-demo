<?php

require('vendor/autoload.php');

$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'e14323f11ea9326b5b38b9f6ce999931',    // The client ID assigned to you by the Provider
    'redirectUri'             => 'http://' . $_SERVER['HTTP_HOST'] . '/access.php',
    'urlAuthorize'            => 'https://api.paymentwall.com/pwaccount/oauth/authorize',
    'urlAccessToken'          => 'https://api.paymentwall.com/pwaccount/oauth/token',
    'urlResourceOwnerDetails' => 'https://api.paymentwall.com/pwapi/pwaccount/',
    'scopes'                  => 'default,pwaccount.email.get,merchant.application.get,merchant.application.update,merchant.application.create'
]);