<?php
include '../components/connect.php';

// Check if service ID is provided in the POST request

    // Prepare and execute query to fetch service duration based on service ID
    $select_servicev = $conn->prepare("SELECT * FROM `services` ");
    $select_servicev->execute();

    // If rows are found, output duration input field
    if ($select_servicev->rowCount() > 0) {
        $row = $select_servicev->fetch(PDO::FETCH_ASSOC);
        
        echo "<input id='durationfetch' readonly type='text' value='" . $row['duration'] . "' />";
    }
}else{echo "Anjai";}
?>
