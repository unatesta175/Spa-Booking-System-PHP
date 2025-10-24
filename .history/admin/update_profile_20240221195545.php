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

   <?php

            $result = $conn->prepare("SELECT * FROM `clients` WHERE id=?");
            $result->execute([$user_id]);



            if ($result->rowCount() > 0) {
               while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                  $name = $row['name'];
                  $ic = $row['ic'];
                  $email = $row['email'];
                  $phoneno = $row['phoneno'];
                  $datebirth = $row['datebirth'];
                  $address = $row['address'];
                  $maritalstat = $row['maritalstat'];
                  $religion = $row['religion'];
                  $occupation = $row['occupation'];
                  $company = $row['company'];
                  $fb = $row['fb'];
                  $ig = $row['ig'];
                  $allergy = $row['allergy'];
               }
            }
            ?>
   <h3>Kemaskini Profil</h3>
            <br>
            <br>
            <label  for="name">Nama Penuh :</label>
            <input type="text" name="name" required placeholder="Masukkan nama penuh anda" maxlength="100" class="box"
               value="<?php echo $name; ?>">

      <input type="submit" value="Kemaskini" class="btn" name="submit">
   </form>

   </section>


   <script src="../js/admin_script.js"></script>

</body>

</html>