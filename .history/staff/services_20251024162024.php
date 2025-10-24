<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin-login.php');
}
;

if (isset($_POST['add_service'])) {

   $name = $_POST['name'];
   $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
   $price = $_POST['price'];
   $price = htmlspecialchars(strip_tags(trim($price)), ENT_QUOTES, 'UTF-8');
   $details = $_POST['details'];
   $details = htmlspecialchars(strip_tags(trim($details)), ENT_QUOTES, 'UTF-8');
   $duration = $_POST['duration'];
   $duration = htmlspecialchars(strip_tags(trim($duration)), ENT_QUOTES, 'UTF-8');
   $category = $_POST['category'];
   $category = htmlspecialchars(strip_tags(trim($category)), ENT_QUOTES, 'UTF-8');
   $type = $_POST['type'];
   $type = htmlspecialchars(strip_tags(trim($type)), ENT_QUOTES, 'UTF-8');

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/' . $image_01;

   // $image_02 = $_FILES['image_02']['name'];
   // $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   // $image_size_02 = $_FILES['image_02']['size'];
   // $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   // $image_folder_02 = '../uploaded_img/' . $image_02;

   // $image_03 = $_FILES['image_03']['name'];
   // $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   // $image_size_03 = $_FILES['image_03']['size'];
   // $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   // $image_folder_03 = '../uploaded_img/' . $image_03;

   if (!empty($_POST['extradetails'])) {
      $extradetails = $_POST['extradetails'];
      $sanitizedDetails = array();

      foreach ($extradetails as $item) {
         $sanitizedDetails[] = filter_var($item, FILTER_SANITIZE_STRING);
      }

      $extradetailsJson = json_encode($sanitizedDetails);
      // Now use $extradetailsJson to store in the database
   } else {
      // Assign an empty string to $extradetailsJson if $_POST['extradetails'] is empty or not set

      $extradetailsJson = null;
   }

   $select_products = $conn->prepare("SELECT * FROM `services` WHERE name = ?");
   $select_products->execute([$name]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'Nama pakej rawatan sudah digunakan!';
   } else {



      if ($image_size_01 > 2000000) {
         $message[] = 'Saiz imej terlalu besar!';
      } else {
         $insert_products = $conn->prepare("INSERT INTO `services`(name, details , extradetails , price, duration , category , type, image_01) VALUES(?,?,?,?,?,?,?,?)");
         $insert_products->execute([$name, $details, $extradetailsJson, $price, $duration, $category, $type, $image_01]);

         move_uploaded_file($image_tmp_name_01, $image_folder_01);
         // move_uploaded_file($image_tmp_name_02, $image_folder_02);
         // move_uploaded_file($image_tmp_name_03, $image_folder_03);
         $message[] = 'Pakej baru berjaya ditambah!';
      }



   }

}
;

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
   $delete_product = $conn->prepare("DELETE FROM `services` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   // $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   // $delete_cart->execute([$delete_id]);
   // $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   // $delete_wishlist->execute([$delete_id]);
   header('location:services.php');
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

      <h1 class="heading">Tambah Pakej Rawatan </h1>

      <form action="" method="post" enctype="multipart/form-data">
         <div class="flex">
            <div class="inputBox">
               <label>Nama Pakej Rawatan : <span style="color: red;">*</span></label>
               <input type="text" class="box" required maxlength="100" placeholder="Masukkan nama pakej rawatan"
                  name="name">
            </div>
            <div class="inputBox">
               <label>Harga Pakej Rawatan : (RM)<span style="color: red;">*</span></label>
               <input type="text" class="box" required pattern="^\d*(\.\d+)?$" placeholder="Ex : 65/ 65.00 /65.50"
                  onkeypress="if(this.value.length == 10 && !this.value.includes('.')) return false;" name="price">
            </div>


            <div class="inputBox">
               <label for="duration">Tempoh Masa Pakej Rawatan: <span style="color: red;">*</span></label>
               <select id="duration" name="duration" class="box" required>
                  <option value="" disabled selected>Masukkan tempoh masa pakej rawatan</option>
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
            </div>




            <div class="inputBox">
               <label for="category">Kategori Pakej Rawatan : <span style="color: red;">*</span></label>
               <select id="category" name="category" class="box" required>
                  <option value="" disabled selected>Pilih kategori pakej</option>
                  <option value="Biasa">Biasa</option>
                  <option value="Promosi">Promosi</option>
               </select>
            </div>

            <div class="inputBox">
               <label for="type">Jenis Pakej Rawatan : <span style="color: red;">*</span></label>
               <select id="type" name="type" class="box" required>
                  <option value="" disabled selected>Pilih jenis pakej</option>
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
            </div>

            <div class="inputBox">
               <label>Deskripsi Pakej Rawatan <span style="color: red;">*</span></label>
               <textarea name="details" placeholder="Masukkan deskripsi pakej " class="box" maxlength="500"
                  cols="30" rows="10"></textarea>
            </div>

            <div class="inputBox" id="inputContainer">
               <label>Senarai proses terapi / produk / peralatan terlibat : </label>
               <input type="text" class="box" placeholder="1# item" name="extradetails[]">
            </div>
            <button type="button" class="option-btn" onclick="addInputField()">Tambah item</button>
            <button type="button" class="option-btn" onclick="removeInputField()">Buang Item</button>

            <div class="inputBox">
               <label>Gambar Produk <span style="color: red;">*</span></label>
               <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
            <!-- <div class="inputBox">
               <label>image 02 <span style="color: red;">*</span></label>
               <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
            <div class="inputBox">
               <label>image 03 <span style="color: red;">*</span></label>
               <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div> -->
         </div>

         <input type="submit" value="Tambah Pakej" class="btn" name="add_service">
      </form>

   </section>

   <section class="show-products">

      <h1 class="heading">Senarai Pakej Rawatan</h1>

      <div class="box-container">

         <?php

 $select_products = $conn->prepare("SELECT * FROM `services` ");
 $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {

               $extradetailsArray = json_decode($fetch_products['extradetails'], true);
               // Initialize $listHtml as an empty string
               $listHtml = "";
               if (is_array($extradetailsArray) && !empty($extradetailsArray)) {
                  // Start the list only if there are items
                  $listHtml .= "<ul>";
                  foreach ($extradetailsArray as $item) {
                     $listHtml .= "<li>" . htmlspecialchars($item) . "</li>";
                  }
                  // Close the list
                  $listHtml .= "</ul>";

               }
               // If there are no items, $listHtml remains an empty string and won't display an empty list
         
               // Use $listHtml in your HTML output as needed
               ?>
               <div class="box">
                  <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                  <div class="name">
                     <?= $fetch_products['name']; ?>
                  </div>
                  <div class="price">RM<span>
                        <?= intval($fetch_products['price']); ?> Per Pax
                     </span></div>

                  <div class="duration">Masa : <span>
                        <?= formatDuration($fetch_products['duration']); ?>
                     </span></div>
                  <div class="typecategory">Jenis : <span style="color:var(--main-color);">
                        <?= $fetch_products['type']; ?>
                     </span></div>
                  <div class="typecategory">Kategori : <span style="color:var(--main-color);">
                        <?= $fetch_products['category']; ?>
                     </span></div>

                  <div class="details"><span>
                        <?= $fetch_products['details']; ?>
                     </span></div>
                  


                  <div class="extradetails"><span>
                        <?php
                        if ($listHtml == "<ul><li></li></ul>") {
                           // If $status is true, display $listHtml
                           echo "";
                        } else {
                           // If $status is false, display another variable's value, let's say $alternativeValue
                           echo $listHtml;
                        }
                        ?>
                     </span></div>
                  <div class="flex-btn">




                     <a href="update-service.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Kemaskini</a>
                     <a href="services.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn"
                        onclick="return confirm('Buang pakej ini?');">Buang</a>
                  </div>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">Tiada pakej ditambah!</p>';
         }
         ?>

      </div>

   </section>




   <script>

      var itemCount = 2;

      function addInputField() {
         var container = document.getElementById("inputContainer");

         var input = document.createElement("input");
         input.type = "text";
         input.name = "extradetails[]";
         input.placeholder = itemCount + "# item";
         input.className = "box";
         input.setAttribute('id', 'item' + itemCount); // Set an ID for each item for easy removal

         container.appendChild(input);

         var br = document.createElement("br");
         br.setAttribute('id', 'br' + itemCount); // Set an ID for each <br> for easy removal
         container.appendChild(br);

         itemCount++;
      }

      function removeInputField() {
         if (itemCount > 2) { // Ensure we don't remove the original input field
            itemCount--; // Decrement itemCount because we're removing the last item

            // Remove the input field
            var inputToRemove = document.getElementById('item' + itemCount);
            if (inputToRemove) {
               inputToRemove.remove();
            }

            // Remove the <br> associated with the input field
            var brToRemove = document.getElementById('br' + itemCount);
            if (brToRemove) {
               brToRemove.remove();
            }
         }
      }
   </script>


   <script src="../js/admin_script.js"></script>

</body>

</html>