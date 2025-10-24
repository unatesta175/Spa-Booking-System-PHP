<?php
include 'components/connect.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $billcode = $_POST['billcode'];
    $status = $_POST['status'];

    if ($status == '1') { // Payment success
        $stmt = $conn->prepare("UPDATE bookings SET pay_stat = 'Paid', bookingstat = 'Confirmed' WHERE billcode = ?");
        $stmt->execute([$billcode]);

        echo 'Payment confirmed';
    } else {
        echo 'Payment failed or pending';
    }
}
?>
