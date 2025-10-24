<?php

include '../components/connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];

    
	$select_accounts->execute();
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


            $select_accounts = $conn->prepare("SELECT * FROM `clients` WHERE `email`='$email'");
    $query = "select * from clients WHERE `email`='$email'";
    $query_run = mysqli_query($conn, $query);
  

    if (mysqli_num_rows($query_run) > 0) {
   
        echo  ' <p style="color:red;"> Already Subscribed! Please  try with  another email id  </p>';
    } else {
     
        $query = "insert into clients (name, email) VALUES('$name','$email')";
        $query_run = mysqli_query($conn, $query);
        if ($query_run) {
            echo ' <p style="color:green;">Thanks for Subscribing </p>';
        } else {
            echo ' Something Error! Please Contact Site Owner!! ';
        }
        
    }
}
