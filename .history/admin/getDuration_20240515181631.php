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

			echo "<input   hidden id='durationparser' readonly type='number' value='<? echo formatDuration($row['duration']); ?>" >";
			
		}
	}

	
	
	function formatDuration($minutes) {
		$hours = floor($minutes / 60);
		$remainingMinutes = $minutes % 60;
	
		$output = '';
	
		if ($hours > 0) {
			$output .= $hours . ' jam';
			if ($remainingMinutes > 0) {
				$output .= ' ' . $remainingMinutes . ' minit';
			}
		} else {
			$output .= $remainingMinutes . ' minit';
		}
	
		return $output;
	}
	
	?>
  