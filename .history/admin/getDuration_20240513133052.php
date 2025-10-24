


		
		<?php
			include '../components/connect.php';
			$a = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
			$a->execute([$_POST['servicev1']]);
		
			// If rows are found, output duration input field
			if ($a->rowCount() > 0) {
				$row = $a->fetch(PDO::FETCH_ASSOC);
        
        echo $row['duration'];}
			
			
		?>
	
						
