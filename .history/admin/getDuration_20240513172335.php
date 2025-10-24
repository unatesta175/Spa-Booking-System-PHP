<?php

include '../components/connect.php'; ?>


	
	<?php
$select_accounts = $conn->prepare("SELECT * FROM `services` WHERE id = ? ");
	$select_accounts->execute([$_POST['servicev1']]);
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


			echo "<tr>
				   <td>" . $row['duration'] . "</td>
				   <td>" . $row['date'] . "</td>
				   <td>" . $row['service_id'] . "</td>
				   <td>" . $row['staff_id'] . "</td>
				   <td>" . $row['timeslot'] . "</td>
				   <td>" . $row['duration'] . "</td>
			   </tr>";
		}
	}

	
	?>
