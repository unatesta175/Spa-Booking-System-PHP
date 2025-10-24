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
  // Database connection details
  $db_name = 'mysql:host=localhost;dbname=spa_db';
  $user_name = 'root';
  $user_password = '';

  // Establish a PDO database connection
  $conn = new PDO($db_name, $user_name, $user_password);

  // Prepare SQL statement to fetch bookings for the given month and year
  $stmt = $conn->prepare("SELECT * FROM bookings WHERE MONTH(date) = ? AND YEAR(date) = ?");
  $stmt->execute([$month, $year]);

  // Fetch the results into an array
  $bookings = [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Store booking dates in an array
    $bookings[] = $row['date'];
  }

 

  // Array of day names starting from Monday
  $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

  // Get the timestamp of the first day of the given month
  $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

  // Get the number of days in the given month
  $numberDays = date('t', $firstDayOfMonth);

  // Get date components of the first day of the month
  $dateComponents = getdate($firstDayOfMonth);

  // Get the name of the month
  $monthName = $dateComponents['month'];

  // Get the day of the week for the first day of the month (0-indexed, starting from Monday)
  $dayOfWeek = $dateComponents['wday'];

  // Adjust the day of the week to start from Monday
  $dayOfWeek = ($dayOfWeek == 0) ? 6 : $dayOfWeek - 1;

  // Get today's date in YYYY-MM-DD format
  $dateToday = date('Y-m-d');

  // Calculate previous and next month and year for navigation
  $prev_month = date('m', mktime(0, 0, 0, $month - 1, 1, $year));
  $prev_year = date('Y', mktime(0, 0, 0, $month - 1, 1, $year));
  $next_month = date('m', mktime(0, 0, 0, $month + 1, 1, $year));
  $next_year = date('Y', mktime(0, 0, 0, $month + 1, 1, $year));

  // HTML code for the calendar
  $calendar = "<center><h2>$monthName $year</h2>";
  $calendar .= "<a class='btns btn-primary btn-xs' href='?month=$prev_month&year=$prev_year'>Prev Month</a>";
  $calendar .= "<a class='btns btn-primary btn-xs' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a>";
  $calendar .= "<a class='btns btn-primary btn-xs' href='?month=$next_month&year=$next_year'>Next Month</a></center>";
  $calendar .= "
  <form id='room_select_form'>
  <div class='row'>
   <div class='col-md-6 col-md-offset-3 form-group'>
   <label> Select Room</label>
    <select class'form-control' id='room_select'>
". $rooms ."
    </select>
   </div>
   </div>
  </form>
  
  <table class='table table-bordered'>";
  $calendar .= "<tr>";

  // Display day names as table headers
  foreach ($daysOfWeek as $day) {
    $calendar .= "<th class='header'>$day</th>";
  }
  $calendar .= "</tr>";

  // Fill in empty cells for days before the first day of the month
  $calendar .= "<tr>";
  for ($i = 0; $i < $dayOfWeek; $i++) {
    $calendar .= "<td class='emptys'></td>";
  }

  // Loop through each day of the month
  $currentDay = 1;
  while ($currentDay <= $numberDays) {
    // Start a new row if it's a new week
    if ($dayOfWeek % 7 == 0) {
      $calendar .= "</tr><tr>"; // Start a new row
    }

    // Format the current day with leading zeros if needed
    $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

    // Construct the date in YYYY-MM-DD format
    $date = "$year-$month-$currentDayRel";

    // Get the day name (e.g., Monday, Tuesday, etc.) and convert it to lowercase
    $dayName = strtolower(date('l', strtotime($date)));

    // Initialize eventNum variable
    $eventNum = 0;

    // Check if the current date is today
    $today = ($date == $dateToday) ? 'today' : '';

    // Check if the current date is booked or if it's a specific day
    if ($dayName === 'tuesday' && $date > date('Y-m-d')) {
      $calendar .= "<td><h4>$currentDay</h4><button class='btns btn-secondary btn-xs'>Spa tutup</button></td>";
    } elseif ($date < date('Y-m-d')) {
      $calendar .= "<td><h4>$currentDay</h4><button class='btns btn-danger btn-xs'>N/A</button></td>";
    } else {

      $totalbookings = checkSlots($conn, $date);
      if ($totalbookings == 36) {
        $calendar .= "<td class='$today'><h4>$currentDay</h4><a href='#'class='btns btn-danger btn-xs'>Tempahan Penuh</a></td>";

      } else {
        $availableslots = 36 - $totalbookings;
        $calendar .= "<td class='$today'><h4>$currentDay</h4><a href='timeslot.php?date=" . $date . "'class='btns btn-success btn-xs'>Tempah</a><br> <span><i>$availableslots slots left</i></span></td>";

      }

    }


    // Move to the next day
    $currentDay++;
    $dayOfWeek++;
  }

  // Fill in remaining empty cells
  if ($dayOfWeek % 7 != 0) {
    $remainingDays = 7 - ($dayOfWeek % 7);
    for ($i = 0; $i < $remainingDays; $i++) {
      $calendar .= "<td class='emptys'></td>";
    }
  }

  // Close the last row and table
  $calendar .= "</tr></table>";

  // Return the generated calendar HTML
  return $calendar;
}

function checkSlots($conn, $date)
{

  // Prepare SQL statement to fetch bookings for the given month and year
  $stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? ");
  $stmt->execute([$date]);

  $totalbookings = 0;

  // Fetch the results into an array
  $bookings = [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Store booking dates in an array
    $totalbookings++;
  }

  return $totalbookings;

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
    /* General styles */
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }

    .container {
      width: 100%;
      margin: 0 auto;

    }

    h2 {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .btns {
      display: inline-block;
      font-weight: 600;
      color: #fff;
      text-align: center;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 25px;
      margin: 0 5px;
      transition: background-color 0.3s ease;
      font-family: 'Gilroymedium', sans-serif !important;
      font-size: 1.3rem !important;
    }

    .btns:hover {
      background-color: #0056b3;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      width: 14.28%;
      /* Each column takes 14.28% of the table width (100% / 7) */
      padding: 10px;
      text-align: center;
      font-size: 1.3rem;
    }



    tr {
      display: flex;
      flex-wrap: wrap;
      width: 100%;
    }


    .emptys {
      background-color: #f8f9fa;
      border-color: #dee2e6;
    }

    /* Responsive styles */
    @media only screen and (max-width: 768px) {
      h2 {
        font-size: 20px;
      }
    }

    @media only screen and (max-width: 576px) {
      .btns {
        display: block;
        width: 100%;
        margin: 5px 0;
      }
    }

    .today {
      background-color: #f7ff97 !important;
    }

    .btn-success {
      color: #fff !important;
      background-color: #15c271 !important;
      border-color: #198754 !important;
    }
  </style>
</head>

<body>

  <?php include 'components/user-header.php'; ?>

  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-md-12 bg-light">

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