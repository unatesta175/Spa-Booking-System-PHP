<table   class="table table-striped table-responsive">
			<tr>
				<th>Employee id</th>
				<th>Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Address</th>
				<th>Designation</th>

			</tr>
			<?php
			
			 $select_accounts = $conn->prepare("SELECT * FROM `staffs`");
			 $select_accounts->execute();
			 if ($select_accounts->rowCount() > 0) {
				while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


				   $id = $row['id'];

				}}
			while($row=mysqli_fetch_array($result)){
				
			}
			$db->closeCon();
			?>
</table>

