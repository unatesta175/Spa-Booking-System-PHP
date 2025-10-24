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

	$select_accounts = $conn->prepare("SELECT * FROM `bookings`");
	$select_accounts->execute();
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

 $id = $row['booking_id'];
                                                    $date = $row['date'];
                                                    $timeslot = $row['timeslot'];
                                                    $duration = $row['duration'];
                                                    $pay_amount = $row['pay_amount'];
			$id = $row['id'];
			echo "<tr>
				   <td>" . $row['emp_id'] . "</td>
				   <td>" . $row['name'] . "</td>
				   <td>" . $row['phone'] . "</td>
				   <td>" . $row['email'] . "</td>
				   <td>" . $row['emp_address'] . "</td>
				   <td>" . $row['desig_name'] . "</td>
			   </tr>";
		}
	}

	?>
</table>