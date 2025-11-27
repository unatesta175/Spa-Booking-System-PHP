<?php

include 'components/connect.php';

// This file handles server-to-server callback from ToyyibPay
// ToyyibPay will POST payment status to this URL

// Get POST data from ToyyibPay
//test data http://localhost/lunaraspa.com/payment-success.php?booking_id=KB00000070&status_id=1&billcode=iuuyzjxq
$refno = isset($_POST['refno']) ? $_POST['refno'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$reason = isset($_POST['reason']) ? $_POST['reason'] : '';
$billcode = isset($_POST['billcode']) ? $_POST['billcode'] : '';
$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';

// Log the callback for debugging (optional)
$log_file = 'payment_logs.txt';
$log_data = date('Y-m-d H:i:s') . " - Status: $status, BillCode: $billcode, OrderID: $order_id, Amount: $amount, Reason: $reason\n";
file_put_contents($log_file, $log_data, FILE_APPEND);

// Update booking payment status based on callback
if ($status == '1' && !empty($order_id)) {
    // Payment successful
    $update_payment = $conn->prepare("UPDATE `bookings` SET pay_stat = ?, updated_at = NOW() WHERE booking_id = ?");
    $update_payment->execute(['Telah Bayar', $order_id]);
    
    echo "OK"; // Acknowledge receipt to ToyyibPay
} elseif ($status == '3') {
    // Payment failed
    $update_payment = $conn->prepare("UPDATE `bookings` SET pay_stat = ?, updated_at = NOW() WHERE booking_id = ?");
    $update_payment->execute(['Gagal', $order_id]);
    
    echo "OK";
} else {
    // Payment pending or other status
    echo "PENDING";
}

exit();


