<?php
// mock_order.php
$order = [
    'id' => 1,
    'order_id' => 'ORD123',
    'user_name' => 'John Doe',
    'user_email' => 'john@example.com',
    'contact' => '1234567890',
    'price' => 100.0
];

paymentRedirect($order);
?>