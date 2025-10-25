<?php

include '../components/connect.php';

session_start();


   $staff_id = $_SESSION['staff_id'];


if (!isset($staff_id)) {
   header('location:login.php');
}


if(isset($_SESSION['login_success'])) {
   // Prepare JavaScript for showing the Sweet Alert
   $loginSuccessScript = "<script>
           window.onload = function() {
               Swal.fire({
                   title: 'Berjaya!',
                   text: 'Anda sudah berjaya log masuk!',
                   icon: 'success',
                   confirmButtonText: 'OK',
                   customClass: {
                       // Define a class for the SweetAlert popup
                       popup: 'my-custom-popup',
                       // Define a class for the SweetAlert title
                       title: 'my-custom-title',
                       // Define a class for the SweetAlert text
                       text: 'my-custom-text',
                       // Define a class for the SweetAlert confirm button
                       confirmButton: 'my-custom-confirm-button',
                       // Define a class for the SweetAlert cancel button
                       cancelButton: 'my-custom-cancel-button'
                   }
                  
               });
           }
         </script>";
   unset($_SESSION['login_success']); // Unset the flag
} else {
   $loginSuccessScript = "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php
   include '../components/functions.php';
   includeHeaderAdmin()
   ?>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
<style>

.image-container {
    text-align: center; /* Center the content horizontally */
    width: 100%; /* Ensure the container takes the full width */
    overflow: hidden; /* Prevents overflow issues */
    margin: 50px auto;
}

.image-container img {
    width: 100%; /* Make the image responsive */
    max-width: 200px; /* Maximum width of the image */
    height: auto; /* Maintain aspect ratio */
     /* Add margin to the top, right, bottom, and left, while centering the image horizontally */
}


</style>
</head>
<body>
<?php echo $loginSuccessScript; ?>
   <?php include '../components/staff-header.php'; ?>

   <section class="dashboard">

      
      <h1 class="heading">dashboard</h1>
      <div class="image-container">
         <img  style="margin-top: 10px; margin-bottom: 10px;" width="auto" height="70"
            src="../images/kapas-new-logo.png" alt="">
      </div>
      <div class="box-container">

         <div class="box">
            <h3>Selamat Datang!</h3>
            <p>
               <?= $fetch_profile['name']; ?>
            </p>
            <a href="update-profile.php" class="btn">Kemaskini Profil</a>
         </div>

         <div class="box">
            <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if ($select_pendings->rowCount() > 0) {
               while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
            ?>
            <h3><span>RM</span>
               <?= $total_pendings; ?><span></span>
            </h3>
            <p>Jumlah Belum Dibayar</p>
            <a href="placed-orders.php" class="btn">Lihat Order</a>
         </div>

        

      </div>

   </section>











   <script src="../js/sweetalert2.all.min.js"></script>
   <script src="../js/staff_script.js"></script>

</body>

</html>