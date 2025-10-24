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

   <div class="other-bg ">

      <section class="other">

         <div class="swiper other-slider">

            <div class="swiper-wrapper">

               <div class="swiper-slide slide" style="height:40rem;">

                  <div class="content">

                     <h3>Pakej Spa</h3>
                     <br>
                     <span>Pengalaman spa yang unik untuk mengembalikan tenaga anda. Kapas menawarkan pakej yang lengkap – lengkap dengan urutan badan, rawatan muka, pakej mandian pada harga yang istimewa.</span>
                     <br>
                     <a href="#main" class="btn">Lihat Pakej Sedia Ada</a>
                     <br>
                  </div>
               </div>


            </div>



         </div>

      </section>

   </div>



   <section id="main" class="service-category">

      <h1 class="heading"  style="text-transform: capitalize !important;">pakej sedia ada</h1>



   </section>
   



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

</body>

</html>