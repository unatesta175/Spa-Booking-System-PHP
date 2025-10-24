<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
}
;


function build_calendar($month, $year)
{

  $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

  $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

  $numberDays = date('t', $firstDayOfMonth);

  $dateComponents = getdate($firstDayOfMonth);

  $monthName = $dateComponents['month'];

  $daysOfWeek = $dateComponents['wday'];

  $dateToday = date('Y-m-d');

  $calendar = "<table class='table table-bordered'>";
  $calendar .= "<center><h2>$monthName $year </h2></center>";
  $calendar .= "<tr>";

  foreach ($daysOfWeek as $day) {

    $calendar .= "<th class='header'>$day</th>";
  }

  $calendar = "</tr><tr>";

  if ($daysOfWeek > 0) {

    for ($k = 0; $k < $daysOfWeek; $k++) {

      $calendar .= "<td></td>";
    }
  }

  $currentDay=1;

  $month = str_pad($month , 2, "0" , STR_PAD_LEFT);

ile($currentDay <= $numberDays){

   $currentDayRel = str_pad($currentDay, 2 , "0" , STR_PAD_LEFT);

}


}

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
  <!-- Starting of Data tables requirements -->

  <!-- Bootstrap The most important for Data Tables -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
    integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>
  <!-- jQuery -->
  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css">

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">



  <style>

  </style>
</head>

<body>

  <?php include 'components/user-header.php'; ?>

  <div class="section">
    <div class="container m-5">
      <div class="row">
        <div class="col-md-12"></div>
      </div>
    </div>
  </div>










  <?php include 'components/footer.php'; ?>

  <script src="js/script.js"></script>
  <script>

  </script>



</body>

</html>