<?php

include '../components/connect.php'; ?>


<!-- <script type="text/javascript">
	$(document).ready(function(){
		
        $("#service").change(function() {
				var durationx =$("#durationfetch").val();
				var duration=$("#duration").val(durationx);
				$("#employee_div").load("search.php",{selected_desig: selected});
			});
		
	});
</script> -->
	<?php



$select_servicev = $conn->prepare("SELECT * FROM `service` WHERE id = ?");
$select_servicev->execute([$_POST['servicev1']]);
if ($select_servicev->rowCount() > 0) {
    while ($row = $select_servicev->fetch(PDO::FETCH_ASSOC)) {
        
         echo "<input id='durationfetch'  value=" . $row['duration'] . ">" . $row['duration'] . "</input>";
}}

	
	?>
</table>