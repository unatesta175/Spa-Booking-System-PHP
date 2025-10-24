<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#desig_dropdown").change(function(){
				
				var selected=$(this).val();
				var bruh = <?php echo json_encode($_POST['bruhh']); ?>; // Echo PHP variable into JavaScript
				$("#employee_div").load("search.php",{selected_desig: selected , bruhh: bruh});
			});
		
	});
</script>

	<select name="designation" class="form-control" id="desig_dropdown">
		<option>---Service---</option>
		<?php
			$select_accounts = $conn->prepare("SELECT * FROM `services`");
			$select_accounts->execute();
			if ($select_accounts->rowCount() > 0) {
				while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

					echo "<option value=".$row['id'].">".$row['name']."</option>";	
				}}
			
			
		?>
	</select>
						
	$_POST['bruhh']