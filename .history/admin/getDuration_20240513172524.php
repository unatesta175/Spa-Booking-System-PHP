<?php

include '../components/connect.php'; ?>


	
	<?php
$select_accounts = $conn->prepare("SELECT * FROM `services` WHERE id = ? ");
	$select_accounts->execute([$_POST['servicev1']]);
	if ($select_accounts->rowCount() > 0) {
		while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

			echo "<input  readonly type="text'>" . $row['duration'] . "</input>";
			
		}
	}

	
	?>
    <input  readonly style="font-size:1.3rem;" type="text" name="duration" id="duration">