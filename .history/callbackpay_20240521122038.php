<?php
include 'components/connect.php';

?>


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status_id = $_POST['status_id'];
    $billcode = $_POST['billcode'];
    $booking_id = $_POST['order_id']; // Ensure this is passed correctly from ToyyibPay

    if ($status_id == 1) {
        // Payment successful
        $update_payment_status = $conn->prepare("UPDATE `bookings` SET pay_stat = 'Telah Bayar', bookingstat = 'Confirmed' WHERE booking_id = ?");
        $update_payment_status->execute([$booking_id]);

        // Redirect to a success page or perform additional actions
        header('Location: payment_success.php');
    } else {
        // Payment failed or other status
        // Redirect to a failure page or perform additional actions
        header('Location: payment_failure.php');
    }
    exit();
}