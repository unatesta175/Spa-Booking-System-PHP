<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $ic = $_POST['ic'];
   $ic = filter_var($ic, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = ($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = ($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   $phoneno = $_POST['phoneno'];
   $phoneno = filter_var($phoneno, FILTER_SANITIZE_STRING);
   $datebirth = $_POST['datebirth'];
   $datebirth = filter_var($datebirth, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = mb_convert_case($address, MB_CASE_TITLE, "UTF-8");
   $address = filter_var($address, FILTER_SANITIZE_STRING);
  
     // Set timezone to Kuala Lumpur
     date_default_timezone_set('Asia/Kuala_Lumpur');
     $currentDateTime = date('Y-m-d H:i:s');

   $select_user = $conn->prepare("SELECT * FROM `staffs` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Emel telah digunakan!';
   }else{
      if($pass != $cpass){
         $message[] = 'Pengesahan kata laluan tidak sepadan!';
      }else{
$insert_user = $conn->prepare("INSERT INTO `staffs`(name,ic, email, password,phoneno,datebirth,address,datetimeregistered) VALUES(?,?,?,?,?,?,?,?)");
         $insert_user->execute([$name,$ic,$email,$cpass,$phoneno,$datebirth,$address,$currentDateTime]);
         $message[] = 'Anda sudah berjaya daftar, sila log masuk!';
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
         <h3>Log Masuk Staff</h3>
         <br>
         <label for="username">Emel:</label>
         <input type="username" id="username" name="username" required placeholder="Masukkan username anda" maxlength="50"
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