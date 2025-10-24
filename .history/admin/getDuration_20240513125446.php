<?php

include '../components/connect.php'; ?>


<script type="text/javascript">
	$(document).ready(function(){
		
        $("#service").change(function() {
				var durationx =$("#durationfetch").val();
				var duration=$("#duration").val();
				$("#employee_div").load("search.php",{selected_desig: selected});
			});
		
	});
</script>
	<?php



$select_servicev = $conn->prepare("SELECT * FROM `service` WHERE id = ?");
$select_servicev->execute([$_POST['service']]);
if ($select_servicev->rowCount() > 0) {
    while ($row = $select_servicev->fetch(PDO::FETCH_ASSOC)) {
        
         echo "<input id='durationfetch'  value=" . $row['duration'] . ">" . $row['duration'] . "</input>";
}}

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