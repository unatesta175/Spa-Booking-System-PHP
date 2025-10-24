<?php

$db_name = 'mysql:host=localhost;dbname=spa_db';
$user_name = 'root';
$user_password = '';

$conn = new PDO($db_name, $user_name, $user_password);
$id = $_POST['userid'];

$fetch_query = mysqli_query($connection, "select * from staffs where id='$id'");
$row = mysqli_num_rows($fetch_query);
if($row>0)
{
	$res = mysqli_fetch_array($fetch_query);
	echo json_encode($res);
}
?>