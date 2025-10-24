<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#desig_dropdown").change(function(){
				
				var selected = $("#durationparser").val();
				$("#duration").val(selected);
			
			});
		
	});
</script>
	
	<?php
$select_accounts = $conn->prepare("SELECT * FROM `services` WHERE id = ? ");
	$select_accounts->execute([$_POST['servicev1']]);
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

			echo "<input  id='durationparser' readonly type='text' value=" . $row['duration']." >";
			
		}
	}

	
	?>
  