<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin-login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
   $price = $_POST['price'];
   $price = htmlspecialchars(strip_tags(trim($price)), ENT_QUOTES, 'UTF-8');
   $details = $_POST['details'];
   $details = htmlspecialchars(strip_tags(trim($details)), ENT_QUOTES, 'UTF-8');

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = htmlspecialchars(strip_tags(trim($image_01)), ENT_QUOTES, 'UTF-8');
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Nama produk sudah digunakan!';
   }else{

   
      
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'Saiz imej terlalu besar!';
         }else{

            $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?)");
            $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03]);
      
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'Produk Berjaya Ditambah!';
         }

      

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
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

<section class="add-products">

   <h1 class="heading">Tambah Produk</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <label>Nama Produk : <span style="color: red;"></span></label>
            <input type="text" class="box" required maxlength="100" placeholder="Masukkan nama produk" name="name">
         </div>
         <div class="inputBox">
            <label>Harga Produk : <span style="color: red;"></span></label>
            <input type="text" class="box" required pattern="^\d*(\.\d+)?$"
            placeholder="Ex : 65/ 65.00 /65.50"
                  onkeypress="if(this.value.length == 10 && !this.value.includes('.')) return false;" name="price">         </div>
        <div class="inputBox">
            <label>Gambar Produk 01 : <span style="color: red;"></span></label>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <label>Gambar Produk 02 : <span style="color: red;"></span></label>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <label>Gambar Produk 03 : <span style="color: red;"></span></label>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
        <label>Deskripsi Produk : <span style="color: red;"></span></label>
            <textarea name="details" placeholder="Masukkan deskripsi produk" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="Tambah Produk" class="btn" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">Senarai Produk</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price">RM<span><?= $fetch_products['price']; ?></span></div>
      <div class="details"><span><?= $fetch_products['details']; ?></span></div>
      <div class="flex-btn">
         <a href="update-product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Kemaskini</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Buang Produk ini?');">Buang</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Tiada produk ditambah!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>