<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


`id` INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(100) NOT NULL,
`ic` VARCHAR(20) NOT NULL,
`email` VARCHAR(70) NOT NULL,
`password` VARCHAR(70) NOT NULL,
`phone_no` VARCHAR(20) NOT NULL,

`datebirth` DATE NOT NULL,
`address` VARCHAR(255) NOT NULL,
`maritalstat` VARCHAR(20) NOT NULL,
`religion` VARCHAR(20) NOT NULL,
`occupation` VARCHAR(100) NOT NULL,
`company` VARCHAR(100) NOT NULL,
`fb` VARCHAR(30) NOT NULL,
`ig` VARCHAR(30) NOT NULL,
`allergy` VARCHAR(70) NOT NULL,
`datetime_registered` datetime NOT NULL

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $ic = $_POST['ic'];
   $ic = filter_var($ic, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   $phoneno = $_POST['phoneno'];
   $phoneno = filter_var($phoneno, FILTER_SANITIZE_STRING);
   $datebirth = $_POST['datebirth'];
   $datebirth = filter_var($datebirth, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $maritalstat = $_POST['maritalstat'];
   $maritalstat = filter_var($maritalstat, FILTER_SANITIZE_STRING);
   $religion = $_POST['religion'];
   $religion = filter_var($religion, FILTER_SANITIZE_STRING);
   $occupation = $_POST['religion'];
   $religion = filter_var($religion, FILTER_SANITIZE_STRING);
   $religion = $_POST['religion'];
   $religion = filter_var($religion, FILTER_SANITIZE_STRING);
   $religion = $_POST['religion'];
   $religion = filter_var($religion, FILTER_SANITIZE_STRING);
   $religion = $_POST['religion'];
   $religion = filter_var($religion, FILTER_SANITIZE_STRING);



   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email,]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
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
   include './components/functions.php';
   includeHeader();
   ?>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Daftar Akaun</h3>
      <label for="name">Nama:</label>
    <input type="text" id="name" name="name" required placeholder="Masukkan nama anda" maxlength="20" class="box">
    
    <label for="email">Emel:</label>
    <input type="email" id="email" name="email" required placeholder="Masukkan emel anda" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="pass">Kata Laluan:</label>
    <input type="password" id="pass" name="pass" required placeholder="Masukkan kata laluan anda" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="cpass">Kata Laluan Pengesahan:</label>
    <input type="password" id="cpass" name="cpass" required placeholder="Masukkan kata laluan pengesahan anda" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
       <p>Sudah ada Akaun?</p>
      <a href="user_login.php" class="option-btn">Log Masuk Sekarang</a>
   </form>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>