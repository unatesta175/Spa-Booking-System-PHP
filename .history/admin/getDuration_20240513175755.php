<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		
				
				var selected = $("#durationparser").val();
				$("#duration").val(selected);
			
			
		
	});
</script>
	
	<?php
$select_accounts = $conn->prepare("SELECT * FROM `services` WHERE id = ? ");
	$select_accounts->execute([$_POST['servicev1']]);
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

			echo "<input  hidden id='durationparser' readonly type='text' value=" . formatDuration(intVal($row['duration']+30))." >";
			
		}
	}

	
	
To convert the duration value from minutes to a more human-readable format like "X minutes" or "Y hour(s) Z minutes", you can use the following PHP function:

	php
	Copy code
	function formatDuration($minutes) {
		$hours = floor($minutes / 60);
		$remainingMinutes = $minutes % 60;
	
		$output = '';
		if ($hours > 0) {
			$output .= $hours == 1 ? '1 jam' : $hours . ' jam';
		}
		if ($remainingMinutes > 0) {
			$output .= $output ? ' ' : ''; // Add a space if hours are present
			$output .= $remainingMinutes == 1 ? '1 minit' : $remainingMinutes . ' minit';
		}
		return $output;
	}
	?>
  