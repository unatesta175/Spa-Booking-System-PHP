<?php

include '../components/connect.php'; 


        $client = $_POST['client'];
        $date= $_POST['date'];
        $staff = $_POST['staff'];
        $service = $_POST['service'];
        $duration = $_POST['duration'];
        $timeslot = $_POST['timeslot'];
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];

        $result = $conn->prepare("SELECT * FROM `services` Where id =?");
        $result->execute([$service_id]);
        if ($result->rowCount() > 0) {
           while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
     
              $duration_service = $row['duration'];
              $pay_amount = $row['price'];
           }
        }
      
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $currentDateTime = date('Y-m-d H:i:s');

        // Check if the user is already registered
        $stmt = $conn->prepare("INSERT INTO `bookings` ( date,  duration,  claimstat, bookingstat , datetimeapplied, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ $date,  $duration, "Pending", "Ditempah", $currentDateTime ,$client, $service, $staff]);
       
        
          
                echo '<p style="color:green;">Anda sudah berjaya membuat tempahan</p>';
           
        

