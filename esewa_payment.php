<?php
session_start();
if (empty($_SESSION['esewa_transaction'])) {
    header('location: checkout.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>eSewa Payment</title>
</head>
<body>
<form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
 <input type="hidden" id="amount" name="amount" value="100" required>
 <input type="hidden" id="tax_amount" name="tax_amount" value ="10" required>
 <input type="hidden" id="total_amount" name="total_amount" value="110" required>
 <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="test" required >
 <input type="hidden" id="product_code" name="product_code" value ="EPAYTEST" required>
 <input type="hidden" id="product_service_charge" name="product_service_charge" value="0" required>
 <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
 <input type="hidden" id="success_url" name="success_url" value="https://esewa.com.np" required>
 <input type="hidden" id="failure_url" name="failure_url" value="https://google.com" required>
 <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>
 <input type="hidden" id="signature" name="signature" value="4Ov7pCI1zIOdwtV2BRMUNjz1upIlT/COTxfLhWvVurE=" required>
 <input value="Submit" type="submit">
 </form>
    <!-- <form action="https://uat.esewa.com.np/epay/main" method="POST">
        <input type="hidden" name="tAmt" value="<?php
            //  echo $_SESSION['item_total']; 
             ?>">
        <input type="hidden" name="amt" value="<?php
        //  echo $_SESSION['item_total'];
          ?>">
        <input type="hidden" name="txAmt" value="0">
        <input type="hidden" name="psc" value="0">
        <input type="hidden" name="pdc" value="0">
        <input type="hidden" name="scd" value="EPAYTEST">
        <input type="hidden" name="pid" value="<?php
        //  echo $_SESSION['esewa_transaction'];
          ?>">
        <input type="hidden" name="su" value="http://yourdomain.com/esewa_success.php?q=su">
        <input type="hidden" name="fu" value="http://yourdomain.com/esewa_failure.php?q=fu">
        <input type="submit" value="Proceed to Payment">
    </form> -->
</body>
</html>
