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
         <a href="../staff/dashboard.php">home</a>
         <a href="../staff/booking-record.php">tempahan</a>
         <a href="../staff/services.php">rawatan</a>
         <a href="../staff/products.php">produk</a>
         <a href="../staff/giftcards.php">giftcard</a>
         <a href="../staff/credits.php">credit</a>
         <a href="../staff/placed-orders.php">orders</a>
         <a href="../staff/admin-accounts.php">admin</a>
         <a href="../admin/client-accounts.php">pengguna</a>
         <a href="../admin/staff-accounts.php">staff</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `staffs` WHERE id = ?");
            $select_profile->execute([$staff_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="../staff/update-profile.php" class="btnx">kemaskini profil</a>
         <a href="../staff/change-password.php" class="option-btn">ubah kata laluan</a>
         <div class="flex-btn">
           
            <a href="../staff/login.php" class="option-btn">login</a>
            
         </div>
         <a href="javascript:void(0);" id="logoutButton" class="delete-btn">logout</a>

      </div>

   </section>

</header>