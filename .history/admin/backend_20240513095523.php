<?php

include '../components/connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];

    
	


            $select_accounts = $conn->prepare("SELECT * FROM `clients` WHERE `email`='$email'");
            $select_accounts->execute([$email]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);


    if ($select_user->rowCount() > 0) {
   
        echo  ' <p style="color:red;"> Already Subscribed! Please  try with  another email id  </p>';
    } else {
     

        $select_accounts = $conn->prepare("INSERT INTO `clients`(name, email,) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $select_accounts->execute([$email]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);


        $query = "insert into clients (name, email) VALUES('$name','$email')";
        $query_run = mysqli_query($conn, $query);
        if ($query_run) {
            echo ' <p style="color:green;">Thanks for Subscribing </p>';
        } else {
            echo ' Something Error! Please Contact Site Owner!! ';
        }
        
    }
}
