<?php

include '../components/connect.php';


$client = $_POST['client'];
$date = $_POST['date'];
$staff = $_POST['staff'];
$service = $_POST['service'];
$duration = $_POST['duration'];
$timeslot = $_POST['timeslot'];
$starttime = $_POST['starttime'];
$endtime = $_POST['endtime'];

$result = $conn->prepare("SELECT * FROM `services` Where id =?");
$result->execute([$service]);
if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {


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

function incrementId($id)
{
        // Extract the numeric part of the ID
        $number = (int) substr($id, 2);

        // Increment the numeric part by 1
        $number++;

        // Format the incremented number with leading zeros and concatenate with the prefix
        return 'KB' . sprintf('%08d', $number);
}

// Connect to your database
// Assuming you have already created a PDO connection object $conn

// Retrieve the latest ID from the database
$get_latest_id = $conn->query("SELECT booking_id FROM bookings ORDER BY id DESC LIMIT 1");
$latest_id_row = $get_latest_id->fetch(PDO::FETCH_ASSOC);

$booking_allowed = true;
   $booked_staff_ids = [];
   foreach ($existing_bookings as $booking) {
      $booked_staff_ids[] = $booking['staff_id'];
      if ($booking['staff_id'] == $staff_id) {
         // User already booked the same timeslot with the same staff
         $booking_allowed = false;
         break;
      }
   }

// Step 2: Increment the retrieved ID to generate the new ID
if ($latest_id_row) {
        $latest_id = $latest_id_row['booking_id'];
        $new_id = incrementId($latest_id); // Pass only the ID to the function
} else {
        // If no records found in the bookings table, start with a default ID
        $new_id = 'KB00000001';
}


if (!$booking_allowed || count(array_unique($booked_staff_ids)) < count($existing_bookings)) {
        $msg = "<div class='alert alert-danger'>Anda tidak boleh menempah slot masa ini dengan ahli terapi yang sama /Sudah ada tempahan bertindih untuk ahli terapi yang dipilih!</div>";
     } else {
// Check if the user is already registered
$stmt = $conn->prepare("INSERT INTO `bookings` ( date,  duration,  claimstat, bookingstat , datetimeapplied, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$date,  $duration, "Pending", "Ditempah", $currentDateTime, $client, $service, $staff]);



echo '<p style="color:green;">Anda sudah berjaya membuat tempahan</p>';
