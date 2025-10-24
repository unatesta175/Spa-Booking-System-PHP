<?php
include '../components/connect.php';

// Check if service ID is provided in the POST request
if(isset($_POST['servicev1'])) {
    // Prepare and execute query to fetch service duration based on service ID
    $select_servicev = $conn->prepare("SELECT * FROM `service` WHERE id = ?");
    $select_servicev->execute([$_POST['servicev1']]);

    // If rows are found, output duration input field
    if ($select_servicev->rowCount() > 0) {
        $row = $select_servicev->fetch(PDO::FETCH_ASSOC);
        echo "<input id='durationfetch' value='" . $row['duration'] . "' />";
    }
}
?>
