<?php

include '../components/connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $client = $_POST['client'];
        $date= $_POST['date'];
        $staff = $_POST['staff'];
        $service = $_POST['service'];
        $duration = $_POST['duration'];


        // Check if the user is already registered
        $stmt = $conn->prepare("INSERT INTO `bookings` ( date,  duration, datetimeapplied, claimstat, bookingstat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ $date,  $duration, $currentDateTime, "Pending", "Ditempah", $client, $service, $staff]);
       
        
            if ($stmt->execute()) {
                echo '<p style="color:green;">Thanks for Subscribing</p>';
            } else {
                echo 'Something Error! Please Contact Site Owner!!';
            }
        
    
}
