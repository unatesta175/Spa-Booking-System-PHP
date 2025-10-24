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
      margin-bottom: 10px;" width="auto" height="70" src="./images/kapas-new-logo.png" alt=""></a>
         </div>

      </div>


      <nav class="navbar flex">
         <div class="menu-item">
            <div class="dropdown-toggle-wrapper" style="display: flex; align-items: center; cursor: pointer;">
               <a href="service-package.php" class="underline">Rawatan & Pakej</a>
               <i class="fas fa-chevron-down"></i>
            </div>
            <div class="dropdown-content">
               <a href="sv-pakej-spa.php">Pakej Spa</a>
               <a href="sv-urutan-badan.php">Urutan Badan</a>
               <a href="sv-skrub.php">Skrub</a>
               <a href="sv-rawatan-facial.php">Rawatan Facial</a>
               <a href="sv-mandian.php">Mandian</a>
               <a href="sv-sauna.php">Sauna</a>
               <a href="sv-rawatan-kaki.php">Rawatan Kaki</a>
               <a href="sv-waxing.php">Waxing</a>
               <a href="sv-bekam-sunnah.php">Bekam Sunnah</a>
               <a href="sv-rawatan-resdung.php">Rawatan Resdung</a>
               <a href="sv-balutan-badan.php">Balutan Badan</a>
               <a href="sv-fisioterapi.php">Fisioterapi</a>
               <a href="sv-lain-lain.php">Lain-lain</a>

            </div>
         </div>
      
         <a href="shop.php" class="underline">Produk</a>
         <a href="giftcard-shop.php" class="underline">Gift Card</a>
        
         
         <!-- <a href="orders.php"class="underline">orders</a> -->
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