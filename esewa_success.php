<?php
// esewa_success.php

// Verify the transaction with eSewa
$refId = $_GET['refId'];
$pid = $_SESSION['transaction_uuid']; // The transaction UUID you stored

// Verification request to eSewa API
$verification_url = "https://esewa.com.np/epay/transrec";
$data = [
    'amt' => $total_amount,
    'rid' => $refId,
    'pid' => $pid,
    'scd' => 'EPAYTEST'
];

// Use cURL to verify the payment
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verification_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if (strpos($response, "Success") !== false) {
    // Payment successful, clear the cart
    unset($_SESSION['cart_item']);
    echo "Payment successful!";
} else {
    echo "Payment verification failed!";
}
?>
