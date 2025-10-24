<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


if(isset($_POST['submit'])){

   $name = $_POST['name'];
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
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $maritalstat = $_POST['maritalstat'];
   $maritalstat = filter_var($maritalstat, FILTER_SANITIZE_STRING);
   $religion = $_POST['religion'];
   $religion = filter_var($religion, FILTER_SANITIZE_STRING);
   $occupation = $_POST['occupation'];
   $occupation = filter_var($occupation, FILTER_SANITIZE_STRING);
   $religion = $_POST['religion'];
   $religion = filter_var($religion, FILTER_SANITIZE_STRING);
   $company = $_POST['company'];
   $company = filter_var($company, FILTER_SANITIZE_STRING);
   $fb = $_POST['fb'];
   $fb = filter_var($fb, FILTER_SANITIZE_STRING);
   $ig = $_POST['ig'];
   $ig = filter_var($ig, FILTER_SANITIZE_STRING);
   $allergy = $_POST['allergy'];
   $allergy = filter_var($allergy, FILTER_SANITIZE_STRING);

   if ($religion === 'Lain-lain') {
      $religion = $_POST['otherReligion']; // Use the value from the text input
    } 

     // Set timezone to Kuala Lumpur
     date_default_timezone_set('Asia/Kuala_Lumpur');
     $currentDateTime = date('Y-m-d H:i:s');

   $select_user = $conn->prepare("SELECT * FROM `clients` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Emel telah digunakan!';
   }else{
      if($pass != $cpass){
         $message[] = 'Pengesahan kata laluan tidak sepadan!';
      }else{
$insert_user = $conn->prepare("INSERT INTO `clients`(name,ic, email, password,phoneno,datebirth,address,maritalstat,religion,occupation,company,fb,ig,allergy,datetimeregistered) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
         $insert_user->execute([$name,$ic,$email,$cpass,$phoneno,$datebirth,$address,$maritalstat,$religion,$occupation,$company,$fb,$ig, $allergy, $currentDateTime]);
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
    <label for="name">Nama Penuh:<span style="color: red;">*</span></label>
    <input type="text" id="name" name="name" style="text-transform:capitalize;" required placeholder="Masukkan nama anda" maxlength="70" class="box">
    
    <label for="email">Emel:<span style="color: red;">*</span></label>
    <input type="email" id="email" name="email" required placeholder="Masukkan emel anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="pass">Kata Laluan:<span style="color: red;">*</span></label>
    <input type="password" id="pass" name="pass" required placeholder="Masukkan kata laluan anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="cpass">Kata Laluan Pengesahan:<span style="color: red;">*</span></label>
    <input type="password" id="cpass" name="cpass" required placeholder="Masukkan kata laluan pengesahan anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="ic">Nombor Kad Pengenalan:<span style="color: red;">*</span></label>
    <input type="text" id="ic" name="ic"  required placeholder="ex : 020714104356 (tak perlu guna ' - ')" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="phoneno">Nombor Telefon:<span style="color: red;">*</span></label>
    <input type="text" id="phoneno" name="phoneno"  required placeholder="Masukkan nombor telefon" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="datebirth">Tarikh Lahir:<span style="color: red;">*</span></label>
    <input type="date" id="datebirth" name="datebirth"  required placeholder="Masukkan tarikh lahir anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="address">Alamat Kediaman:<span style="color: red;">*</span></label>
    <input type="text" id="address" name="address"  required placeholder="Masukkan alamat kediaman anda" maxlength="70" class="box" >
   
    <label for="maritalstat">Status Perkahwinan:<span style="color: red;">*</span></label>
    <input type="text" id="maritalstat" name="maritalstat"  required placeholder="Masukkan status Perkahwinan anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="religion">Agama:<span style="color: red;">*</span></label>
<select id="religion" name="religion" required class="box" onchange="showOtherField()">
  <option value="">--Masukkan Agama Anda--</option>
  <option value="Islam">Islam</option>
  <option value="Kristian">Kristian</option>
  <option value="Katolik">Katolik</option>
  <option value="Hindu">Hindu</option>
  <option value="Buddha">Buddha</option>
  <option value="Sikh">Sikh</option>
  <option value="Yahudi">Yahudi</option>
  <option value="Tiada">Tiada</option>
  <option value="Lain-lain">Lain-lain</option>
</select>
<!-- Initially hidden text input for specifying "Lain-lain" religion -->
<input type="text" id="otherReligion" name="otherReligion" style="display:none;" placeholder="Masukkan agama lain-lain anda" class="box">


    
    <label for="occupation">Pekerjaan:<span style="color: red;">*</span></label>
    <input type="text" id="occupation" name="occupation"  required placeholder="Masukkan jenis pekerjaan anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="company">Nama Syarikat:</label>
    <input type="text" id="company" name="company" placeholder="Masukkan nama syarikat anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="fb">Facebook Username:</label>
    <input type="text" id="fb" name="fb" placeholder="Masukkan username facebook anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="ig">Instagram Username:</label>
    <input type="text" id="ig" name="ig" placeholder="Masukkan username instagram anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   
    <label for="allergy">Alahan:<span style="color: red;">*</span></label>
    <input type="text" id="allergy" name="allergy"  required placeholder="Nyatakan Alahan anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    
    <input type="submit"  value="daftar" class="btn" name="submit">
       <p>Sudah ada Akaun?</p>
      <a href="user_login.php" class="option-btn">Log Masuk Sekarang</a>
   </form>

</section>

<script>
function showOtherField() {
  var religionSelect = document.getElementById('religion');
  var otherReligionInput = document.getElementById('otherReligion');
  // Show the text input when "Lain-lain" is selected
  if (religionSelect.value === 'Lain-lain') {
    otherReligionInput.style.display = '';
  } else {
    // Otherwise, hide it
    otherReligionInput.style.display = 'none';
  }
}
</script>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>


</body>
</html>