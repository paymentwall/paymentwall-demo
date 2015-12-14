<?php
session_start();

include('provider.php');

if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {
    $code = $_GET['code'];
    try {
        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code'              => $code,
            'resource_owner_id' => '25bdb52fc296eb08162e65aefcec0cac',
            'client_secret'     => '66393550cf90a40dec45aae7ff7a4dc1',
            'redirect_uri'      => $_SERVER['HTTP_HOST'] . '/access.php'
        ]);

        $_SESSION['token'] = $accessToken->getToken();
        $_SESSION['code']  = $_GET['code'];

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        header('Location: ' . $_SERVER['HTTP_HOST'] . '/index.php');
        exit;

    }

}


$client = new GuzzleHttp\Client();

$res = $client->get('https://api.paymentwall.com/pwapi/merchant/application', [
    'query' => ['access_token' => $accessToken->getToken(), 'version' => '1']
]);

$body = json_decode($res->getBody());

?>
<html>
<head>
    <title>My Access</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/access.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>

<body>
<h4>Create a project:</h4>
<form method="POST" action="createProject.php">
    <label for="name">Name: </label>
    <input type="text" name="name">
    <label for="url">URL: </label>
    <input type="text" name="url">
    <label for="pingback_url">Pingback URL: </label>
    <input type="text" name="pingback_url">
    <?php  echo "<input type='hidden' name='state' value='" . $_GET['state'] . "'/>" ; ?>
    <input type="submit" value="Submit">
    
</form>

<?php

echo "<h4>Projects:</h4>";
echo "<table class='table table-responsive table-bordered'>";

    foreach (array_keys((array)$body[0]) as $key) {
        $key = str_replace("_", " ", $key);
        echo "<th>" . $key . "</th>";
    }
    $body = array_values($body);

    for ($i=0; $i < count($body); $i++) {
        $array = array_values((array)$body[$i]);
        echo "<tr>";
        for ($j=0; $j < sizeof($array); $j++) {
            echo "<td>";
            echo $array[$j];
            if ($j == 0) {
                echo '<br>';
                echo '
                <form action="pw/payments.php" method="POST" name="' . time() . '">
                    <input type="hidden" name="id" value="' . $array[1] . '">
                    <input type="hidden" name="public_key" value="' . $array[2] . '">
                    <input type="hidden" name="private_key" value="' . $array[3] . '">
                    <input type="hidden" name="signature_version" value="' . $array[5] . '">
                    <input type="hidden" name="api_type" value="' . $array[7] . '">
                    <input type="submit" value="Test payments">
                </form>';
            }
            echo "</td>";
        }
        echo "</tr>";
    }
echo "</table>";
?>
</body>
</html>