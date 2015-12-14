<?php
	session_start();
	define("PUBLIC_KEY", $_POST['public_key']);
	define("PRIVATE_KEY", $_POST['private_key']);
	define("SIGNATURE_VERSION", $_POST['signature_version']);
	define("API_TYPE", $_POST['api_type']);
	define("USER_ID", 'user@example.com');

	require_once('utils/bootstrap.php');
	require_once('utils/GeoLocator.php');
	require_once('utils/PaymentSystem.php');

	$_SESSION['projectId'] = $_POST['id'];

	/*
		Locates user via GeoLocation API
	*/

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim(end($ipAddresses));
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

	$ip = $_SERVER['REMOTE_ADDR'];

	$locator = new GeoLocator;
	$country = $locator->locate($ip);

	/* 
		Pulls list of payment system options available for this country via Payment Systems API
	*/

	$paymentSystems = new PaymentSystem;
	$list = $paymentSystems->getPaymentSystemsFor($country);

?>

<html>

	<head>
		<title>Paymentwall</title>
		<link rel="stylesheet" type="text/css" href="public/index.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://api.paymentwall.com/brick/brick.1.4.js"> </script>
		<script src="public/index.js"></script>
	</head>

	<body>
		<div class="wrapper">
			<img src="public/pw.png" class="logo">
		
			<div class="paymentMethods">
				<h2>Amount: 9.99 USD</h2>
				<div>
					<h1>Payment methods:</h1>
				</div>
				<div class="grey">
					<?php foreach ($list as $value): ?>
						<div class="option">
							<img src="<?php echo $value['img_url'] ?>">
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