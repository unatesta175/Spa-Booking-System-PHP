<?php

include '../components/connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $client = $_POST['client'];
        $date= $_POST['date'];
        $staff = $_POST['staff'];
        $service = $_POST['service'];

        // Check if the user is already registered
        $stmt = $conn->prepare("INSERT INTO `bookings` ( date,  duration, datetimeapplied, claimstat, bookingstat, pay_amount, pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ $date, $starttime, $endtime, $duration, $currentDateTime, "Pending", "Ditempah", $pay_amount, "Belum Bayar", $user_id, $service_id, $staff_id]);
       
        

        if ($stmt->rowCount() > 0) {
            echo '<p style="color:red;">Already Subscribed! Please try with another email id</p>';
        } else {
            // Insert user data into the table
            $stmt = $conn->prepare("INSERT INTO clients (name, email) VALUES (:name, :email)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                echo '<p style="color:green;">Thanks for Subscribing</p>';
            } else {
                echo 'Something Error! Please Contact Site Owner!!';
            }
        }
    
}
