


		
		<?php
			include '../components/connect.php';
			$a = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
			$select_servicev->execute([$_POST['servicev1']]);
		
			// If rows are found, output duration input field
			if ($select_servicev->rowCount() > 0) {
				$row = $select_servicev->fetch(PDO::FETCH_ASSOC);
        
        echo "<input id='durationfetch' readonly type='text' value='" . $row['duration'] . "' />";
    }
			
			
		?>
	
						
