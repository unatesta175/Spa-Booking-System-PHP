<?php
// if (isset($message)) {
//    foreach ($message as $message) {
//       echo '
//          <div class="message">
//             <span>' . $message . '</span>
//             <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
//          </div>
//          ';
//    }
// }
?>

<header class="header">

   <section class="flex section-no-padding">
      <div style="display:flex;flex-direction: row;align-items: center;
      justify-content: center;">
         <div>
            <a href="index.php"><img class=" underline" style=" margin-top: 10px;
      margin-bottom: 10px;" width="auto" height="70" src="./images/lunara-new-logo.png" alt=""></a>
         </div>

      </div>


      <nav class="navbar flex">
         <div class="menu-item">
            <div class="dropdown-toggle-wrapper" style="display: flex; align-items: center; cursor: pointer;">
               <a href="service-package.php" class="underline">Rawatan & Terapi</a>
               <i class="fas fa-chevron-down"></i>
            </div>
            <div class="dropdown-content">
               <a href="sv-pakej-terapi-badan.php">Pakej Terapi Badan</a>
               <a href="sv-terapi-urutan.php">Terapi Urutan</a>
               <a href="sv-skrub.php">Skrub</a>
               <a href="sv-rawatan-muka.php">Rawatan Muka</a>
               <a href="sv-terapi-rendaman.php">Terapi Rendaman</a>
               <a href="sv-terapi-wap.php">Terapi Wap</a>
               <a href="sv-terapi-kaki.php">Terapi Kaki </a>
               <a href="sv-terapi-wax.php">Terapi Wax</a>
               <a href="sv-terapi-bekam.php">Terapi Bekam</a>
               <a href="sv-terapi-resdung.php">Terapi Sinus</a>
               <a href="sv-terapi-balutan.php">Terapi Balutan</a>
               <a href="sv-terapi-pemulihan.php">Terapi Pemulihan</a>
               <a href="sv-lain-lain.php">Lain-lain</a>

            </div>
         </div>
      
         <a href="shop.php" class="underline">Produk</a>
      
         <a href="orders.php" class="underline">Pesanan</a>
        
         
         <!-- <a href="shop.php">shop</a> -->
         <!-- <a href="contact.php">contact</a> -->

      </nav>

      <div class="icons">
         <?php
         $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $count_wishlist_items->execute([$user_id]);
         $total_wishlist_counts = $count_wishlist_items->rowCount();

         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search-page.php"><i class="fas fa-search"></i></a>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(
               <?= $total_wishlist_counts; ?>)
            </span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(
               <?= $total_cart_counts; ?>)
            </span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `clients` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p>
               <?= $fetch_profile["name"]; ?>
            </p>
            <a href="booking-record.php" class="btnx">Rekod Tempahan</a>
            <a href="update-profile.php" class="btnx">kemaskini profil</a>
            <a href="change-password.php" class="option-btn">ubah kata laluan</a>
            <div class="flex-btn">
               <a href="user-register.php" class="option-btn">daftar</a>
               <a href="user-login.php" class="option-btn">login</a>
            </div>
            <a href="javascript:void(0);" id="logoutButton" class="delete-btn">logout</a>
            <?php
         } else {
            ?>
            <p>Sila Daftar atau log masuk dahulu!</p>
            <div class="flex-btn">
               <a href="user-register.php" class="option-btn">Daftar</a>
               <a href="user-login.php" class="option-btn">login</a>
            </div>
            <?php
         }
         ?>


      </div>

   </section>

</header>