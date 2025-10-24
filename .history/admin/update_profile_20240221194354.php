<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['submit'])) {

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = ($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = ($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = ($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if ($old_pass == $empty_pass) {
      $message[] = 'Sila masukkan kata laluan lama!';
   } elseif ($old_pass != $prev_pass) {
      $message[] = 'Kata laluan lama tidak sepadan!';
   } elseif ($new_pass != $confirm_pass) {
      $message[] = 'Kata laluan pengesahan tidak sepadan!';
   } else {
      if ($new_pass != $empty_pass) {
         $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$confirm_pass, $admin_id]);
         $message[] = 'Kata laluan berjaya dikemaskini!';
      } else {
         $message[] = 'Sila masukkan kata laluan baru!';
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

   <style>
      .password-container {
         position: relative;
      }

      .password-container i {
         position: absolute;
         top: 50%;
         right: 10px;
         transform: translateY(-50%);
         cursor: pointer;
         color: #888;
      }

      .password-container i:hover {
         color: #333;
      }
   </style>
</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>update profile</h3>
         <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
         <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required
            placeholder="enter your username" maxlength="20" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="old_pass" placeholder="enter old password" maxlength="20" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="new_pass" placeholder="enter new password" maxlength="20" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="confirm_pass" placeholder="confirm new password" maxlength="20" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="update now" class="btn" name="submit">
      </form>

   </section>












   <script src="../js/admin_script.js"></script>

</body>

</html>