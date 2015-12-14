<?php
    session_start();
    header('Content-type: application/json');
    require_once('utils/bootstrap.php');
    require_once('../oauth2-client/vendor/autoload.php');

    $client = new GuzzleHttp\Client();

    $res = $client->get('https://api.paymentwall.com/pwapi/merchant/application/' . $_SESSION['projectId'], [
        'query' => ['access_token' => $_SESSION['token'], 'version' => '1']
    ]);

    $body = json_decode($res->getBody());

    if (preg_match('/^Digital/', $body->api_type)) {
        $api_type = 2;
    } elseif (preg_match('/^Virtual/', $body->api_type)) {
        $api_type = 1;
    } else {
        $api_type = 3;
    }
    

    Paymentwall_Config::getInstance()->set(array(
        'api_type' => $api_type,
        'public_key' => $body->key,
        'private_key' => $body->secret
    ));

    $response_array['status'] = 'success'; 
    $response_array['test'] = $body;

    $widget = new Paymentwall_Widget(
        'user@example.com',
        'p2',
        array(
            new Paymentwall_Product(
                'product301',                           
                9.99,                                   
                'USD',                                  
                '1 month membership',                      
                Paymentwall_Product::TYPE_FIXED
            )
        ),
        array('ps' => $_POST['option'], 'email' => 'useeeer@example.com', 'any_custom_parameter' => 'value', 'sign_version' => $body->signature_version)
    );
    $response_array['widget'] = $widget->getHtmlCode();

    echo json_encode($response_array);
?>