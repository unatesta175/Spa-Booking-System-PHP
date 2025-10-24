<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#desig_dropdown").change(function(){
				
				var selected=$(this).val();
			var x = "<?php echo $selected_staff; ?>";
				$("#employee_div").load("search.php",{selected_desig: selected, xvariable: x });
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

	<!-- <input type="hidden" name="x_variable" id="x_variable" value=""> -->
						
