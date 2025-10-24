<?php
include '../components/connect.php';

// Check if service ID is provided in the POST request
if(isset($_POST['servicev1'])) {
    // Prepare and execute query to fetch service duration based on service ID
    $select_servicev = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
    $select_servicev->execute([$_POST['servicev1']]);

    // If rows are found, output duration input field
    if ($select_servicev->rowCount() > 0) {
        $row = $select_servicev->fetch(PDO::FETCH_ASSOC);
        echo "<input id='durationfetch' value='" . $row['duration'] . "' />";
        echo "<input id='durationfetch' readonly type='text' value='" . $row['duration'] . "' />";
    }
}else{echo "Anjai";}
?><?php

include '../components/connect.php'; ?>

<table class="table table-striped table-responsive">
	<tr>
		<th>No Tempahan </th>
		<th>Tarikh Tempahan </th>
		<th>Pakej Terapi</th>
		<th>Nama Pakar Terapi</th>
		<th>Slot Masa Tempahan</th>
		<th>Tempoh Masa</th>

	</tr>
	<?php




$select_accounts = $conn->prepare("SELECT * FROM `bookings` WHERE service_id = ? AND staff_id = ? AND date = ?");
	$select_accounts->execute([$_POST['service'],$_POST['staff'],$_POST['date']]);
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


			echo "<tr>
				   <td>" . $row['booking_id'] . "</td>
				   <td>" . $row['date'] . "</td>
				   <td>" . $row['service_id'] . "</td>
				   <td>" . $row['staff_id'] . "</td>
				   <td>" . $row['timeslot'] . "</td>
				   <td>" . $row['duration'] . "</td>
			   </tr>";
		}
	}

	
	?>
</table>
