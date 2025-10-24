

$("#service").change(function() {
                var servicev = $(this).val();
                var datev = $("#date").val(); 
		
		<?php
			include '../components/connect.php';
			$select_servicev = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
			$select_servicev->execute([$_POST['servicev1']]);
		
			// If rows are found, output duration input field
			if ($select_servicev->rowCount() > 0) {
				$row = $select_servicev->fetch(PDO::FETCH_ASSOC);
        
        echo "<input id='durationfetch' readonly type='text' value='" . $row['duration'] . "' />";
    }
			
			
		?>
	
						
