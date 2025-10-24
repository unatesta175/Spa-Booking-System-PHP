<?php
include("connection.php");
$fetch_query = mysqli_query($connection, "select * from tbl_registration");
$row = mysqli_num_rows($fetch_query);
if($row>0)
{
	while($res = mysqli_fetch_array($fetch_query))
	{?>
       <tr>
       	<td><?php echo $res['id']; ?></td>
       	<td><?php echo $res['name']; ?></td>
       	<td><?php echo $res['email']; ?></td>
       	<td><?php echo $res['phone']; ?></td>
       	<td><?php echo $res['address']; ?></td>
       	<td><button type="button" class="btn btn-success" id="edit_btn" data-id="<?php echo $res['id']; ?>">Edit</button>
       </tr>
<?php	}
}
?>