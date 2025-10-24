<?php
include '../components/connect.php';

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