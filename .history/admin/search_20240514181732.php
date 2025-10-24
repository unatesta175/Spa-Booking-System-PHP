<?php

include '../components/connect.php'; ?>
<script type="text/javascript">
	$(document).ready(function() {


		var selected = $("#durationparser").val();
		$("#duration").val(selected);



	});
</script>

<table class="table table-striped table-responsive">
	<tr>
		<th>No Tempahan </th>
		<th>Tarikh Tempahan </th>
		<th>Pakej Terapi</th>
		<th>Nama Pakar Terapi</th>
		<th>Slot Masa Tempahan</th>
		<th>Tempoh Masa</th>
		<th>Test</th>

	</tr>
	<?php

	$existingBookings = [];
	if (isset($_GET['date'])) {
		$date = $_GET['date'];
		$selectedStaff = isset($_GET['staff']) ? $_GET['staff'] : null;

		$stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? AND staff_id = ?");
		$stmt->execute([$date, $selectedStaff]);

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$existingBookings[] = $row;
		}
	}

	$a = $conn->prepare("SELECT * FROM `services` WHERE service_id = ?");
	$a->execute([$_POST['service']]);
	if ($a->rowCount() > 0) {
		while ($rowa = $a->fetch(PDO::FETCH_ASSOC)) {

 
		}}

	$select_accounts = $conn->prepare("SELECT * FROM `bookings` WHERE service_id = ? AND staff_id = ? AND date = ?");
	$select_accounts->execute([$_POST['service'], $_POST['staff'], $_POST['date']]);
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


			echo "<tr>
				   <td>" . $row['booking_id'] . "</td>
				   <td>" . $row['date'] . "</td>
				   <td>" . $row['service_id'] . "</td>
				   <td>" . $row['staff_id'] . "</td>
				   <td>" . $row['timeslot'] . "</td>
				   <td>" . $row['duration'] . "</td>
				   <td>" . $_POST['duration'] . "</td>
			   </tr>";
		}
	}


	?>
</table>