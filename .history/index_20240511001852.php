<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['login_success'])) {
   // Prepare JavaScript for showing the Sweet Alert
   $loginSuccessScript = "<script>
           window.onload = function() {
               Swal.fire({
                   title: 'Berjaya!',
                   text: 'Anda sudah berjaya log masuk!',
                   icon: 'success',
                   confirmButtonText: 'OK',
                   customClass: {
                       // Define a class for the SweetAlert popup
                       popup: 'my-custom-popup',
                       // Define a class for the SweetAlert title
                       title: 'my-custom-title',
                       // Define a class for the SweetAlert text
                       text: 'my-custom-text',
                       // Define a class for the SweetAlert confirm button
                       confirmButton: 'my-custom-confirm-button',
                       // Define a class for the SweetAlert cancel button
                       cancelButton: 'my-custom-cancel-button'
                   }
                  
               });
           }
         </script>";
   unset($_SESSION['login_success']); // Unset the flag
} else {
   $loginSuccessScript = "";
}


if(isset($_SESSION['logout_success'])) {
   // Prepare JavaScript for showing the Sweet Alert
   echo "<script>
           window.addEventListener('load', function() {
               Swal.fire({
                   title: 'Berjaya!',
                   text: 'Anda sudah berjaya Log keluar',
                   icon: 'success',
                   confirmButtonText: 'OK',
                   customClass: {
                       // Define a class for the SweetAlert popup
                       popup: 'my-custom-popup',
                       // Define a class for the SweetAlert title
                       title: 'my-custom-title',
                       // Define a class for the SweetAlert text
                       text: 'my-custom-text',
                       // Define a class for the SweetAlert confirm button
                       confirmButton: 'my-custom-confirm-button',
                       // Define a class for the SweetAlert cancel button
                       cancelButton: 'my-custom-cancel-button'
                   }
                
               });
           });
         </script>";
   unset($_SESSION['logout_success']); // Unset the flag to prevent the alert from showing again on refresh
}

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php
   include './components/functions.php';
   includeHeader();
   ?>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11"> -->
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
<style>


</style>
</head>
<body>
<?php echo $loginSuccessScript; ?>
   
<?php include 'components/user-header.php'; ?>

<div class="home-bg ">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/h1.png" alt="">
         </div>
         <div class="content">
            
            <h3>Rawatan & Pakej</h3>
            <br>
            <span>yang kami tawarkan di beauty spa</span><br>
            <a href="shop.php" class="btn">Lihat Pakej Sedia Ada</a>
         </div>
      </div>
      
      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/h2.png" alt="">
         </div>
         <div class="content">
            
            <h3>Pakej Promosi</h3>
            <br>
            <span>Rawatan harga rendah dalam masa yang terhad!</span> <br>
            
            <a href="shop.php" class="btn">Lihat Pakej Sedia Ada</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/h4.png" alt="">
         </div>
         <div class="content">
            
            <h3>Produk</h3>
            <br>
            <span>Dapatkan produk kami yang berkualiti!</span> <br>
            <a href="shop.php" class="btn">Lihat Produk sedia ada</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/h5.png" alt="">
         </div>
         <div class="content">
            
            <h3>Kapas Voucher Gift</h3>
            <br>
            <span>Penghargaan kepada insan tercinta!</span> <br>
            <a href="shop.php" class="btn">Dapatkan Spa Voucher</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/h5.png" alt="">
         </div>
         <div class="content">
            
            <h3>Pakej Kredit</h3>
            <br>
        <span>Dapatkan bonus kredit dengan pakej kredit kami</span> <br>
            <a href="shop.php" class="btn">Tambah credit spa</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>


<section class="category">

   <h1 class="heading"></h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=laptop" class="swiper-slide slide">
   <i class="fa fa-sliders fa-5x fa-fw"></i>
      <h3>TEMPAHAN</h3>
   </a>

   <a href="update-profile.php" class="swiper-slide slide">
   <i class="fa fa-person fa-5x fa-fw"></i>
      <h3>PROFIL</h3>
   </a>

   <a href="change-password.php" class="swiper-slide slide">
      
      <i class="fa fa-eject fa-fw fa-5x"></i><h3>UBAH KATA LALUAN</h3>
   </a>

   <a href="components/user-logout.php" class="swiper-slide slide">
      
      <i class="fa fa-key fa-fw fa-5x"></i><h3>LOG KELUAR</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Pakej Spa Sedia ada</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `services`LIMIT 10 "); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="duration" value="<?= $fetch_product['duration']+30; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <!-- <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button> -->
      <!-- <a href="quick-view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a> -->
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>RM</span><?= $fetch_product['price']; ?><span> Per Pax</span></div>
      </div>
      <input type="submit" value="Tempah Sekarang" class="btn" name="add_to_book">
      
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no services added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>



<section class="home-products">

   <h1 class="heading">Pakej kredit </h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `credits`"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick-view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
      <div class="price"><span>RM</span><?= $fetch_product['price']; ?><span></span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Giftcard Sedia ada</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `giftcards` "); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick-view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>RM</span><?= $fetch_product['price']; ?><span></span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>



<section class="home-products">

   <h1 class="heading">Produk Kami</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick-view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
      <div class="price"><span>RM</span><?= $fetch_product['price']; ?><span></span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Promo Sedia ada</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick-view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>RM</span><?= $fetch_product['price']; ?><span></span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>







<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/sweetalert2.all.min.js"></script>
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 4,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});


window.addEventListener('scroll', function() {
  var parallax = document.querySelector('.home-bg');
  var scrollPosition = window.pageYOffset;

  parallax.style.backgroundPositionY = scrollPosition * 0.5 + 'px';
});


</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fadeInUp');
        entry.target.classList.remove('hidden'); // Remove hidden class to make it visible
        observer.unobserve(entry.target); // Optional: Stop observing once animated
      }
    });
  }, {
    rootMargin: '0px',
    threshold: 0.1 // Adjust based on when you want the animation to start
  });

  // Observe all forms with the 'box' class
  document.querySelectorAll('.category').forEach(form => {
    observer.observe(form);
  });
  document.querySelectorAll('.home-products').forEach(form => {
    observer.observe(form);
  });
});
</script>


</body>
</html>