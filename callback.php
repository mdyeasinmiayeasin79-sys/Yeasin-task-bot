<?php
session_start();
require_once 'config.php';

$paymentID = isset($_GET['paymentID']) ? $_GET['paymentID'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;
$token = isset($_SESSION['bkash_token']) ? $_SESSION['bkash_token'] : null;

if ($status == 'success' && $paymentID && $token) {
    // টাকা কাটা চূড়ান্ত করা
    $url = BKASH_BASE_URL . '/checkout/execute';
    $post_data = array('paymentID' => $paymentID);

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

    if (isset($response['transactionStatus']) && $response['transactionStatus'] == 'Completed') {
        echo "<div style='text-align:center; padding:50px;'>";
        echo "<h2 style='color:green;'>আপনার পেমেন্ট সফল হয়েছে!</h2>";
        echo "<p>ট্রানজেকশন আইডি: <b>" . $response['trxID'] . "</b></p>";
        echo "<p>টাকার পরিমাণ: <b>" . $response['amount'] . " BDT</b></p>";
        echo "</div>";
    } else {
        echo "<h2 style='color:red; text-align:center;'>পেমেন্ট ব্যর্থ হয়েছে!</h2>";
    }
} else {
    echo "<h2 style='color:red; text-align:center;'>পেমেন্ট বাতিল করা হয়েছে!</h2>";
}
?>
