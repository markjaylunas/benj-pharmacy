<?php

function requestGcash(){


    // GCASH CURL REQUEST
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
    CURLOPT_URL               => 'https://g.payx.ph/payment_request',
    CURLOPT_RETURNTRANSFER    => true,
    CURLOPT_ENCODING          => '',
    CURLOPT_MAXREDIRS         => 10,
    CURLOPT_TIMEOUT           => 0,
    CURLOPT_FOLLOWLOCATION    => true,
    CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST     => 'POST',
    CURLOPT_POSTFIELDS        => array(
                    'x-public-key'  => 'pk_e9b2875d39ddd3310736f517adabd7a2',
                    'amount'        => $_SESSION['cart_total'],
                    'description'   => 'Payment for Benj Pharmacy products',
                    // 'fee'           => 1,
    
                    'expiry'        => '1',
                    'customername'  => ucfirst($_SESSION['fname']).' '.ucfirst($_SESSION['lname']),
                    // 'customermobile'  => '9123456789',
                    'customeremail'  =>  $_SESSION['email'],
                    'merchantlogourl'  => 'https://benjpharmacy.online/logo.svg',
                    'webhooksuccessurl'  => 'https://benjpharmacy.online/gcash-success',
                    // 'webhookfailurl'  => '',
                ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
    return '';
}

$gcash_response = requestGcash();
$gcash_response = json_decode($gcash_response, true);
$_SESSION['gcash_response'] = $gcash_response;

if($gcash_response['success']!==1){
    header("Refresh:0");
    exit();
}
?>