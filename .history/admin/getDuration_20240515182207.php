<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
	


		function formatDuration(minutes) {
			let hours = Math.floor(minutes / 60);
			let remainingMinutes = minutes % 60;
			let formattedDuration = '';

			if (hours > 0) {
				formattedDuration += hours + ' Jam';
			}
			if (remainingMinutes > 0) {
				if (hours > 0) {
					formattedDuration += ' ';
				}
				formattedDuration += remainingMinutes + ' Minit';
			}
			return formattedDuration;
		}

		// Example usage
		$(document).ready(function() {
			$("#durationparser").on('change', function() {
				var selected = $("#durationparser").val();
				var formatted = formatDuration(parseInt(selected));
				$("#duration").val(formatted);
			});
		});
	
</script>

<?php
$select_accounts = $conn->prepare("SELECT * FROM `services` WHERE id = ? ");
$select_accounts->execute([$_POST['servicev1']]);
if ($select_accounts->rowCount() > 0) {
	while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

		echo "<input   hidden id='durationparser' readonly type='text' value=" . $row['duration'] . " >";
	}
}



function formatDuration($minutes)
{
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