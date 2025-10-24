<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = ($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = ($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   date_default_timezone_set('Asia/Kuala_Lumpur');
   $currentDateTime = date('Y-m-d H:i:s');

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
   $select_admin->execute([$name]);

   if ($select_admin->rowCount() > 0) {
      $message[] = 'Username sudah digunakan!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Kata laluan pengesahan tidak sepadan!';
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `admins`(name,email, password, datetimeregistered) VALUES(?,?,?)");
         $insert_admin->execute([$name, $email, $cpass, $currentDateTime]);
         $message[] = 'Admin baru sudah didaftarkan!';
      }
   }

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

   <?php include '../components/admin_header.php'; ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>Daftar Admin</h3>
         <label for="name">Username:<span style="color: red;">*</span></label>
         <input type="text" id="name" name="name" required placeholder="Masukkan nama anda" maxlength="70" class="box">

         <label for="email">Emel:<span style="color: red;">*</span></label>
         <input type="email" id="email" name="email" required placeholder="Masukkan emel anda" maxlength="70"
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">

         <label for="passLogin">Kata Laluan:<span style="color: red;">*</span></label>

         <div class="password-container">
            <input type="password" id="passLogin" name="pass" required placeholder="Masukkan kata laluan anda"
               maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <i style="font-size:160%;" id="togglePassword" class="fas fa-eye"></i>
         </div>

         <label for="passLoginx">Kata Laluan Pengesahan:<span style="color: red;">*</span></label>
         <div class="password-container">
            <input type="password" id="passLoginx" name="cpass" required
               placeholder="Masukkan kata laluan pengesahan anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')">
            <i style="font-size:160%;" id="togglePasswordx" class="fas fa-eye"></i>
         </div>
         <input type="submit" value="register now" class="btn" name="submit">
      </form>

   </section>












   <script src="../js/admin_script.js"></script>

</body>

</html>