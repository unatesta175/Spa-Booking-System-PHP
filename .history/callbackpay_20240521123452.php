<?php
include 'components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    callbackPayCode();
}

// Execute the callbackPayCodeBackend function for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    callbackPayCodeBackend();
}


function callbackPayCode()
{

    global $conn;
    // Retrieve data from the request
    $status_id = $_GET['status_id'] ?? null;
    $billcode = $_GET['billcode'] ?? null;
    $token = $_GET['order_id'] ?? null;

    // If status is 1, process the payment
    if ($status_id == 1) {
        // Retrieve order details from the database
      $stmt = $conn->prepare("UPDATE bookings SET pay_stat = 'Telah Bayar', bookingstat = 'Confirmed' WHERE billcode = ?");
        $stmt->execute([$billcode]);
        
        // If order is found, update its status and send notification
      
    } else {
        header("Location: booking-record.php");
    }
}

function callbackPayCodeBackend()
{
    // Retrieve data from the request
    $status_id = $_POST['status_id'] ?? null;
    $order_id = $_POST['order_id'] ?? null;

    // If status is 1, process the payment
    if ($status_id == 1) {
        // Retrieve order details from the database
        $order = getOrder($order_id);

        // If order is found, update its status and send notification
        if ($order) {
            updateOrderStatus($order['id']);
            sendNotification($order);
        }
    }
}


?>

