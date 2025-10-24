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
   $ic = $_POST['ic'];
   // $email = $_POST['email'];
   $phoneno = $_POST['phoneno'];
   $datebirth = $_POST['datebirth'];
   $address = $_POST['address'];
   $maritalstat = $_POST['maritalstat'];
   $religion = $_POST['religion'];
   $occupation = $_POST['occupation'];
   $religion = $_POST['religion'];
   $company = $_POST['company'];
   $fb = $_POST['fb'];
   $ig = $_POST['ig'];
   $allergy = $_POST['allergy'];

   if ($religion === 'Lain-lain') {
      $religion = $_POST['otherReligion']; // Use the value from the text input
    } 

    if ($occupation === 'Lain-lain') {
      $occupation = $_POST['otherOccupation']; // Use the value from the text input
    } 
     // Set timezone to Kuala Lumpur
     date_default_timezone_set('Asia/Kuala_Lumpur');
     $currentDateTime = date('Y-m-d H:i:s');

   $update = $conn->prepare("UPDATE `clients` SET name = ?, ic = ?, phoneno = ? , datebirth= ? , address= ? , maritalstat= ?, religion= ?, occupation= ?, company= ?, fb= ?, ig= ?, allergy= ? WHERE id = ?");
   $update->execute([$name, $ic, $phoneno, $datebirth, $address, $maritalstat,$religion,$occupation,$company,$fb,$ig, $allergy, $pid]);
  
   
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

      <h1 class="heading">Kemaskini Staff</h1>

      <?php

      $update_id = isset($_GET['update']) ? $_GET['update'] : '';

      $select_products = $conn->prepare("SELECT * FROM `staffs` WHERE id = ?");
      $select_products->execute([$update_id]);
      if ($select_products->rowCount() > 0) {
         while ($row = $select_products->fetch(PDO::FETCH_ASSOC)) {
            // Decode the JSON to an array

               $name = $row['name'];
               $ic = $row['ic'];
               $email = $row['email'];
               $phoneno = $row['phoneno'];
               $datebirth = $row['datebirth'];
               $address = $row['address'];
      ?>
            <form action="" method="post" enctype="multipart/form-data">


               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">

               <span for="name">Nama Penuh </span>
         <input type="text" name="name" required placeholder="Masukkan nama penuh anda" maxlength="100" class="box" value="<?php echo $name; ?>">
         <!-- 
            <span  for="name">Emel :</span>
            <input type="text" name="email" required placeholder="Masukkan emel anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $email; ?>"> -->

         <span for="name">No. Kad Pengenalan </span>
         <input type="text" name="ic" required placeholder="Masukkan no. kad pengenalan anda" maxlength="100" oninput="this.value = this.value.replace(/\s/g, '')" class="box" value="<?php echo $ic; ?>">

         <span for="name">No. Telefon </span>
         <input type="text" name="phoneno" required placeholder="Masukkan no. telefon anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $phoneno; ?>">

         <span for="name">Tarikh Lahir </span>
         <input type="date" name="datebirth" required placeholder="Masukkan tarikh lahir anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $datebirth; ?>">

         <span for="address">Alamat Kediaman<span style="color: red;"></span></span>
         <input value="<?php echo $address; ?>" type="text" id="address" name="address" required placeholder="Masukkan alamat kediaman anda" maxlength="70" class="box">


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