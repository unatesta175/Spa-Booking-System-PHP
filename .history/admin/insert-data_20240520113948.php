<?php
include('connection.php');
$name = $_POST['fullname'];
$email = $_POST['emailid'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$insert_query = mysqli_query($connection, "staffs set name='$name', email='$email', phoneno='$phone', address='$address'");
if($insert_query>0)
{
	echo "Data Submitted Successfuly!";
}
else
{
	echo "Error!";
}
?>