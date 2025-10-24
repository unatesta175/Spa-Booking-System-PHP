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

   <div class="service-bg ">

      <section class="service">

         <div class="swiper service-slider">

            <div class="swiper-wrapper">

               <div class="swiper-slide slide" style="height:40rem;">

                  <div class="content">

                     <h3>Rawatan Kaki</h3>
                     <br>
                     <span>Lepaskan segala ketegangan anda dengan urutan kaki yang melegakan dan selesa. Bayangkan perasaan yang memuaskan dan lega selepas kami lepaskan titik-titik sakit di kaki anda. Itulah jaminan Kapas..</span>
                     <br>
                     <a href="#main" class="btn">Lihat Pakej Sedia Ada</a>
                     <br>
                  </div>
               </div>


            </div>



         </div>

      </section>

   </div>


   <section id="main" class="show-services">

      <h1 class="heading" style="text-transform: capitalize !important;">pakej sedia ada</h1>

      <div class="box-container">

         <?php

         $select_products = $conn->prepare("SELECT * FROM `services` WHERE `type`=? ");
     $select_products->execute([('skrub')]);
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
                  <img src="./uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                  <div class="name">
                     <?= $fetch_products['name']; ?>
                  </div>
                  <div class="price">RM<span>
                        <?= intval($fetch_products['price']); ?> Per Pax
                     </span></div>

                  <div class="duration">Anggaran Masa : <span>
                        <?= formatDuration($fetch_products['duration']); ?>
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
                     <a href="booking.php?update=<?= $fetch_products['id']; ?>" class="btn">Tempah</a>

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

      window.addEventListener('scroll', function () {
         var parallax = document.querySelector('.service-bg');
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