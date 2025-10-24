<?php
include("connection.php");
$id = $_POST['userid'];

$fetch_query = mysqli_query($connection, "select * from tbl_registration where id='$id'");
$row = mysqli_num_rows($fetch_query);
if($row>0)
{
	$res = mysqli_fetch_array($fetch_query);
	echo json_encode($res);
}
?>