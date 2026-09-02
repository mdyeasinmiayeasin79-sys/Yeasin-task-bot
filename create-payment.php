<?php
require_once 'config.php';

// ১. বিকাশ থেকে টোকেন নেওয়া
function getBkashToken() {
    $post_token = array(
        'app_key' => BKASH_APP_KEY,
        'app_secret' => BKASH_APP_SECRET
    );

    $url = BKASH_BASE_URL . '/checkout/token/grant';
    $headers = array(
        'Content-Type: application/json',
        'username: ' . BKASH_USERNAME,
        'password: ' . BKASH_PASSWORD
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_token));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    
    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);
    return isset($response['id_token']) ? $response['id_token'] : null;
}

// ২. পেমেন্ট অর্ডার তৈরি করা
$token = getBkashToken();

if ($token) {
    $amount = "100"; 
    $invoice = "INV-" . time(); 

    $post_data = array(
        'mode' => '0011',
        'payerReference' => '01700000000',
        'callbackURL' => CALLBACK_URL,
        'amount' => $amount,
        'currency' => 'BDT',
        'intent' => 'sale',
        'merchantInvoiceNumber' => $invoice
    );

    $url = BKASH_BASE_URL . '/checkout/create';
    $headers = array(
        'Content-Type: application/json',
        'Authorization: ' . $token,
        'x-app-key: ' . BKASH_APP_KEY
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);

    if (isset($response['bkashURL'])) {
        session_start();
        $_SESSION['bkash_token'] = $token;
        header("Location: " . $response['bkashURL']);
        exit();
    } else {
        echo "পেমেন্ট তৈরি করতে সমস্যা হয়েছে।";
    }
} else {
    echo "বিকাশ সার্ভারের সাথে সংযোগ করা যাচ্ছে না।";
}
?>
