<?php
    session_start();
    header('Content-type: application/json');
    require_once('utils/bootstrap.php');
    require_once('../vendor/autoload.php');

    if(isset($_SESSION['projectId'])) {

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
        $signatureVersion = $body->signature_version;

        unset($_SESSION['projectId']);
    } else {
        $api_type = $_SESSION['apiType'];
        $key = $_SESSION['publicKey'];
        $secret = $_SESSION['secretKey'];
        $signatureVersion = $_SESSION['signatureVersion'];
    }
        

    Paymentwall_Config::getInstance()->set(array(
        'api_type' => $api_type,
        'public_key' => $key,
        'private_key' => $secret
    ));


    $response_array['status'] = 'success'; 
    // $response_array['test'] = $body;

    $widget = new Paymentwall_Widget(
        'user@example.com',
        'p1_1',
        array(
            new Paymentwall_Product(
                'product301',                           
                9.99,                                   
                'USD',                                  
                '1 month membership',                      
                Paymentwall_Product::TYPE_FIXED
            )
        ),
        array('ps' => $_POST['option'], 'email' => 'user@example.com', 'any_custom_parameter' => 'value', 'sign_version' => $signatureVersion)
    );
    $response_array['widget'] = $widget->getHtmlCode();

    echo json_encode($response_array);
?>