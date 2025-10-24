<?php
ob_start();
include '../components/connect.php';

session_start();

if (isset($_SESSION['staff_id'])) {
   $staff_id = $_SESSION['staff_id'];

if (!isset($staff_id)) {
   header('location:login.php');
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $ic = $_POST['ic'];
   // $email = $_POST['email'];
   $phoneno = $_POST['phoneno'];
   $datebirth = $_POST['datebirth'];
   $address = $_POST['address'];


   $update = $conn->prepare("UPDATE `staffs` SET name = ?, ic = ?, phoneno = ? , datebirth= ? , address= ? WHERE id = ?");
   $update->execute([$name, $ic, $phoneno, $datebirth, $address, $staff_id]);
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

   <?php include '../components/staff-header.php'; ?>

   <section class="form-container">

      <form action="" method="post">

         <?php

         $result = $conn->prepare("SELECT * FROM `staffs` WHERE id=?");
         $result->execute([$staff_id]);
         if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

               $name = $row['name'];
               $ic = $row['ic'];
               $email = $row['email'];
               $phoneno = $row['phoneno'];
               $datebirth = $row['datebirth'];
               $address = $row['address'];
            }
         }
         ?>

         <h3>Kemaskini Profil</h3>
         <br>
         <br>
         <label for="name">Nama Penuh :</label>
         <input type="text" name="name" required placeholder="Masukkan nama penuh anda" maxlength="100" class="box" value="<?php echo $name; ?>">
         <!-- 
            <label  for="name">Emel :</label>
            <input type="text" name="email" required placeholder="Masukkan emel anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $email; ?>"> -->

         <label for="name">No. Kad Pengenalan :</label>
         <input type="text" name="ic" required placeholder="Masukkan no. kad pengenalan anda" maxlength="100" oninput="this.value = this.value.replace(/\s/g, '')" class="box" value="<?php echo $ic; ?>">

         <label for="name">No. Telefon :</label>
         <input type="text" name="phoneno" required placeholder="Masukkan no. telefon anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $phoneno; ?>">

         <label for="name">Tarikh Lahir :</label>
         <input type="date" name="datebirth" required placeholder="Masukkan tarikh lahir anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $datebirth; ?>">

         <label for="address">Alamat Kediaman:<span style="color: red;">*</span></label>
         <input value="<?php echo $address; ?>" type="text" id="address" name="address" required placeholder="Masukkan alamat kediaman anda" maxlength="70" class="box">


         <input type="submit" value="Kemaskini" class="btn" name="submit">
      </form>

   </section>


   <script src="../js/admin_script.js"></script>

</body>

</html>