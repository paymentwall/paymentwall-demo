<?php

require('oauth2-client/vendor/autoload.php');

if(!$_SESSION) session_start();
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '25bdb52fc296eb08162e65aefcec0cac',    // The client ID assigned to you by the Provider
    'redirectUri'             => 'http://localhost:8080/oauth.php',
    'urlAuthorize'            => 'https://api.paymentwall.com/pwaccount/oauth/authorize',
    'urlAccessToken'          => 'https://api.paymentwall.com/pwaccount/oauth/token',
    'urlResourceOwnerDetails' => 'https://api.paymentwall.com/pwapi/merchant/application/',
    'scopes'                  => 'default,pwaccount.email.get,merchant.application.get,merchant.application.update,merchant.application.create'
]);


if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {
    try {
        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code'              => $_GET['code'],
            'resource_owner_id' => '25bdb52fc296eb08162e65aefcec0cac',
            'client_secret'     => '66393550cf90a40dec45aae7ff7a4dc1',
            'redirect_uri'      => 'http://localhost:8080/oauth.php'
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.

        $client = new GuzzleHttp\Client();

        $res = $client->get('https://api.paymentwall.com/pwapi/merchant/application', [
        	'query' => ['access_token' => $accessToken->getToken(), 'version' => '1']
        ]);

		var_dump((string)$res->getBody());



        // echo $accessToken->getRefreshToken() . "\n";
        // echo $accessToken->getExpires() . "\n";
        // echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

//      id 127947
  //       $params = http_build_query(
		// 	array(
		// 		'access_token' => $accessToken->getToken(),
		// 		'version' => '1'
		// 	    //,'name'    => 'testetest',
		// 		// 'url'     => 'http://www.eita.com.br',
		// 		// 'pingback_url' => 'http://www.eita.com.br/pingback.php'
		// 	));

		// 	var_dump($params);

		// $url = 'https://api.paymentwall.com/pwapi/merchant/application?' . $params;

		// var_dump($url);

		// $ch = curl_init($url);
		// // curl_setopt($ch, CURLOPT_MUTE, 1);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// curl_setopt($ch, CURLOPT_POST, 0);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// // var_dump(curl_getinfo($ch));
		// $output = curl_exec($ch);
		// curl_close($ch);
		// $jsonData = json_decode($output,true);
		// var_dump($output);
		// var_dump($jsonData);


		echo '</pre>';

		// echo $res->getStatusCode();
		// // 200
		// echo $res->getHeader('content-type');
		// // 'application/json; charset=utf8'
		// echo $res->getBody();


    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}