<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		
				
		var selected = $("#durationparser").val();
        var formatted = formatDuration(parseInt(selected));
        $("#duration").val(formatted);
		
	});

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
</script>
	
	<?php
$select_accounts = $conn->prepare("SELECT * FROM `services` WHERE id = ? ");
	$select_accounts->execute([$_POST['servicev1']]);
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

			echo "<input hidden id='durationparser' readonly type='text' value=" . $row['duration']+30 ." >";
			
			echo "<input id='servicename' readonly type='text' value=" . $row['name'] ." >";
			
		}
	}


	$select_accounts1 = $conn->prepare("SELECT * FROM `clients` WHERE id = ? ");
	$select_accounts1->execute([$_POST['clientv1']]);
	if ($select_accounts1->rowCount() > 0) {
		while ($row1 = $select_accounts1->fetch(PDO::FETCH_ASSOC)) {

			echo "<input id='clientname' readonly type='text' value='" . $row1['name'] ."' >";

		}
	}

	$select_accounts2 = $conn->prepare("SELECT * FROM `staffs` WHERE id = ? ");
	$select_accounts2->execute([$_POST['staffv1']]);
	if ($select_accounts2->rowCount() > 0) {
		while ($row2 = $select_accounts2->fetch(PDO::FETCH_ASSOC)) {

			echo "<input id='staffname' readonly type='text' value=" . $row2['name']."' >";

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
  