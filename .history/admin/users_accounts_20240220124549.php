<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">user accounts</h1>

   <div class="box-container">

   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `users`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
   ?>
   <div class="box">
      <p> user id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> email : <span><?= $fetch_accounts['email']; ?></span> </p>
      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account? the user related information will also be delete!')" class="delete-btn">delete</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no accounts available!</p>';
      }
   ?>

   </div>

</section>

<div class="container">
	<?php include("menus.php"); ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-default rounded-0 shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
							<h3 class="card-title">Senarai Kenderaan Pelajar</h3>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-end">
							<button type="button" class="btn btn-primary bg-gradient btn-sm rounded-0"><a
									href="addStudentcar.php" class="text-light">Tambah Kenderaan Pelajar</a></button>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="card-body">
					<button id="export-btn">Eksport ke Excel</button><br><br>
					<div class="row">
						<div class="col-sm-12 table-responsive">
							<table id="myTable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>Nama Pelajar</th>
										<th>Jantina</th>
										<th>No. Telefon</th>
										<th>No. Matriks</th>
										<th>No. Plat Kenderaan</th>
										<th>Tindakan</th>
									</tr>
									
								</thead>
								<tbody>
									<?php
									$no = 1;
									$sql = "select * from `studentcar`";
									$result = mysqli_query($con, $sql);

									if ($result) {
										while ($row = mysqli_fetch_assoc($result)) {
											$id = $row['studentid'];
											$name = $row['name'];
											$gender = $row['gender'];
											$phone = $row['phone'];
											$matric = $row['matric'];
											$platenum = $row['platenum'];

											echo '<tr>
                							<th>' . $no . '</th>
                							<td>' . $name . '</td>
                							<td>' . $gender . '</td>
											<td>' . $phone . '</td>
                							<td>' . $matric . '</td>
											<td>' . $platenum . '</td>	
               								<td>
											<center>
												<button><a href="viewStudentcar.php?id=' . $id . '">Lihat</a></button>
												<button><a href="updateStudentcar.php?id=' . $id . '">Kemaskini</a></button>
												<button><a href="deleteStudentcar.php?id=' . $id . '">Buang</a></button>
											</center>
											</td>
                							</tr>';
											$no++;
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>












<script src="../js/admin_script.js"></script>
   
</body>
</html>