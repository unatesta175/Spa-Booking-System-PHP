<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

// Get booking ID from URL
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

// Get payment status from ToyyibPay
$status_id = isset($_GET['status_id']) ? $_GET['status_id'] : '';
$billcode = isset($_GET['billcode']) ? $_GET['billcode'] : '';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

// Status ID meanings:
// 1 = Successful payment
// 2 = Pending payment
// 3 = Failed payment

if ($status_id == '1' && !empty($booking_id)) {
    // Update booking payment status to paid
    $update_payment = $conn->prepare("UPDATE `bookings` SET pay_stat = ?, updated_at = NOW() WHERE booking_id = ?");
    $update_payment->execute(['Telah Bayar', $booking_id]);
    
    $_SESSION['payment_success'] = true;
} elseif ($status_id == '3') {
    $_SESSION['payment_failed'] = true;
} else {
    $_SESSION['payment_pending'] = true;
}

// Redirect to booking record page
header('location: booking-record.php');
exit();

