<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = ($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if ($select_admin->rowCount() > 0) {
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   } else {
      $message[] = 'Nama pengguna atau kata laluan tidak sepadan!';
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

   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
   ?>

   <section class="form-container">

   <form action="" method="post">
         <img class=" underline" style=" margin-top: 3em;
      margin-bottom: 3em;" width="auto" height="100" src="../images/kapas-new-logo.png" alt="">
         <br>
         <h3>Log Masuk Admin</h3>
         <br>
         <label for="email">Emel:</label>
         <input type="email" id="email" name="email" required placeholder="Masukkan emel anda" maxlength="50"
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">

         <!-- Label and input for Password -->
         <label for="passLogin">Kata Laluan:</label>

         <div class="password-container">
            <input type="password" id="passLogin" name="pass" required placeholder="Masukkan kata laluan anda"
               maxlength="40" class="box">
            <i style="font-size:160%;" id="togglePassword" class="fas fa-eye"></i>
         </div>
         <input type="submit" value="Log Masuk " class="btn" name="submit">
      </form>

   </section>

   <script>const passwordInput = document.getElementById("passLogin");
      const togglePassword = document.getElementById("togglePassword");

      togglePassword.addEventListener("click", function () {
         if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");
         } else {
            passwordInput.type = "password";
            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");
         }
      });</script>

</body>

</html>