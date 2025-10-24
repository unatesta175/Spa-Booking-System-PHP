<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user-header.php'; ?>

   <div class="service-bg">

      <section class="service">

         <div class="swiper service-slider">

            <div class="swiper-wrapper">

               <div class="swiper-slide slide" style="height:40rem;">

                  <div class="content">

                     <h3>Rawatan & Pakej</h3>
                     <br>
                     <span>yang kami tawarkan di beauty spa</span>
                     <br>
                     <a href="#Main" class="btn">Lihat Pakej Sedia Ada</a>
                     <br>
                  </div>
               </div>


            </div>



         </div>

      </section>

   </div>



   <section id="Main" class="service-category">

      <h1 class="heading"  style="text-transform: capitalize !important;">pakej sedia ada</h1>



      <form action="" method="post" class="box ">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc1.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Pakej Spa</div>

               <div class="details">Serlahkan aura kewanitaan anda dengan pakej spa esklusif Kapas Beauty. Hilangkan
                  toksin dari badan, bersihkan kulit dan kembalikan kecantikan dalaman anda dengan pakej spa menyeluruh.
               </div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Bakal Pengantin</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Golongan Berumur</li>
                     <li>Wanita Bekerjaya</li>
                     <li>Suri Rumah</li>

                  </ul>
               </div>
               <div class="flex-btn">
               <a href="sv-pakej-spa.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc2.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Urutan Badan</div>

               <div class="details">Bawa minda dan badan anda ke dalam suasana ketenangan serta aktifkan proses
                  penyembuhan pada masa yang sama. Kesejahteraan umum anda terjamin dalam jagaan kami. </div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Wanita Aktif</li>
                     <li>Golongan Berumur</li>
                     <li>Suri Rumah</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Wanita Bekerjaya</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-urutan-badan.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc4.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Skrub</div>

               <div class="details">Rutin kecantikan Mesir sejak zaman dulu lagi untuk membuang sel-sel kulit mati dan
                  kotoran daripada lapisan kulit anda. Skrub membantu memberi lapisan kelembapan kulit dan lapisan
                  minyak badan baharu dan bersih. </div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Wanita Aktif</li>
                     <li>Bakal Pengantin</li>
                     <li>Suri Rumah</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Wanita Bekerjaya</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-skrub.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc5.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Rawatan Facial</div>

               <div class="details">Tampak lebih segar dan bertenaga. Pilih pakej rawatan wajah yang bersesuain untuk
                  membantu anda menyelesaikan masalah kulit muka. </div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Masalah Jerawat</li>

                     <li>Tona Kulit Tidak Sekata</li>
                     <li>Kulit Kusam</li>
                     <li>Kulit Berminyak</li>
                     <li>Blackhead & Whitehead Berlebihan</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-rawatan-facial.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc6.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Mandian</div>

               <div class="details">Tenangkan otot anda, bersihkan kulit dan tenangkan minda anda dengan pakej-pakej
                  mandian Kapas</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Wanita Aktif</li>
                     <li>Golongan Berumur</li>
                     <li>Suri Rumah</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Wanita Bekerjaya</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-mandian.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>


      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc7.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Sauna</div>

               <div class="details">Tarik nafas secara perlahan dan rehatkan otot anda, dari kepala hingga ke kaki di
                  sauna Kapas. Sauna juga sememangnya terbukti dalam membantu proses pengurangan berat badan anda.</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Wanita Aktif</li>
                     <li>Golongan Berumur</li>
                     <li>Suri Rumah</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Wanita Bekerjaya</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-sauna.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc8.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Rawatan Kaki</div>

               <div class="details">Lepaskan segala ketegangan anda dengan urutan kaki yang melegakan dan selesa.
                  Bayangkan perasaan yang memuaskan dan lega selepas kami lepaskan titik-titik sakit di kaki anda.
                  Itulah jaminan Kapas.</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Wanita Aktif</li>
                     <li>Wanita Bekerjaya</li>
                     <li>Tumit Merekah</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Suri Rumah</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-rawatan-kaki.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc9.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Waxing</div>

               <div class="details">Dapatkan kulit yang lebih halus dan lembut, pertumbuhan bulu yang lebih nipis dan
                  lebih halus, serta tiada lagi pertumbuhan bulu di tempat yang tidak diingini.</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Wanita Aktif</li>
                     <li>Wanita Bekerjaya</li>
                     <li>Kulit Sensitif</li>
                     <li>Bakal Pengantin</li>
                     <li>Suri Rumah</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-waxing.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc10.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Bekam Sunnah
               </div>

               <div class="details">Amalan yang diamalkan oleh Rasullulah S.A.W. sebagai kaedah perubatan dan pengubatan
                  penyakit.</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Pesakit Gout</li>
                     <li>Kekejangan Otot</li>
                     <li>Migrain</li>
                     <li>Pesakit Diabetes</li>
                     <li>Detoksifikasi Darah</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-bekam-sunnah.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc11.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Rawatan Resdung
               </div>

               <div class="details">Masalah resdung membuatkan anda berasa tidak selesa dan hilang mood. Jangan risau,
                  anda akan lega setelah kami selesaikan semuanya untuk anda!</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Bersin Tanpa Henti</li>
                     <li>Sakit / Tekanan Muka</li>
                     <li>Alahan Sinus</li>

                     <li>Hidung Tersumbat</li>
                     
                     <li>Bengkak Di Sekitar Mata & Hidung</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-rawatan-resdung.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc12.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Balutan Badan
               </div>

               <div class="details">Hilangkan toksin dari badan, baiki kontur badan anda buat sementara waktu, buang
                  kulit mati, pelembapan intensif dan relaksasi anda dengan pakej balutan badan menyeluruh.</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Bakal Pengantin</li>
                     <li>Golongan Berumur</li>
                     <li>Suri Rumah</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Wanita Bekerjaya</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-balutan-badan.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>


      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc13.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Fisioterapi
               </div>

               <div class="details">Redakan kesakitan dan dapatkan kembali fleksibiliti anda untuk meluangkan masa yang
                  lebih berkualiti bersama orang tersayang.</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul>
                     <li>Sakit Sendi</li>
                     <li>Mobiliti Terhad</li>
                     <li>Pesakit Arthritis</li>
                     <li>Pesakit Slip Disc</li>
                     <li>Kecederaan Dari Bersukan</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-fisioterapi.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>

      <form action="" method="post" class="box">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="images/sc14.png" alt="">
               </div>

            </div>
            <div class="content">
               <div class="name">Lain-lain
               </div>

               <div class="details">Walaupun nampak kecil, namun impaknya besar. Pilih pakej – pakej eyelash perming dan
                  manikur/pedikur untuk kuku anda.</div>

               <div class="flex">
                  <div class="title">
                     SESUAI UNTUK
                  </div>
               </div>
               <div class="flex">
                  <ul >
                     <li>Bakal Pengantin</li>
                     <li>Golongan Berumur
                     </li>
                     <li>Suri Rumah</li>
                     <li>Ibu Lepas Bersalin</li>
                     <li>Wanita Bekerjaya</li>

                  </ul>
               </div>
               <div class="flex-btn">
                 <a href="sv-lain-lain.php" class="btn">Lihat Pakej Sedia Ada</a>

               </div>
            </div>
         </div>
      </form>
   </section>
   <!-- <section class="services">

   <h1 class="heading" style="text-transform: capitalize !important;">pakej sedia ada</h1>

   <div class="box-container">

   <?php
   $select_products = $conn->prepare("SELECT * FROM `services`");
   $select_products->execute();
   if ($select_products->rowCount() > 0) {
      while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
         ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick-view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   } else {
      echo '<p class="empty">no products found!</p>';
   }
   ?>

   </div>

</section> -->









   <script>

      window.addEventListener('scroll', function () {
         var parallax = document.querySelector('.other-bg');
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
  document.querySelectorAll('form').forEach(form => {
    observer.observe(form);
  });
});
</script>


   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>
   <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Clear all items in the local storage
            localStorage.clear();

            // Other initialization code...
        });
    </script>
</body>

</html>