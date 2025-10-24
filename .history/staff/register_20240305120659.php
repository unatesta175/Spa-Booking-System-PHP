<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['staff_id'])){
   $staff_id = $_SESSION['staff_id'];
}else{
   $staff_id = '';
};

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
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/staff_style.css">
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
   
<?php include '../components/staff-header.php'; ?>
<section class="form-container">

<form action="" method="post">
   <h3>Daftar Staff</h3>
   <label for="name">Nama Penuh:<span style="color: red;">*</span></label>
<input type="text" id="name" name="name" required placeholder="Masukkan nama anda" maxlength="70" class="box">

<label for="email">Emel:<span style="color: red;">*</span></label>
<input type="email" id="email" name="email" required placeholder="Masukkan emel anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

<label for="passLogin">Kata Laluan:<span style="color: red;">*</span></label>

<div class="password-container">
<input type="password" id="passLogin" name="pass" required placeholder="Masukkan kata laluan anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
<i style="font-size:160%;"id="togglePassword" class="fas fa-eye"></i>
</div>

<label for="passLoginx">Kata Laluan Pengesahan:<span style="color: red;">*</span></label>
<div class="password-container">
<input type="password" id="passLoginx" name="cpass" required placeholder="Masukkan kata laluan pengesahan anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
<i style="font-size:160%;"id="togglePasswordx" class="fas fa-eye"></i>
</div>

<label for="ic">Nombor Kad Pengenalan:<span style="color: red;">*</span></label>
<input type="text" id="ic" name="ic"  required placeholder="ex : 020714104356 (tak perlu guna ' - ')" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

<label for="phoneno">Nombor Telefon:<span style="color: red;">*</span></label>
<input type="text" id="phoneno" name="phoneno"  required placeholder="Masukkan nombor telefon" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

<label for="datebirth">Tarikh Lahir:<span style="color: red;">*</span></label>
<input type="date" id="datebirth" name="datebirth"  required placeholder="Masukkan tarikh lahir anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

<label for="address">Alamat Kediaman:<span style="color: red;">*</span></label>
<input type="text" id="address" name="address"  required placeholder="Masukkan alamat kediaman anda" maxlength="70" class="box" >

   <input type="submit" value="Daftar" class="btn" name="submit">
   <p>Sudah ada Akaun?</p>
      <a href="login.php" class="option-btn">Log Masuk Sekarang</a>
  
  </form>

</section>

<?php include '../components/staff-footer.php'; ?>

<script src="../js/script.js"></script>

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

<script>const passwordInputx = document.getElementById("passLoginx");
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

</body>
</html>