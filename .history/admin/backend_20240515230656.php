<?php

include '../components/connect.php'; 

if (isset($_POST['submit'])) {
        $client = $_POST['client'];
        $date= $_POST['date'];
        $staff = $_POST['staff'];
        $service = $_POST['service'];
        $duration = $_POST['duration'];
        $timeslot = $_POST['timeslot'];
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];
      

        // Check if the user is already registered
        $stmt = $conn->prepare("INSERT INTO `bookings` ( date,  duration,  claimstat, bookingstat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ $date,  $duration, "Pending", "Ditempah", $client, $service, $staff]);
       
        
          
                echo '<p style="color:green;">Anda sudah berjaya membuat tempahan</p>';
           
        
    
