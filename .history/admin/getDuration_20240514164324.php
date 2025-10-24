<?php
include '../components/connect.php';

$serviceId = $_POST['servicev1'];

$select_accounts = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
$select_accounts->execute([$serviceId]);
if ($select_accounts->rowCount() > 0) {
    while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
        echo "<input hidden id='durationparser' readonly type='text' value='" . $row['duration'] . "'>";
    }
}
?>

<script type="text/javascript">
$(document).ready(function(){
    var selected = $("#durationparser").val();
    $("#duration").val(selected);
});
</script>

	
	
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
  