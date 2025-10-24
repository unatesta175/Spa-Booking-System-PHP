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
   $duration = $_POST['duration'];
   $duration = htmlspecialchars(strip_tags(trim($duration)), ENT_QUOTES, 'UTF-8');
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);

   $extradetails = isset($_POST['extradetails']) ? $_POST['extradetails'] : [];
    $extradetailsJson = json_encode($extradetails);

    $update_product = $conn->prepare("UPDATE `services` SET name = ?, price = ?, details = ?, duration = ?, category = ?, type = ?, extradetails = ? WHERE id = ?");
    $update_product->execute([$name, $price, $details, $duration, $category, $type, $extradetailsJson, $pid]);


   $message[] = 'Pakej Rawatan Berjaya Dikemaskini!';

   $old_image_01 = $_POST['old_image_01'];
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   if (!empty($image_01)) {
      if ($image_size_01 > 2000000) {
          $message[] = 'image size is too large!';
      } else {
          // Generate a new filename for the uploaded image to avoid caching issues
          $image_ext = pathinfo($image_01, PATHINFO_EXTENSION); // Extract the image extension
          $new_image_name = "image_" . time() . "." . $image_ext; // Create a new image name based on current timestamp
          $image_folder_01 = '../uploaded_img/' . $new_image_name; // Update the image folder path with the new image name
  
          $update_image_01 = $conn->prepare("UPDATE `services` SET image_01 = ? WHERE id = ?");
          $update_image_01->execute([$new_image_name, $pid]);
          move_uploaded_file($image_tmp_name_01, $image_folder_01);
  
          // Check if the old image exists before attempting to delete it
          $old_image_path = '../uploaded_img/' . $old_image_01;
          if (!empty($old_image_01) && file_exists($old_image_path)) {
              unlink($old_image_path);
          }
  
          $message[] = 'image 01 updated successfully!';
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

      <h1 class="heading">Kemaskini Pakej Rawatan</h1>

      <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
      $select_products->execute([$update_id]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            // Decode the JSON to an array
            $extraDetails = json_decode($fetch_products['extradetails'], true);

            
            ?>
            <form action="" method="post" enctype="multipart/form-data">
               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
               <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">


               <div class="image-container">
                  <div class="main-image">
                     <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                  </div>

               </div>
               <span>Kemaskini nama pakej rawatan</span>
               <input type="text" name="name" required class="box" maxlength="100" placeholder="enter product name"
                  value="<?= $fetch_products['name']; ?>">

               <span>Kemaskini harga pakej rawatan ( RM )</span>
               <input value="<?= $fetch_products['price']; ?>" type="text" class="box" required pattern="^\d*(\.\d+)?$" placeholder="Ex : 65/ 65.00 /65.50"
                  onkeypress="if(this.value.length == 10 && !this.value.includes('.')) return false;" name="price">
               

               <span for="duration">Kemaskini tempoh masa pakej rawatan: </span>
               <select id="duration" name="duration" class="box" required>
                  <option value="<?= $fetch_products['duration']; ?>" selected>
                     <?= formatDuration($fetch_products['duration']); ?>
                  </option>
                  <option value="15">15 minit</option> <!-- Represents 15 minutes -->
                  <option value="20">20 minit</option>
                  <option value="30">30 minit</option>
                  <option value="40">40 minit</option>
                  <option value="45">45 minit</option> <!-- Represents 45 minutes -->
                  <option value="60">1 jam </option> <!-- Represents 60 minutes (1 hour) -->
                  <option value="75">1 jam 15 minit</option> <!-- Represents 75 minutes (1 hour 15 minutes) -->
                  <option value="90">1 jam 30 minit</option>
                  <option value="105">1 jam 45 minit</option>
                  <option value="120">2 jam</option>
                  <option value="135">2 jam 15 minit</option>
                  <option value="150">2 jam 30 minit</option>
                  <option value="165">2 jam 45 minit</option>
                  <option value="180">3 jam</option>
                  <option value="195">3 jam 15 minit</option>
                  <option value="210">3 jam 30 minit</option>
                  <option value="225">3 jam 45 minit</option>
                  <option value="240">4 jam</option>
                  <option value="255">4 jam 15 minit</option>
                  <option value="270">4 jam 30 minit</option>
                  <option value="285">4 jam 45 minit</option>
                  <option value="300">5 jam</option>
                  <option value="315">5 jam 15 minit</option>
                  <option value="330">5 jam 30 minit</option>
                  <option value="345">5 jam 45 minit</option>
                  <option value="360">6 jam</option> <!-- Represents 360 minutes (6 hours) -->
               </select>

               <span for="category">Kemaskini kategori pakej rawatan : </span>
               <select id="category" name="category" class="box" required>
                  <option value="<?= $fetch_products['category']; ?>" selected>
                     <?= $fetch_products['category']; ?>
                  </option>
                  <option value="Biasa">Biasa</option>
                  <option value="Promosi">Promosi</option>
               </select>

               <span for="type">Kemaskini jenis pakej rawatan :</span>
               <select id="type" name="type" class="box" required>
                  <option value="<?= $fetch_products['type']; ?>" selected>
                     <?= $fetch_products['type']; ?>
                  </option>
                  <option value="Pakej Spa">Pakej Spa</option>
                  <option value="Urutan Badan">Urutan Badan</option>
                  <option value="Skrub">Skrub</option>
                  <option value="Rawatan Facial">Rawatan Facial</option>
                  <option value="Mandian">Mandian</option>
                  <option value="Sauna">Sauna</option>
                  <option value="Rawatan Kaki">Rawatan Kaki</option>
                  <option value="Waxing">Waxing</option>
                  <option value="Bekam Sunnah">Bekam Sunnah</option>
                  <option value="Rawatan Resdung">Rawatan Resdung</option>
                  <option value="Balutan Badan">Balutan Badan</option>
                  <option value="Fisioterapi">Fisioterapi</option>
                  <option value="Lain-lain">Lain-lain</option>
               </select>
               <span>Kemaskini deskripsi pakej rawatan</span>
               <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>

              <!-- Dynamic inputs for extradetails -->
              <span>Kemaskini senarai proses terapi / produk / peralatan yang terlibat : </span>
        <div id="inputContainer">
            <?php if (is_array($extraDetails) && count($extraDetails) > 0) : ?>
                <?php foreach ($extraDetails as $index => $detail) : ?>
                    <div class="inputBox">
                        <input type="text" class="box" value="<?= htmlspecialchars($detail) ?>" placeholder="<?= $index + 1 ?># item" name="extradetails[]">
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="inputBox">
                    <input type="text" class="box" placeholder="1# item" name="extradetails[]">
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="option-btn" onclick="addInputField()">Tambah item</button>
        <button type="button" class="option-btn" onclick="removeInputField()">Buang Item</button>
        

               <span>Kemaskini Gambar Produk : </span>
               <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
               <div class="flex-btn">
                  <input type="submit" name="update" class="btn" value="Kemaskini">
                  <a href="services.php" class="option-btn">Kembali</a>
               </div>
            </form>

            <?php
         }
      } else {
         echo '<p class="empty">no product found!</p>';
      }
      ?>

   </section>




   <script>
 var itemCount = <?= isset($extraDetails) ? count($extraDetails) + 1 : 2 ?>;
    function addInputField() {
        var container = document.getElementById("inputContainer");
        var input = document.createElement("input");
        input.type = "text";
        input.name = "extradetails[]";
        input.placeholder = itemCount + "# item";
        input.className = "box";
        container.appendChild(input);
        itemCount++;
    }
    function removeInputField() {
        if (itemCount > 2) {
            var inputElements = document.querySelectorAll("#inputContainer .box");
            if (inputElements.length > 1) {
                inputElements[inputElements.length - 1].remove();
                itemCount--;
            }
        }
    }
   </script>








   <script src="../js/admin_script.js"></script>

</body>

</html>