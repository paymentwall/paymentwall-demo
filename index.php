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
	<head>
		<link rel="stylesheet" type="text/css" href="assets/index.css">
	</head>

	<body>
	<a href="<?php echo $authorizationUrl; ?>" class="btn"><button>Login with PW Account</button></a>

	<div class="container"><form id="sign_in_form" class="form" role="form" method="post" novalidate="novalidate"><div class="logo_container"></div><h2 class="form-heading">PW Account</h2><div id="sign_in_erorrs" class="errors_block"></div><input type="email" id="email" name="email" class="form-control tooltipstered" placeholder="Email"><input type="password" id="password" name="password" class="form-control tooltipstered" placeholder="Password"><div id="ck-button" class="signin_show"><label><input type="checkbox" value="1" id="myonoff" name="onoff" class="tooltipstered"><span class="onoff_text">Show</span></label></div><a href="https://api.paymentwall.com/pwaccount/forgot-password" class="forgot_pass">Forgot password?</a><input class="btn btn-lg btn-primary btn-block tooltipstered" type="submit" id="sign_in" value="Sign In"><div id="remember_user">Remember me</div><div class="onoffswitch remember_switch"><input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox tooltipstered" id="myonoffswitch"><label class="onoffswitch-label" for="myonoffswitch"></label></div><div class="bottom_text signin_bottom">Don't have an account? <a href="https://api.paymentwall.com/pwaccount/signup">Sign Up</a></div><input type="hidden" name="url" value="" class="tooltipstered"></form><div class="container_secure"><div class="secure_area">Secure Area</div></div></div>
	
	</body>
</html>