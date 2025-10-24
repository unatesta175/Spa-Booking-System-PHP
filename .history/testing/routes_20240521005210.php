<?php
// routes.php
require 'order_controller.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/payment-callback') {
    callbackPayCode();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/payment-callback') {
    callbackPayCodeBackend();
} else {
    echo "404 Not Found";
}
?>