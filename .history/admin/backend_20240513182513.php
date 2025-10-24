<?php

include '../components/connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = $_POST['client'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        // Check if the user is already registered
        $stmt = $conn->prepare("SELECT * FROM clients WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

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
