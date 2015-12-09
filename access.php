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
            'redirect_uri'      => 'http://localhost:8080/access.php'
        ]);

        $_SESSION['token'] = $accessToken->getToken();
        $_SESSION['code']  = $_GET['code'];

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        header('Location: localhost:8080/index.php');
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
    <style type="text/css">
        body
        {
            width:80%;
            margin-right:auto;
            margin-left:auto;
        }
        table {
            overflow: hidden;
            table-layout: fixed;
            width: 1200px;
            word-wrap: break-word;
        }
    </style>
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
echo "<table border='1'>";

    foreach (array_keys((array)$body[0]) as $key) {
        echo "<th>" . $key . "</th>";
    }
    $body = array_values($body);

    for ($i=0; $i < count($body); $i++) {
        $array = array_values((array)$body[$i]);
        echo "<tr>";
        for ($j=0; $j < 10; $j++) { 
            echo "<td>";
            echo $array[$j];
            echo "</td>";
        }
        echo "</tr>";
    }
echo "</table>";
?>
</body>
</html>