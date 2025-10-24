<?php
include("connection.php");
$id = $_POST['userid'];

$fetch_query = mysqli_query($connection, "select * from staffs where id='$id'");
$row = mysqli_num_rows($fetch_query);
if($row>0)
{
	$res = mysqli_fetch_array($fetch_query);
	echo json_encode($res);
}
?>

<?php
include("connection.php");

$id = $_POST['userid'];

$stmt = $conn->prepare("SELECT * FROM staffs WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->rowCount();
if ($row > 0) {
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($res);
}
?>