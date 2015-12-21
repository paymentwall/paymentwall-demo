<?php
session_start();


if(sizeof($_POST) > 0) {
	include('provider.php');

	try {

	    $client = new GuzzleHttp\Client();

	    $res = $client->post('https://api.paymentwall.com/pwapi/merchant/application/', [
	    	'form_params' => ['access_token' => $_SESSION['token'], 'version' => 1, 
	    		'name' => $_POST['name'], 'url' => $_POST['url'], 'pingback_url' => $_POST['pingback']]
	    ]);

	    header("Refresh: 2; url=store.php" . '?state=' . $_POST['state'] . '&code=' . $_SESSION['code']);
	 
		echo 'New Project Created!!';

	} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

	    // Failed to get the access token or user details.
	    exit($e->getMessage());
	}	
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="assets/index.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
	<div style="max-width: 500px;">
		<form action="/createProject.php" method="POST">
		  <div class="form-group">
		    <label for="name">Name</label>
		    <input type="text" class="form-control" id="name" placeholder="Name:" name="name">
		  </div>
		  <div class="form-group">
		    <label for="url">URL</label>
		    <input type="text" class="form-control" id="url" placeholder="URL:" name="url">
		  </div>
		  <div class="form-group">
		    <label for="pingback">Pingback URL</label>
		    <input type="text" class="form-control" id="url" placeholder="Pingback URL:" name="pingback">
		  </div>
		  <button type="submit" class="btn btn-default">Submit</button>
		</form>		
	</div>
</body>
</html>