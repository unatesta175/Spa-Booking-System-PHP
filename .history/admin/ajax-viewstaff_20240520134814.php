
<?php
include("connection.php");

$id = $_POST['userid'];

$stmt = $conn->prepare("SELECT * FROM staffs WHERE id = ?");

$stmt->execute([$id]);

$row = $stmt->rowCount();
if ($row > 0) {
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($res);
}
?>