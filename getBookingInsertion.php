<?php
session_start(); 
include 'components/connect.php';
require_once 'components/env_loader.php';


$client = $_POST['client'];
$date = $_POST['date'];
$staff = $_POST['staff'];
$service = $_POST['service'];
// $duration = $_POST['duration'];
$timeslot = $_POST['timeslot'];
$starttime = $_POST['starttime'];
$endtime = $_POST['endtime'];

$result = $conn->prepare("SELECT * FROM `services` Where id =?");
$result->execute([$service]);
if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                $duration = $row['duration']+30;
                $pay_amount = $row['price'];
        }
}

$r1 = $conn->prepare("SELECT * FROM `clients` WHERE id = ?");
$r1->execute([$client]);
if ($r1->rowCount() > 0) {
        while ($rr1 = $r1->fetch(PDO::FETCH_ASSOC)) {


                $phoneno = $rr1['phoneno'];
                $email = $rr1['email'];
                $name = $rr1['name'];
        }
}

date_default_timezone_set('Asia/Kuala_Lumpur');
$currentDateTime = date('Y-m-d H:i:s');
$defaultDateTime = "0000-00-00 00:00:00";
$get_latest_id = $conn->query("SELECT id FROM bookings ORDER BY id DESC LIMIT 1");
$latest_id_row = $get_latest_id->fetch(PDO::FETCH_ASSOC);
$latest_id = $latest_id_row['id']+1;
$formatted_id = 'KB' . str_pad($latest_id, 8, '0', STR_PAD_LEFT);


$stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? AND timeslot = ? ");
$stmt->execute([$date, $timeslot]);
$existing_bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_bookings[] = $row;
}


$booking_allowed = true;
$booked_staff_ids = [];
foreach ($existing_bookings as $booking) {
        $booked_staff_ids[] = $booking['staff_id'];
        if ($booking['staff_id'] == $staff) {
                // User already booked the same timeslot with the same staff
                $booking_allowed = false;
                break;
        }
}

if (!$booking_allowed || count(array_unique($booked_staff_ids)) < count($existing_bookings)) {
        $msg = "<div class='alert alert-danger'>Anda tidak boleh menempah slot masa ini dengan ahli terapi yang sama /Sudah ada tempahan bertindih untuk ahli terapi yang dipilih!</div>";
} else {
        // Check if the user is already registered
    $stmt = $conn->prepare("INSERT INTO `bookings` ( booking_id, timeslot, date, starttime, endtime, duration, datetimeapplied, updated_at, claimstat, bookingstat, pay_amount, pay_stat, client_id, service_id, staff_id) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$formatted_id, $timeslot, $date, $starttime, $endtime, $duration, $currentDateTime, $defaultDateTime, "Pending", "Ditempah", $pay_amount, "Belum Bayar", $client, $service, $staff]);


        $query = $conn->query("SELECT MAX(booking_id) FROM bookings");
        $last_inserted_id = $query->fetchColumn();

        $bruh = (1 * 100);
        $rmx100 = ($pay_amount * 100);
        $some_data = array(
                'userSecretKey' => env('TOYYIBPAY_SECRET_KEY'),
                'categoryCode' => env('TOYYIBPAY_CATEGORY_CODE'),
                'billName' => $last_inserted_id,
                'billDescription' => $name,
                'billPriceSetting' => 1,
                'billPayorInfo' => 1,
                // 'billAmount' => $rmx100,
                'billAmount' =>  $bruh,
                'billReturnUrl' => 'index.php',
                'billCallbackUrl' => '',
                'billExternalReferenceNo' => '',
                'billTo' => $name,
                'billEmail' => $email,
                'billPhone' => $phoneno,
                'billSplitPayment' => 0,
                'billSplitPaymentArgs' => '',
                'billPaymentChannel' => 0,
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $obj = json_decode($result, true);
        $billcode = $obj[0]['BillCode'];

        $update_admin_pass = $conn->prepare("UPDATE `bookings` SET billcode = ? WHERE booking_id = ?");
        $update_admin_pass->execute([$billcode, $last_inserted_id]);

        // Push successful booking to the $bookings array
        $bookings[] = $timeslot;
        $_SESSION['book_success'] = true; // Set login success flag
        $_SESSION['redirect_record'] = true; // Set login success flag
        
        // Set success message
        // $msg = "<div class='alert alert-success'>Tempahan anda berjaya!</div>";

      
}
