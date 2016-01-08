<?php
session_start();

require_once('pw/utils/bootstrap.php');
include('provider.php');

$code = $_GET['code'];

if(!isset($_SESSION['token'])) {
    try {
        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code'              => $code,
            'resource_owner_id' => YOUR_PUBLIC_KEY,
            'client_secret'     => YOUR_PRIVATE_KEY,
            'redirect_uri'      => 'http://' . $_SERVER['HTTP_HOST'] . '/store.php'
        ]);

        $_SESSION['token'] = $accessToken->getToken();
        $_SESSION['code']  = $code;

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());
    }    
}

$client = new GuzzleHttp\Client();

$res = $client->get('https://api.paymentwall.com/pwapi/merchant/application', [
    'query' => ['access_token' => $_SESSION['token'], 'version' => '1']
]);

$body = json_decode($res->getBody());

if (isset($_GET['id'])) {
	$_SESSION['projectId'] = $_GET['id'];
	$res = $client->get('https://api.paymentwall.com/pwapi/merchant/application/' . $_GET['id'], [
	    'query' => ['access_token' => $_SESSION['token'], 'version' => '1']
	]);

	$store = json_decode($res->getBody());

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ipAddresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	    $ip = trim(end($ipAddresses));
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}

	$parameters = array(
		'key' => $store->key,
		'uid' => "test@example.com",
		'user_ip' => $ip
	);

	$url = 'https://api.paymentwall.com/api/rest/country?' . http_build_query($parameters);

	$result = file_get_contents($url);
	$country = json_decode($result, true);

	Paymentwall_Config::getInstance()->set(array(
	    'api_type' => $store->api_type,
	    'public_key' => $store->key,
	    'private_key' => $store->secret
	));

	$parameters = array(
		'country_code' => $country['code'],
		'key' => $store->key,
		'sign_version' => 2
	);

	$parameters['sign'] = (new Paymentwall_Signature_Widget())->calculate(
	    $parameters,
	    2
	);

	$url = 'https://api.paymentwall.com/api/payment-systems/?' . http_build_query($parameters);

	$result = @file_get_contents($url);

	if (!$result) throw new Exception("Error!");
	
	$paymentSystems = json_decode($result, true);

}
?>

<html>
	<head>
		<meta charset="utf-8">
	    <title>Paymentwall Demo Page</title>
		<link rel="stylesheet" type="text/css" href="assets/style/index.css">
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	    <script src="https://api.paymentwall.com/brick/brick.1.4.js"> </script>
		<script src="assets/js/index.js"></script>
	</head>

	<body>
	<div class="wrapper">
			<div class="middle">
				<a href="/createProject.php">
					<button id="yellow" type="button" class="btn btn-default btn-lg">
					    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Want another store?
					</button>
				</a>
			</div>

			<img src="assets/img/pw.png" class="logo">

			<div class="container">
			  <form role="form" action="/store.php" method="GET">
			    <div class="form-group">
			      <label for="sel1">Select store (select one):</label>
			      <select class="form-control input-medium" id="sel1" style="width: 60%" onchange="sendId(this)">
		      		<?php foreach ($body as $store): ?>
						<option value="<?php echo $store->id; ?>"><?php echo $store->name; ?></option>
					<?php endforeach; ?>
			      </select>
			    </div>
			    <input type="hidden" id="hidden" value="" name="id">
			    <input type="submit" value="Submit" class="btn btn-default">
			  </form>
			</div>
		
			<div class="paymentMethods">
			<h1><?php echo $store->name ?> store</h1>
				<h2>Amount: 9.99 USD</h2>
				<?php if (isset($_GET['id'])): ?> 
					<div>
						<h3>Payment methods:</h3>
					</div>
					<div class="blue">
						<?php foreach ($paymentSystems as $value): ?>
							<div class="option">
								<div>
									<img src="<?php echo $value['img_url'] ?>">								
								</div>
								<p><?php echo $value['name'] ; ?></p>
								<input type="radio" name="radio" value="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">					
							</div>			
						<?php endforeach; ?>
					</div>
				<?php endif; ?>			
			</div>
			<div id="loading" class="loading">
			<img src="../assets/img/loading.gif">
			</div>
			<div id="iframe">

			</div>

		</div>
	
	</body>
</html>