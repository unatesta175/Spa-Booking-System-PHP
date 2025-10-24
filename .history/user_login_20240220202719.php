<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = 'incorrect username or password!';
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
   include './components/functions.php';
   includeHeader();
   ?>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

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
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
   <img class=" underline"  style=" margin-top: 3em;
      margin-bottom: 3em;"width="auto" height="100" src="./images/kapas-new-logo.png" alt="">
      <br>
      <h3>Log Masuk </h3>
      <br>
      <label for="email">Emel:</label>
    <input type="email" id="email" name="email" required placeholder="Masukkan emel anda" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <!-- Label and input for Password -->
    <label for="passid">Kata Laluan:</label>

    <div class="password-container">
  <input type="password" id="passLogin" name="pass" required placeholder="Masukkan kata laluan anda" maxlength="40" class="box">
  <i style="font-size:160%;"id="togglePassword" class="fas fa-eye"></i>
</div>
          <input type="submit" value="login now" class="btn" name="submit">
      <p>Tiada akaun?</p>
      <a href="user_register.php" class="option-btn">Daftar sekarang</a>
   </form>

</section>
















<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

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