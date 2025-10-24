<?php
ob_start();
include '../components/connect.php';

session_start();

if (isset($_SESSION['staff_id'])) {
   $staff_id = $_SESSION['staff_id'];
   
} else {
   $staff_id = '';

}
if (!isset($admin_id)) {
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

   <?php

            $result = $conn->prepare("SELECT * FROM `admins` WHERE id=?");
            $result->execute([$admin_id]);



            if ($result->rowCount() > 0) {
               while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                  $name = $row['name'];
                 
               }
            }
            ?>
   <h3>Kemaskini Profil</h3>
            <br>
            <br>
            <label  for="name">Username :</label>
            <input type="text" name="name" required placeholder="ex : ilyas175" maxlength="100" class="box"
               value="<?php echo $name; ?>">

      <input type="submit" value="Kemaskini" class="btn" name="submit">
   </form>

   </section>


   <script src="../js/admin_script.js"></script>

</body>

</html>