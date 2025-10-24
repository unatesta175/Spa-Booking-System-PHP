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
   
   
         $update_admin_pass = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
         $update_admin_pass->execute([$name, $admin_id]);
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
      <h3>Kemaskini Profil</h3>
      <br>
       <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>"> 
   
      
      <label for="old_pass">Kata laluan lama:</label>
      <div class="password-container">
      <input type="password" name="old_pass" id="old_pass" placeholder="Masukkan kata laluan lama" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <i style="font-size:160%;"id="togglePasswordx" class="fas fa-eye"></i>
      </div>

      <label for="old_pass">Kata laluan baru:</label>
      <div class="password-container">
      <input type="password" name="new_pass" id="new_pass" placeholder="Masukkan kata laluan baru" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <i style="font-size:160%;"id="togglePasswordy" class="fas fa-eye"></i>
      </div>

      <label for="old_pass">Sahkan kata laluan baru:</label>
      <div class="password-container">
      <input type="password" name="confirm_pass" id="cpass"placeholder="Sahkan kata laluan baru" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <i style="font-size:160%;"id="togglePasswordz" class="fas fa-eye"></i>
      </div>
      
      <input type="submit" value="Kemaskini" class="btn" name="submit">
   </form>

   </section>


   <script>const passwordInputx = document.getElementById("old_pass");
const togglePasswordx = document.getElementById("togglePasswordx");

togglePasswordx.addEventListener("click", function () {
  if (passwordInputx.type === "password") {
    passwordInputx.type = "text";
    togglePasswordx.classList.remove("fa-eye");
    togglePasswordx.classList.add("fa-eye-slash");
  } else {
    passwordInputx.type = "password";
    togglePasswordx.classList.remove("fa-eye-slash");
    togglePasswordx.classList.add("fa-eye");
  }
});</script>

<script src="js/script.js"></script>

<script>const passwordInputy = document.getElementById("new_pass");
const togglePasswordy = document.getElementById("togglePasswordy");

togglePasswordy.addEventListener("click", function () {
  if (passwordInputy.type === "password") {
    passwordInputy.type = "text";
    togglePasswordy.classList.remove("fa-eye");
    togglePasswordy.classList.add("fa-eye-slash");
  } else {
    passwordInputy.type = "password";
    togglePasswordy.classList.remove("fa-eye-slash");
    togglePasswordy.classList.add("fa-eye");
  }
});</script>

<script src="js/script.js"></script>

<script>const passwordInputz = document.getElementById("cpass");
const togglePasswordz = document.getElementById("togglePasswordz");

togglePasswordz.addEventListener("click", function () {
  if (passwordInputz.type === "password") {
    passwordInputz.type = "text";
    togglePasswordz.classList.remove("fa-eye");
    togglePasswordz.classList.add("fa-eye-slash");
  } else {
    passwordInputz.type = "password";
    togglePasswordz.classList.remove("fa-eye-slash");
    togglePasswordz.classList.add("fa-eye");
  }
});</script>











   <script src="../js/admin_script.js"></script>

</body>

</html>