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

	$stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? AND staff_id = ?");
	$stmt->execute([$_POST['date'], $_POST['staff']]);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$existingBookings[] = $row;
	}

	$a = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
	$a->execute([$_POST['service']]);
	if ($a->rowCount() > 0) {
		while ($rowa = $a->fetch(PDO::FETCH_ASSOC)) {

			$duration = $rowa['duration'];
		}
	}

	$duration = $dura; // Duration of each appointment in minutes
	$cleanup = 0; // Time gap between appointments in minutes
	$start = "10:00"; // Start time for appointments
	$end = "19:00"; // End time for appointments

	function timeslots($duration, $cleanup, $start, $end, $existingBookings)
	{
		// Convert start and end times to timestamps
		$startTime = strtotime($start); // Convert start time to timestamp
		$endTime = strtotime($end); // Convert end time to timestamp

		$slots = array(); // Initialize an array to store available timeslots

		// Loop through each timeslot
		for ($currentTime = $startTime; $currentTime < $endTime; $currentTime += ($duration + $cleanup) * 60) {
			// Calculate end time for the current timeslot
			$endTimeSlot = $currentTime + $duration * 60;

			// Skip lunchtime (1 pm - 2 pm)
			// if (date('H', $currentTime) == '13') {
			//    continue;
			// }

			// Check if the current timeslot overlaps with any existing booking
			$overlap = false; // Flag to indicate if there's an overlap
			foreach ($existingBookings as $booking) {
				$bookingStartTime = strtotime($booking['starttime']); // Convert booking start time to timestamp
				$bookingEndTime = $bookingStartTime + $booking['duration'] * 60; // Calculate booking end time

				// If the timeslots overlap, set overlap flag to true
				if (
					($currentTime >= $bookingStartTime && $currentTime < $bookingEndTime) ||
					($endTimeSlot > $bookingStartTime && $endTimeSlot <= $bookingEndTime)
				) {
					$overlap = true;
					break;
				}
			}

			// Format timeslot start and end times in 24-hour format
			$slotStartTime = date('H:i', $currentTime); // Format start time
			$slotEndTime = date('H:i', $endTimeSlot); // Format end time

			// Add timeslot to the array with overlap flag
			$slots[] = array(
				'timeslot' => $slotStartTime . ' - ' . $slotEndTime . ' ' . date('A', $currentTime),
				'overlap' => $overlap // Store whether the timeslot overlaps with an existing booking
			);
		}

		return $slots; // Return the array of available timeslots with overlap flags
	}

	

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
				   <td>" . $test . "</td>
			   </tr>";
		}
	}


	?>
</table>