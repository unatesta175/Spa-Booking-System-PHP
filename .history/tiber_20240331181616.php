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

  $dayOfWeek = $dateComponents['wday']; // Use a different variable to store the day of the week

  $dateToday = date('Y-m-d');

  $prev_month = date('m', mktime(0, 0, 0, $month - 1, 1, $year));
  $prev_year = date('Y', mktime(0, 0, 0, $month - 1, 1, $year));

  $next_month = date('m', mktime(0, 0, 0, $month + 1, 1, $year));
  $next_year = date('Y', mktime(0, 0, 0, $month + 1, 1, $year));

  $calendar = "<center><h2>$monthName $year</h2>";
  $calendar .= "<a class='btns btn-primary btn-xs' href='?month=$prev_month&year=$prev_year'>Prev Month</a>";
  $calendar .= "<a class='btns btn-primary btn-xs' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a>";
  $calendar .= "<a class='btns btn-primary btn-xs' href='?month=$next_month&year=$next_year'>Next Month</a></center>";


  $calendar .= "<table class='table table-bordered'>";
  $calendar .= "<tr>";
  // Display day names
  foreach ($daysOfWeek as $day) {
    $calendar .= "<th class='header'>$day</th>";
  }
  $calendar .= "</tr>";

  // Fill in empty cells for days before the first day of the month
  $calendar .= "<tr>";
  for ($i = 0; $i < $dayOfWeek; $i++) {
    $calendar .= "<td class='empty'></td>";
  }

  $currentDay = 1;
  while ($currentDay <= $numberDays) {
    // Start a new row if it's a new week
    if ($dayOfWeek == 7) {
      $dayOfWeek = 0;
      $calendar .= "</tr><tr>";
    }

    $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
    $date = "$year-$month-$currentDayRel";
    $today = ($date == $dateToday) ? 'today' : '';

    $calendar .= "<td class='$today'><h3>$currentDayRel</h3></td>";

    $currentDay++;
    $dayOfWeek++;
  }

  // Fill in remaining empty cells
  if ($dayOfWeek != 7) {
    $remainingDays = (7 - $dayOfWeek);
    for ($i = 0; $i < $remainingDays; $i++) {
      $calendar .= "<td class='empty'></td>";
    }
  }

  $calendar .= "</tr></table>";

  return $calendar;
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
    @media only screen and (max-width:760px),
    (min-device-width :802px) and (max-device-width: 1020px) {

      table,
      thead,
      tbody,
      th,
      td,
      tr {

        display: block !important;
      }

      .empty {
        display: none!important;
      }

      th {
        position: absolute!important;
        top: -9999px!important;
        left: -9999px!important;
      }

      tr {
        border: 1px solid #ccc!important;
      }

      td {
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 50%;
      }

      td:nth-of-type(1):before {

        content: "sunday";
      }

      td:nth-of-type(2):before {

        content: "Monday";
      }

      td:nth-of-type(3):before {

        content: "Tuesday";
      }

      td:nth-of-type(4):before {

        content: "Wednesday";
      }

      td:nth-of-type(5):before {

        content: "Thursday";
      }

      td:nth-of-type(6):before {

        content: "Friday";
      }

      td:nth-of-type(7):before {

        content: "Saturday";
      }
    }

      @media only screen and (min-device-width:320px) and (max-device-width:480px) {

        body {

          padding: 0;
          margin: 0;
        }
      }

      @media only screen and (min-device-width:802px) and (max-device-width:1020px) {

        body {

          width: 495px;
        }
      }

      @media (min-width:641px) {

        table {

          table-layout: fixed;
        }

        td {

          width: 33%;
        }
      }

    .row{margin-top:20px;}

.today{background:yellow !important;}
    

    .btns {

      display: inline-block;
      font-weight: 400;
      color: #212529;
      text-align: center;
      vertical-align: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      background-color: transparent;
      border: 1px solid transparent;
      padding: 0.375rem 0.75rem;
      font-size: 1.5rem;
      line-height: 1.5;
      border-radius: 15px;
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    
  </style>
</head>

<body>

  <?php include 'components/user-header.php'; ?>

  <div class="section">
    <div class="container m-5">
      <div class="row">
        <div class="col-md-12">

          <?php

          $dateComponents = getdate();

          if (isset($_GET['month']) && isset($_GET['year'])) {
            $month = $_GET['month'];
            $year = $_GET['year'];
          } else {
            $month = $dateComponents['mon'];
            $year = $dateComponents['year'];
          }

          echo build_calendar($month, $year);

          ?>
        </div>
      </div>
    </div>
  </div>










  <?php include 'components/footer.php'; ?>

  <script src="js/script.js"></script>
  <script>

  </script>



</body>

</html>