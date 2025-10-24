<?php
// order_controller.php
require 'database.php';

function paymentRedirect($order) {
    $token = uniqid();

    // Adjust the environment manually
    $isProduction = false; // Set to true in production

    $userSecretKey = $isProduction ? '4ssn0hcg-fioe-j0t7-18zp-1eef11jt6yx6' : '8zx3ye65-6b19-6zff-0i6s-m2e424s33r3q';
    $categoryCode = $isProduction ? 'jsd4hs9j' : 'nb2uirym';

    $billReturnUrl = $isProduction ? 'https://kahwinnow.com/payment-callback' : 'http://127.0.0.1:8000/payment-callback';
    $billCallbackUrl = $isProduction ? 'https://kahwinnow.com/payment-callback' : 'http://127.0.0.1:8000/payment-callback';
    $curlUrl = $isProduction ? 'https://toyyibpay.com/index.php/api/createBill' : 'https://dev.toyyibpay.com/index.php/api/createBill';
    $redirect = $isProduction ? 'https://toyyibpay.com/' : 'https://dev.toyyibpay.com/';

    $paymentData = array(
        'userSecretKey' => $userSecretKey,
        'categoryCode' => $categoryCode,
        'billName' => $order['order_id'],
        'billDescription' => $order['user_name'],
        'billPriceSetting' => 1,
        'billPayorInfo' => 1,
        'billAmount' => $order['price'] * 100,
        'billReturnUrl' => $billReturnUrl,
        'billCallbackUrl' => $billCallbackUrl,
        'billExternalReferenceNo' => $token,
        'billTo' => $order['user_name'],
        'billEmail' => $order['user_email'],
        'billPhone' => $order['contact'],
        'billSplitPayment' => 0,
        'billSplitPaymentArgs' => '',
        'billPaymentChannel' => '0',
        'billContentEmail' => 'Terima kasih kerana membuat tempahan kad kahwin digital bersama KahwinNow!',
        'billChargeToCustomer' => 1,
        'billExpiryDays' => 30
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_URL, $curlUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paymentData);

    $result = curl_exec($curl);
    curl_close($curl);
    $responseData = json_decode($result, true);

    if (isset($responseData[0]["BillCode"])) {
        // Update order billcode
        $stmt = $pdo->prepare("UPDATE orders SET toyyibpay_token = :token, toyyibpay_billcode = :billcode WHERE id = :id");
        $stmt->execute([
            ':token' => $token,
            ':billcode' => $responseData[0]["BillCode"],
            ':id' => $order['id']
        ]);

        // Redirect to records
        header('Location: /rekod');
        exit();
    }
}

function callbackPayCode() {
    global $pdo;

    $status_id = $_GET['status_id'];
    $billcode = $_GET['billcode'];
    $order_id = $_GET['order_id'];

    if ($status_id == 1) {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE toyyibpay_token = :token");
        $stmt->execute([':token' => $order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $stmt = $pdo->prepare("UPDATE orders SET Status = -1, toyyibpay_token = NULL, payment_date = NOW() WHERE id = :id");
            $stmt->execute([':id' => $order['id']]);
            
            sendNotification($order);

            header('Location: /rekod?success-center-2=Pembayaran berjaya dibuat. Tempahan anda akan diproses dalam tempoh 1-2 hari bekerja. Terima Kasih.');
            exit();
        }
    } else {
        header('Location: /rekod');
        exit();
    }
}

function callbackPayCodeBackend() {
    global $pdo;

    $status_id = $_POST['status_id'];
    $billcode = $_POST['billcode'];
    $order_id = $_POST['order_id'];

    if ($status_id == 1) {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE toyyibpay_token = :token");
        $stmt->execute([':token' => $order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $stmt = $pdo->prepare("UPDATE orders SET Status = -1, toyyibpay_token = NULL, payment_date = NOW() WHERE id = :id");
            $stmt->execute([':id' => $order['id']]);

            sendNotification($order);
        }
    }
}

function sendNotification($order) {
    // Implementation for sending notification
    // For simplicity, we assume this is a placeholder function
}
?>