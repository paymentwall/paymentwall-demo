<?php
session_start();

include('provider.php');


try {

    $client = new GuzzleHttp\Client();

    $res = $client->post('https://api.paymentwall.com/pwapi/merchant/application/', [
    	'form_params' => ['access_token' => $_SESSION['token'], 'version' => 1, 
    		'name' => $_POST['name'], 'url' => $_POST['url'], 'pingback_url' => $_POST['pingback_url']]
    ]);

    header('Location: localhost:8080/access.php?state=' . $_POST['state'] . '&status=success');
    exit;

} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

    // Failed to get the access token or user details.
    exit($e->getMessage());
}