<?php

// Include the PDO connection file
require_once 'path/to/your/connect.php';

function callbackPayCode()
{
    // Retrieve data from the request
    $status_id = $_GET['status_id'] ?? null;
    $order_id = $_GET['order_id'] ?? null;

    // If status is 1, process the payment
    if ($status_id == 1) {
        // Retrieve order details from the database
        $order = getOrder($order_id);

        // If order is found, update its status and send notification
        if ($order) {
            updateOrderStatus($order['id']);
            sendNotification($order);
            redirectTo('/rekod', 'success-center-2', 'Pembayaran berjaya dibuat. Tempahan anda akan diproses dalam tempoh 1-2 hari bekerja. Terima Kasih.');
        } else {
            redirectTo('/rekod');
        }
    } else {
        redirectTo('/rekod');
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

// Function to retrieve order details from the database
function getOrder($order_id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM orders WHERE toyyibpay_token = ?");
    $stmt->execute([$order_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to update order status in the database
function updateOrderStatus($order_id)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE orders SET Status = -1, toyyibpay_token = null, payment_date = NOW() WHERE id = ?");
    $stmt->execute([$order_id]);
}

// Function to send notification
function sendNotification($order)
{
    // Your notification logic goes here
}

// Function to redirect with a message
function redirectTo($url, $key = null, $message = null)
{
    if ($key && $message) {
        $_SESSION[$key] = $message;
    }
    header("Location: $url");
    exit;
}

// Execute the callbackPayCode function for GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    callbackPayCode();
}

// Execute the callbackPayCodeBackend function for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    callbackPayCodeBackend();
}
