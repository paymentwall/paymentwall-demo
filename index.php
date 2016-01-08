<?php
session_start();
unset($_SESSION['token']);

$_SESSION['publicKey'] = YOUR_PUBLIC_KEY;
$_SESSION['secretKey'] = YOUR_SECRET_KEY;
$_SESSION['apiType'] = 2;
$_SESSION['signatureVersion'] = 2;

require_once('pw/utils/bootstrap.php');
include('provider.php');

// Fetch the authorization URL from the provider; this returns the
// urlAuthorize option and generates and applies any necessary parameters
// (e.g. state).
$authorizationUrl = $provider->getAuthorizationUrl();

// Get the state generated for you and store it to the session.
$_SESSION['oauth2state'] = urlencode($provider->getState());

if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipAddresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip = trim(end($ipAddresses));
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$locator = new GeoLocator;
$country = $locator->locate($ip);

/* 
	Pulls list of payment system options available for this country via Payment Systems API
*/

try {

	$paymentSystems = new PaymentSystem;
	$list = $paymentSystems->getPaymentSystemsFor($country);
	
} catch (Exception $e) {

	exit($e->getMessage());		
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
				<a href="<?php echo $authorizationUrl; ?>">
					<button id="yellow" type="button" class="btn btn-default btn-lg">
					    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Want your own store?
					</button>
				</a>
			</div>

			<img src="assets/img/pw.png" class="logo">
		
			<div class="paymentMethods">
				<h2>Amount: 9.99 USD</h2>
				<div>
					<h1>Payment methods:</h1>
				</div>
				<div class="blue">
					<?php foreach ($list as $value): ?>
						<div class="option">
							<div>
								<img src="<?php echo $value['img_url'] ?>">								
							</div>
							<p><?php echo $value['name'] ; ?></p>
							<input type="radio" name="radio" value="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">					
						</div>			
					<?php endforeach; ?>
				</div>				
			</div>
			<div id="loading" class="loading">
			<img src="../assets/img/loading.gif">
			</div>
			<div id="iframe">

			</div>

		</div>
	
	</body>
</html>