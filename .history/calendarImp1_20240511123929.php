<?php

include 'components/connect.php';

session_start();

if (isset ($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

if (isset ($_POST['submit'])) {


   $date = $_POST['date'];
   $date = filter_var($date, FILTER_SANITIZE_STRING);
   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING);

   $remarks = ($_POST['remarks']);
   $remarks = filter_var($remarks, FILTER_SANITIZE_STRING);


   $staff_id = $_POST['staff_id'];
   $staff_id = filter_var($staff_id, FILTER_SANITIZE_STRING);
   $service_id = $_POST['service_id'];
   $service_id = filter_var($service_id, FILTER_SANITIZE_STRING);
   $pay_type = ($_POST['pay_type']);
   $pay_type = filter_var($pay_type, FILTER_SANITIZE_STRING);


   $result = $conn->prepare("SELECT * FROM `services` Where id =?");
   $result->execute([$service_id]);
   if ($result->rowCount() > 0) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

         $duration_service = $row['duration'];
         $pay_amount = $row['price'];
      }
   }

   $duration = $duration_service + 30;

   $pay_method = "Perbankan Dalam Talian";



   // Set timezone to Kuala Lumpur
   date_default_timezone_set('Asia/Kuala_Lumpur');
   $currentDateTime = date('Y-m-d H:i:s');

   $get_latest_id = $conn->query("SELECT id FROM bookings ORDER BY id DESC LIMIT 1");
   $latest_id_row = $get_latest_id->fetch(PDO::FETCH_ASSOC);

   function incrementId($id)
   {
      $prefix = substr($id, 0, 2);
      $number = (int) substr($id, 2);
      $number++;
      return $prefix . sprintf('%08d', $number);
   }

   // Step 2: Increment the retrieved ID to generate the new ID
   if ($latest_id_row) {
      $latest_id = $latest_id_row['id'];
      $new_id = incrementId($latest_id); // Pass only the ID to the function
   } else {
      // If no records found in the bookings table, start with a default ID
      $new_id = 'KB00000001';
   }

   echo $new_id;

   // Step 3: Insert the new booking with the incremented ID
   $insert_user = $conn->prepare("INSERT INTO `bookings` (id, date, time, duration, remarks, datetimeapplied, claimstat, bookingstat, pay_method, pay_type, pay_amount,  pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
   $insert_user->execute([$new_id, $date, $time, $duration, $remarks, $currentDateTime, "Pending", "Ditempah", $pay_method, $pay_type, $pay_amount, "Telah Bayar", $user_id, $service_id, $staff_id]);
   $message[] = 'Anda sudah berjaya menempah sesi rawatan';

   // Function to increment ID


}
include 'components/wishlist_cart.php';

// $stmt = $conn->prepare("SELECT * FROM bookings WHERE client_id = ?");
// $stmt->execute([$user_id]);
// $bookings = [];
// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    // Format booking into FullCalendar event object
//    $booking = [
//       'title' => 'Booked Slot',
//       'start' => $row['date'] . 'T' . $row['starttime'], // Concatenate date and time for start datetime
//       'end' => date('Y-m-d H:i:s', strtotime($row['starttime'] . ' +' . $row['duration'] . ' minutes')), // Calculate end datetime based on duration
//       'color' => '#f00', // You can set a different color for bookings
//    ];
//    $bookings[] = $booking;
// }
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

   <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>



   <style>
      .fc .fc-timegrid-axis-cushion,
      .fc .fc-timegrid-slot-label-cushion {
         padding: 0px 4px !important;
         height: 6rem !important;
      }

      .fc-timegrid-col-events {
         width: 100% !important;
      }

     

      /* Hide the dropdown arrows for date and time input fields */
      input[type="date"]::-webkit-calendar-picker-indicator {
         display: none;
      }

      input[type="time"]::-webkit-calendar-picker-indicator {
         display: none;
      }

      label {

         flex-direction: row !important;
         font-size: 150.5% !important;
      }

      .box {
         font-size: 1.5rem !important;
      }

      .container,
      .progress-bar,
      .form-group,

      .btns {
         font-size: 1.3rem;
         font-family: 'Gilroymedium', sans-serif;
         /* Adjust the font size as needed */
      }

      .progress {
         display: flex;
         height: 2rem;
         overflow: hidden;
         font-size: 2rem;
         background-color: #e9ecef;
         border-radius: 15px;
      }

      .btn-primarys {
         color: #fff !important;
         background-color: #007bff !important;
         border-color: #007bff !important;
      }

      .btn-secondarys {

         color: #fff !important;
         background-color: #6c757d !important;
         border-color: #6c757d !important;
      }

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

      .fc-timegrid-event,
      .fc-timegrid-more-link {
         border-radius: 3px;
         font-size: 20px !important;
         height: 100%;
      }


      .fc-event-title-container,
      .fc-event-time {
         text-align: center !important;

      }

      .fc-timegrid-event .fc-event-time {
         font-size: 20px !important;

      }

      .fc-timegrid-slot {
         cursor: pointer;
      }

      .fc-scrollgrid-sync-inner {

         border-color: #f75196 !important;
      }



      .btn-selected {
         background-color: green;
         /* Change background color to green */
         color: white;
         /* Change text color to white */
      }

      #calendar1 .fc-daygrid-day:hover {
         background-color: lightblue;
         /* Change background color on hover */
      }

      /* Minimalist styling for FullCalendar */


      .fc-timegrid-slot:hover {
         background-color: lightblue;

      }

      @media only screen and (min-width: 601px) and (max-width: 1200px) {
         .calendar {
            margin: 10px !important;
            /* Add some margin between calendars */
         }
      }

      .containerc {
         display: flex;
         flex-wrap: wrap;
         /* Allow items to wrap to the next row */
         justify-content: center;
         /* Center items horizontally */
         background-color: white;
         border-radius: 15px;
         box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
      }

      /* Style for each calendar */
      .calendar {
         flex: 20;
         /* Each calendar takes up 20% of the container width */
         margin: 10px;
         /* Add some margin between calendars */
      }

      /* Media query for mobile devices */
      @media only screen and (max-width: 600px) {
         .calendar {
            flex: 100%;
            /* Each calendar takes up 100% of the container width */
         }
      }

      /* CSS for selected state */
      .selected-cell {
         background-color: #cce5ff;
         /* Light blue background */
         border: 2px solid #004085;
         /* Dark blue border */
         border-radius: 4px;
         /* Rounded corners */

      }

      /* Overall container */

      #calendar1 {
         max-width: 550px;
         margin: 40px auto;
         font-family: Arial, sans-serif;
      }

      #calendar2 {
         max-width: 550px;
         margin: 40px auto;
         font-family: Arial, sans-serif;
      }




      .fc .fc-col-header-cell-cushion {
         display: inline-block;
         padding: 2px 4px;
         text-decoration: none;

      }

      @media (min-width: 768px) {
         .containerc {
            max-width: 100% !important;
         }

      }


      @media (min-width: 576px) {
         .containerc {
            max-width: 100% !important;
         }

      }

      .fc .fc-daygrid-day-number {
         padding: 4px;
         position: relative;
         z-index: 4;
         text-decoration: none
      }

      .fc table {
         border-collapse: collapse;
         border-spacing: 0px;
         font-size: 1.5rem;

      }

      @media screen and (max-width: 882px) {

         .btnd {

            font-size: .8rem !important;
            padding: 4px !important;
         }
      }

      /* Responsive styling */
      /* Responsive styling */
      @media screen and (max-width: 768px) {

         #calendar2,
         #calendar1 {
            margin: 5px auto;
         }

         .fc-daygrid-day-content {
            flex-direction: column;

         }

         .fc .fc-daygrid-day-number {
            width: 100%;
         }

         .btnd {
            margin: 0px !important;
            /* Adjust margin as needed */
            width: 80% !important;
            /* Adjust button width */
            font-size: .5rem !important;
            padding: 4px !important;
         }

         .btnt {
            margin-top: 10px !important;
            /* Adjust margin as needed */
            width: 80% !important;
            /* Adjust button width */
            font-size: 1rem !important;

         }

         .fc table {
            border-collapse: collapse;
            border-spacing: 0px;
            font-size: 1rem;
         }
      }

      @media screen and (max-width: 540px) {

         .btnd {
            margin-top: 0px !important;
            /* Adjust margin as needed */
            width: 100% !important;
            /* Adjust button width */
            font-size: .7rem !important;
            padding: 2px !important;
            height: 50% !important;
            border-radius: 8px !important;
         }

         .btnt {
            margin-top: 0px !important;
            /* Adjust margin as needed */
            width: 100% !important;
            /* Adjust button width */
            font-size: .7rem !important;
            padding: 2px !important;
            height: 50% !important;
            border-radius: 8px !important;
         }

         .fc table {
            border-collapse: collapse;
            border-spacing: 0px;
            font-size: .7rem;

         }
      }

      .fc-daygrid-day-content {
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
      }

      .fc .fc-daygrid-day-number {
         padding: 4px;
         position: relative;
         z-index: 4;
         text-decoration: none;
         width: 100%;
      }

      /* CSS for the button */
      .btnt {
         margin: 5px;
         /* Adjust margin as needed */
         font-size: 1rem;
         border-radius: 15px;
         padding: 10px;
         width: 25%;
      }

      .btnd {
         margin: 5px;
         /* Adjust margin as needed */
         font-size: 1rem;
         border-radius: 15px;
         padding: 5px;

   </style>
</head>

<body>

   <?php include 'components/user-header.php'; ?>


  
   <section>
      <div class="container" style="background-color:white; border-radius: 25px; padding:15px; box-shadow: var(--box-shadow);">
         <div id='calendar'>
         </div>
      </div>
   </section>




   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>
   <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                slotDuration: '00:15:00',   // Each slot is 15 minutes
                slotLabelInterval: '01:00:00', // Show time labels every 15 minutes
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listDay'
                },
                businessHours: {            // Define business hours
                    startTime: '10:00',    // Start time
                    endTime: '19:00',      // End time (11 p.m.)
                    daysOfWeek: [0, 1, 3, 4, 5, 6] // Business hours for Monday to Friday
                },
                dateClick: function(info) {
      alert('clicked ' + info.dateStr);
    },
    select: function(info) {
      alert('selected ' + info.startStr + ' to ' + info.endStr);
    },
                events: [
                                    <?php
                                    $bookings_query = $conn->prepare("SELECT * FROM `bookings`");
                                    $bookings_query->execute();
                                    while ($booking = $bookings_query->fetch(PDO::FETCH_ASSOC)) {
                                       $start_datetime = $booking['date'] . 'T' . $booking['starttime'];
                                       $start_timestamp = strtotime($start_datetime);
                                       $end_timestamp = $start_timestamp + ($booking['duration'] * 60);
                                       $end_datetime = date('Y-m-d\TH:i:s', $end_timestamp);

                                       // Extract AM/PM designation
                                       $start_time = date('h:i A', strtotime($booking['starttime']));
                                       $end_time = date('h:i A', $end_timestamp);

                                       echo "{";
                                       echo "title: '" . 'Booked Slot' . "',";
                                       echo "start: '" . $start_datetime . "',";
                                       echo "end: '" . $end_datetime . "',";
                                       echo "description: 'Time: " . $start_time . " - " . $end_time . "'"; // Add AM or PM to the event description
                                       echo "},";
                                    }


                                    ?>
                                    ],
                                    slotMinTime: '10:00', // Minimum time to display (10 a.m.)
        slotMaxTime: '23:00', // Maximum time to display (11 p.m.)
        scrollTime: '08:00', // Scroll to 8 a.m. initially
        slotLabelInterval: { hours: 1 }, // Show time labels every hour
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
        },
   
    });

            calendar.render();
        });
    </script>


</body>

</html>