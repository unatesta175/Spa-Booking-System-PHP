<?php
echo $_POST['datee'];
include '../components/connect.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#desig_dropdown").change(function(){
				
				var selected=$(this).val();
				var bruh = <?php echo json_encode($_POST['selected_depart']); ?>; // Echo PHP variable into JavaScript
				$("#employee_div").load("search.php",{selected_desig: selected , bruhh: bruh});
			});
		
	});
</script>



						
