<?php
session_start();

include('provider.php');

// Fetch the authorization URL from the provider; this returns the
// urlAuthorize option and generates and applies any necessary parameters
// (e.g. state).
$authorizationUrl = $provider->getAuthorizationUrl();

// Get the state generated for you and store it to the session.
$_SESSION['oauth2state'] = urlencode($provider->getState());

?>


<html>
	<head>
		<meta charset="utf-8">
	    <title>Login</title>
		<link rel="stylesheet" type="text/css" href="assets/index.css">
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	    <link rel="stylesheet" type="text/css" href="assets/access.css">
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</head>

	<body>
	<div class="middle">
		<a href="<?php echo $authorizationUrl; ?>">
			<button id="yellow" type="button" class="btn btn-default btn-lg">
			    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Login with PW Account
			</button>
		</a>
	</div>
	
	</body>
</html>