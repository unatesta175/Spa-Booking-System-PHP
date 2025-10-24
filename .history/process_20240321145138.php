<?php

include 'components/connect.php';

session_start();

if (isset ($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

if (isset ($_POST['submit'])) {


   $date = $_POST['date'];
   $date = filter_var($date, FILTER_SANITIZE_STRING);
   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING);
 
   $remarks = ($_POST['remarks']);
   $remarks = filter_var($remarks, FILTER_SANITIZE_STRING);
 

   $staff_id = $_POST['staff_id'];
   $staff_id = filter_var($staff_id, FILTER_SANITIZE_STRING);
   $service_id = $_POST['service_id'];
   $service_id = filter_var($service_id, FILTER_SANITIZE_STRING);
   $pay_type = ($_POST['pay_type']);
   $pay_type = filter_var($pay_type, FILTER_SANITIZE_STRING);


   $result = $conn->prepare("SELECT * FROM `services` Where id =?");
$result->execute([$service_id]);
   if ($result->rowCount() > 0) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
        
            $duration_service = $row['duration']; 
            $pay_amount = $row['price'];
      }
   } 
   
   $duration = $duration_service +30;
   
   $pay_method="Perbankan Dalam Talian";



   // Set timezone to Kuala Lumpur
   date_default_timezone_set('Asia/Kuala_Lumpur');
   $currentDateTime = date('Y-m-d H:i:s');

   $get_latest_id = $conn->query("SELECT id FROM bookings ORDER BY id DESC LIMIT 1");
   $latest_id_row = $get_latest_id->fetch(PDO::FETCH_ASSOC);

   function incrementId($id)
   {
       $prefix = substr($id, 0, 2);
       $number = (int) substr($id, 2);
       $number++;
       return $prefix . sprintf('%08d', $number);
   }
   
   // Step 2: Increment the retrieved ID to generate the new ID
   if ($latest_id_row) {
       $latest_id = $latest_id_row['id'];
       $new_id = incrementId($latest_id); // Pass only the ID to the function
   } else {
       // If no records found in the bookings table, start with a default ID
       $new_id = 'KB00000001';
   }

   echo $new_id;

   // Step 3: Insert the new booking with the incremented ID
   $insert_user = $conn->prepare("INSERT INTO `bookings` (id, date, time, duration, remarks, datetimeapplied, claimstat, bookingstat, pay_method, pay_type, pay_amount,  pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
   $insert_user->execute([$new_id, $date, $time, $duration, $remarks, $currentDateTime, "Pending", "Ditempah", $pay_method, $pay_type, $pay_amount, "Telah Bayar", $user_id, $service_id, $staff_id]);
   $message[] = 'Anda sudah berjaya menempah sesi rawatan';

   // Function to increment ID
   

}
include 'components/wishlist_cart.php';

?>