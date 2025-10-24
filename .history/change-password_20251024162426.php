<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = ($_POST['old_pass']);
   $old_pass = htmlspecialchars(strip_tags(trim($old_pass)), ENT_QUOTES, 'UTF-8');
   $new_pass = ($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = ($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass == $empty_pass){
    $_SESSION['message'] = 'Sila masukkan kata laluan lama!';
 }elseif($old_pass != $prev_pass){
    $_SESSION['message'] = 'Kata laluan lama tidak sepadan!';
 }elseif($new_pass != $cpass){
    $_SESSION['message'] = 'Kata laluan pengesahan tidak sepadan!';
 }else{
    if($new_pass != $empty_pass){
       $update_admin_pass = $conn->prepare("UPDATE `clients` SET password = ? WHERE id = ?");
       $update_admin_pass->execute([$cpass, $user_id]);
       $_SESSION['message'] = 'Kata laluan berjaya dikemaskini!';
    }else{
       $_SESSION['message'] = 'Sila masukkan kata laluan baru!';
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
   
<?php include 'components/user-header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Kemaskini Kata Laluan</h3>
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
      <input type="password" name="cpass" id="cpass"placeholder="Sahkan kata laluan baru" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <i style="font-size:160%;"id="togglePasswordz" class="fas fa-eye"></i>
      </div>
      
      <input type="submit" value="Kemaskini" class="btn" name="submit">
   </form>

</section>

<?php include 'components/footer.php'; ?>
<script src="js/sweetalert2.all.min.js"></script>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if(isset($_SESSION['message'])): ?>
        var message = "<?php echo $_SESSION['message']; ?>";
        Swal.fire({
            icon: 'info',
            title: message,
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['message']); // Remove the message after displaying it ?>
    <?php endif; ?>
});
</script>
</body>
</html>