<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">

   <section class="flex">

      <a href="../admin/dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="../admin/dashboard.php">home</a>
         <a href="../admin/booking-record.php">tempahan</a>
         <a href="../admin/services.php">rawatan</a>
         <a href="../admin/products.php">produk</a>
         <a href="../admin/giftcards.php">giftcard</a>
         <a href="../admin/credits.php">credit</a>
         <a href="../admin/placed-orders.php">orders</a>
         <a href="../admin/admin-accounts.php">admin</a>
         <a href="../admin/client-accounts.php">pengguna</a>
         <a href="../admin/staff-accounts.php">staff</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="../admin/update-profile.php" class="btns">kemaskini profil</a>
         <a href="../admin/change-password.php" class="option-btn">ubah kata laluan</a>
         <div class="flex-btn">
            <a href="../admin/admin-accounts.php" class="option-btn">daftar</a>
            <a href="../admin/admin-login.php" class="option-btn">login</a>
            
         </div>
         <a href="javascript:void(0);" id="logoutButton" class="delete-btn">logout</a>

      </div>

   </section>

</header>