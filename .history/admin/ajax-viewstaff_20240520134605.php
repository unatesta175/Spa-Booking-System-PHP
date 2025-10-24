<?php
include("connection.php");
$id = $_POST['userid'];

$stmt = $conn->prepare("SELECT * FROM staffs WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the result as an associative array
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($res) {
        echo json_encode($res);
    }

	

?>