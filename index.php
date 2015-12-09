<?php
session_start();

include('provider.php');

// Fetch the authorization URL from the provider; this returns the
// urlAuthorize option and generates and applies any necessary parameters
// (e.g. state).
$authorizationUrl = $provider->getAuthorizationUrl();

// Get the state generated for you and store it to the session.
$_SESSION['oauth2state'] = $provider->getState();

?>


<html>
	<body>
	<a href="<?php echo $authorizationUrl; ?>" class="btn"><button>Login with PW Account</button></a>
	</body>
</html>