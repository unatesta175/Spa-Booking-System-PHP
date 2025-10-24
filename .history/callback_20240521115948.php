<?php
include 'components/connect.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $billcode = $_POST['billcode'];
    $status = $_POST['status'];

    if ($status == '1') { // Payment success
        $stmt = $conn->prepare("UPDATE bookings SET pay_stat = 'Paid', bookingstat = 'Confirmed' WHERE billcode = ?");
        $stmt->execute([$billcode]);

        echo 'Pembayaran berjaya dibuat. Tempahan anda akan diproses dalam tempoh 1-2 hari bekerja. Terima Kasih.';
    } else {
        echo 'Payment failed or pending';
    }
}
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