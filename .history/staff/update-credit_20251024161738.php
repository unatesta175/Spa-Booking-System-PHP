<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin-login.php');
}

if (isset($_POST['update'])) {

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
   $price = $_POST['price'];
   $price = htmlspecialchars(strip_tags(trim($price)), ENT_QUOTES, 'UTF-8');
   $details = $_POST['details'];
   $details = htmlspecialchars(strip_tags(trim($details)), ENT_QUOTES, 'UTF-8');

   $update_product = $conn->prepare("UPDATE `credits` SET name = ?, price = ?, details = ? WHERE id = ?");
   $update_product->execute([$name, $price, $details, $pid]);

   $message[] = 'Pakej Kredit berjaya dikemaskini!';

   $old_image_01 = $_POST['old_image_01'];
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = htmlspecialchars(strip_tags(trim($image_01)), ENT_QUOTES, 'UTF-8');
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/' . $image_01;

   if (!empty($image_01)) {
      if ($image_size_01 > 2000000) {
         $message[] = 'Saiz imej terlalu besar!';
      } else {
         $update_image_01 = $conn->prepare("UPDATE `credits` SET image_01 = ? WHERE id = ?");
         $update_image_01->execute([$image_01, $pid]);
         move_uploaded_file($image_tmp_name_01, $image_folder_01);
         if (!empty($old_image_01) && file_exists('../uploaded_img/' . $old_image_01)) {
            unlink('../uploaded_img/' . $old_image_01);
         }
         $message[] = 'Gambar Pakej Kredit 01 berjaya dikemaskini!';
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

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="update-product">

      <h1 class="heading">Kemaskini Pakej Kredit</h1>

      <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `credits` WHERE id = ?");
      $select_products->execute([$update_id]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="post" enctype="multipart/form-data">
               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
               <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
              
               <div class="image-container">
                  <div class="main-image">
                     <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                  </div>
                  
               </div>
               <span>Kemaskini nama pakej kredit : </span>
               <input type="text" name="name" required class="box" maxlength="100" placeholder="Masukkan nama Pakej Kredit"
                  value="<?= $fetch_products['name']; ?>">
               <span>Kemaskini harga pakej kredit : (RM) </span>
               <input type="text" class="box" required pattern="^\d*(\.\d+)?$"
            placeholder="Ex : 65/ 65.00 /65.50"
                  onkeypress="if(this.value.length == 10 && !this.value.includes('.')) return false;" name="price" value="<?= $fetch_products['price']; ?>"> 

              
               <span>Kemaskini deskripsi pakej kredit : </span>
               <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
               <span>Kemaskini gambar pakej kredit 01 : </span>
               <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
              
               <div class="flex-btn">
                  <input type="submit" name="update" class="btn" value="Kemaskini">
                  <a href="credits.php" class="option-btn">Kembali</a>
               </div>
            </form>

            <?php
         }
      } else {
         echo '<p class="empty">Tiada Pakej Kredit!</p>';
      }
      ?>

   </section>












   <script src="../js/admin_script.js"></script>

</body>

</html>