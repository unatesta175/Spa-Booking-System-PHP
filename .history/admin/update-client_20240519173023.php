<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin-login.php');
}

if (isset($_POST['update'])) {

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $username = $_POST['username'];
   $username = filter_var($username, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   date_default_timezone_set('Asia/Kuala_Lumpur');
   $currentDateTime = date('Y-m-d H:i:s');

   $update_product = $conn->prepare("UPDATE `admins` SET name = ?, username = ?, email = ?, updated_at = ? WHERE id = ?");
   $update_product->execute([$name, $username, $email, $currentDateTime, $pid]);

   // $message[] = 'Admin Berjaya Dikemaskini!';
   $_SESSION['delete'] = true;
}
if (isset($_SESSION['delete'])) {
   // Prepare JavaScript for showing the Sweet Alert
   unset($_SESSION['delete']);
   $bookdeleteScript = "<script>
          window.onload = function() {
              Swal.fire({
                  title: 'Berjaya!',
                  text: 'Berjaya dikemaskini!',
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
   unset($_SESSION['delete']); // Unset the flag
} else {
   $bookdeleteScript = "";
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

</head>

<body>
   <?php echo $bookdeleteScript; ?>
   <?php include '../components/admin_header.php'; ?>

   <section class="update-product">

      <h1 class="heading">Kemaskini Pengguna</h1>

      <?php

      $update_id = isset($_GET['update']) ? $_GET['update'] : '';

      $select_products = $conn->prepare("SELECT * FROM `clients` WHERE id = ?");
      $select_products->execute([$update_id]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            // Decode the JSON to an array


      ?>
            <form action="" method="post" enctype="multipart/form-data">


               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">

               <span>Nama</span>
               <input type="text" name="name" required class="box" maxlength="100" placeholder="Nama" value="<?= $fetch_products['name']; ?>">

               <input type="text" name="name" required placeholder="Masukkan nama penuh anda" maxlength="100" class="box"value="<?php echo $name; ?>">
               <span>Username</span>
               <input type="text" name="username" required class="box" maxlength="100" placeholder="Username" value="<?= $fetch_products['username']; ?>">

               <span>Emel</span>
               <input type="text" name="email" required class="box" maxlength="100" placeholder="Emel" value="<?= $fetch_products['email']; ?>">



               <div class="flex-btn">
                  <input type="submit" name="update" class="btn" value="Kemaskini">
                  <a href="client-accounts.php" class="option-btn">Kembali</a>
               </div>
            </form>

      <?php
         }
      }
      ?>

   </section>

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="../js/admin_script.js"></script>

</body>

</html>