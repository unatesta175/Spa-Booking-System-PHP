<?php

include '../components/connect.php'; ?>

<table   class="table table-striped table-responsive">
			<tr>
				<th>Employee id</th>
				<th>Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Address</th>
				

			</tr>
			<?php
			
			$result=$db->getEmployee($_POST['selected_desig']);
			while($row=mysqli_fetch_array($result)){
				echo "<tr>
					<td>".$row['emp_id']."</td>
					<td>".$row['name']."</td>
					<td>".$row['phone']."</td>
					<td>".$row['email']."</td>
					<td>".$row['emp_address']."</td>
					
				</tr>";
			}
			$db->closeCon();
			?>
</table>