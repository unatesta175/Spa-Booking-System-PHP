<?php

include '../components/connect.php'; 

        $client = $_POST['client'];
        $date= $_POST['date'];
        $staff = $_POST['staff'];
        $service = $_POST['service'];
        $duration = $_POST['duration'];
        $staff = $_POST['staff'];
        $service = $_POST['service'];
        $duration = $_POST['duration'];
        var timeslot = $("input[name=timeslot]").val();
        var starttime = $("input[name=starttime]").val();
        var endtime = $("input[name=endtime]").val();

        // Check if the user is already registered
        $stmt = $conn->prepare("INSERT INTO `bookings` ( date,  duration,  claimstat, bookingstat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ $date,  $duration, "Pending", "Ditempah", $client, $service, $staff]);
       
        
          
                echo '<p style="color:green;">Thanks for Subscribing</p>';
           
        
    
