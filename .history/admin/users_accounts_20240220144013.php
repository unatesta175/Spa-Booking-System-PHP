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

    
    <!-- Bootstrap The most important for Data Tables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    




    <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="js/jquery.dataTables.min.js"></script>
   <script src="js/dataTables.bootstrap.min.js"></script>
   
   <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
   <style>
        @media only screen and (max-width: 300px) {
            table {
                width: 100%;
            }

            thead {
                display: none;
            }

            tr:nth-of-type(2n) {
                background-color: inherit;
            }

            tr td:first-child {
                background: #f0f0f0;
                font-weight: bold;
                font-size: 1.3em;
                text-align: center;
            }

            tbody td {
                display: block;
                text-align: center;
            }

            tbody td:before {
                content: attr(data-th);
                display: block;
                text-align: left;
            }
        }

    .container{ padding:2rem;}
    
    button {
  text-decoration: none; /* Remove underline */
 
}
    </style>
</head>
<body>

  

<?php include '../components/admin_header.php'; ?>
<div class="container">
	
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-default rounded-0 shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
							<h3 class="card-title">Senarai Pengguna</h3>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-end">
							<button type="button" class="option-btn"><a
									href="addStudentcar.php" class="text-light">Tambah user</a></button>
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
										<th>ID Penggguna</th>
										<th>Nama Pengguna</th>
										<th>Email</th>
										<th>Tindakan</th>
									</tr>
									
								</thead>
								<tbody>
									<?php
									$no = 1;
									$select_accounts = $conn->prepare("SELECT * FROM `users`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($row = $select_accounts->fetch(PDO::FETCH_ASSOC)){

									
											$id = $row['id'];
											$name = $row['name'];
											$email = $row['email'];
											
											
		

											echo '<tr>
                							<td>' . $no . '</td>
                							<td>' . $id . '</td>
                							<td>' . $name . '</td>
                							<td>' . $email . '</td>
											
               								<td>
                                       <center><a class=" underline" style="font-size: 1.5em;" href="users_accounts.php?delete=' . $id . '" onclick="return confirm(`delete this account? the user related information will also be delete!"> <center><i class=" fa fa-trash" style="color:red">
                                       </i></center></a>&nbsp;&nbsp;&nbsp;&nbsp;</center>
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














<script src="../js/admin_script.js"></script>
   
</body>
</html>