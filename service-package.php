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

                     <h3>Rawatan & Terapi</h3>
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
               <div class="name">Pakej Terapi Badan</div>

               <div class="details">Alami transformasi menyeluruh dengan rangkaian rawatan lengkap kami yang menggabungkan urutan, penjagaan wajah dan mandian terapeutik. Setiap sesi direka untuk memulihkan kesegaran tubuh dan meningkatkan kecantikan semula jadi anda.
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
               <a href="sv-pakej-terapi-badan.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Urutan</div>

               <div class="details">Rasai keharmonian jiwa dan raga melalui sentuhan terapi yang lembut namun berkesan. Teknik urutan kami membantu merangsang keupayaan pemulihan dalaman tubuh dan memberikan ketenangan mental yang mendalam.
               </div>

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
                 <a href="sv-terapi-urutan.php" class="btn">Lihat Pakej Sedia Ada</a>

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

               <div class="details">Singkirkan lapisan kulit kusam dan tidak sihat dengan rawatan lulur tradisional kami. Kulit anda akan terasa lebih lembut, bersih dan bercahaya dengan kelembapan yang diperbaharui untuk penampilan yang lebih menawan.
               </div>

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
               <div class="name">Rawatan Muka</div>

               <div class="details">Manjakan wajah anda dengan penjagaan profesional yang disesuaikan mengikut keperluan kulit. Setiap rawatan membantu mengatasi masalah kulit spesifik sambil menonjolkan kecantikan dan kesihatan kulit anda.
               </div>

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
                 <a href="sv-rawatan-muka.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Rendaman</div>

               <div class="details">Berendam dalam ketenangan dengan pelbagai pilihan mandian herba dan terapeutik. Setiap rendaman direka untuk menyegarkan tubuh, melembutkan kulit dan menenangkan fikiran selepas hari yang meletihkan.
               </div>

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
                 <a href="sv-terapi-rendaman.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Wap</div>

               <div class="details">Lepaskan ketegangan dengan terapi wap herba yang membantu membersihkan tubuh dari dalam. Proses ini meningkatkan sirkulasi darah, membuka liang pori dan menyokong kesihatan menyeluruh anda dengan cara yang semula jadi.
               </div>

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
                 <a href="sv-terapi-wap.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Kaki </div>

               <div class="details">Bebaskan kaki anda daripada keletihan dengan terapi refleksologi yang menenangkan. Rawatan ini membantu merangsang titik-titik penting pada tapak kaki untuk meningkatkan peredaran darah dan mengurangkan stres di seluruh badan.
               </div>

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
                 <a href="sv-terapi-kaki.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Wax</div>

               <div class="details">Nikmati kelembutan kulit yang tahan lama dengan perkhidmatan waxing berkualiti. Keluarkan bulu yang tidak diingini dengan cekap dan rasai hasil yang licin serta pertumbuhan semula yang lebih perlahan dan halus.
               </div>

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
                 <a href="sv-terapi-wax.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Bekam
               </div>

               <div class="details">Terapi tradisional Islam yang telah diamalkan sejak zaman Nabi untuk membantu merawat pelbagai masalah kesihatan. Kaedah ini dipercayai dapat meningkatkan kesejahteraan tubuh dan membantu proses penyembuhan semula jadi.
               </div>

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
                 <a href="sv-terapi-bekam.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Sinus
               </div>

               <div class="details">Hadapi masalah sinus dengan lebih yakin melalui rawatan khusus yang membantu melegakan ketidakselesaan. Terapi kami direka untuk memberikan kelegaan daripada simptom sinus dan meningkatkan kualiti hidup harian anda.
               </div>

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
                 <a href="sv-terapi-resdung.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Balutan
               </div>

               <div class="details">Rasai kesegaran tubuh dengan rawatan balutan menyeluruh yang membersihkan kulit mati dan meningkatkan kelembapan secara mendalam. Badan anda akan berasa lebih segar, kencang dan rileks sepenuhnya selepas rawatan.
               </div>

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
                 <a href="sv-terapi-balutan.php" class="btn">Lihat Pakej Sedia Ada</a>

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
               <div class="name">Terapi Pemulihan
               </div>

               <div class="details">Pulihkan pergerakan tubuh dengan rawatan pemulihan profesional yang membantu mengurangkan kesakitan dan meningkatkan kelenturan. Kembalikan keupayaan anda untuk menjalani kehidupan aktif bersama keluarga tercinta.
               </div>

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
                 <a href="sv-terapi-pemulihan.php" class="btn">Lihat Pakej Sedia Ada</a>

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

               <div class="details">Sempurnakan penampilan anda dengan sentuhan kecantikan tambahan yang memberikan perbezaan ketara. Kami menawarkan rawatan bulu mata dan penjagaan kuku untuk melengkapkan keanggunan dan keyakinan diri anda.
               </div>

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