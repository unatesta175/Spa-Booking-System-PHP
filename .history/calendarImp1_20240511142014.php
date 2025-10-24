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
   <link rel="stylesheet" href="css/calendar.css">

   <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>



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
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                businessHours: {            // Define business hours
                    startTime: '10:00',    // Start time
                    endTime: '19:00',      // End time (11 p.m.)
                    daysOfWeek: [0, 1, 3, 4, 5, 6] // Business hours for Monday to Friday
                },
                editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            navLinks: true,
                dateClick: function(info) {
      alert('clicked ' + info.dateStr);
    },
    select: function(info) {
      alert('selected ' + info.startStr + ' to ' + info.endStr);
    },eventClick: function(info) {
            var eventTitle = info.event.title;
            var eventDescription = info.event.extendedProps.description;
            var eventId = info.event.extendedProps.id;

            
               Swal.fire({
                   title: 'Maklumat Tempahan!',
                  //  text: 'Anda sudah berjaya log masuk!',
                   
                    html: '<b>Pakej Rawatan dipilih:</b> ' + eventTitle + '<br><b>Description:</b> ' + eventDescription + '<br><b>what:</b> ' + eventId,
                   icon: 'info',
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
                                       $service_id = $booking['service_id'];
                                       $booking_id = $booking['booking_id'];
                                   $bookings_query1 = $conn->prepare("SELECT * FROM `services` WHERE id=?");
                                    $bookings_query1->execute([$service_id]);
                                    while ($booking1 = $bookings_query1->fetch(PDO::FETCH_ASSOC)) {

                                       $sv_name = $booking1['name'];}                                   

                                       // Extract AM/PM designation
                                       $start_time = date('h:i A', strtotime($booking['starttime']));
                                       $end_time = date('h:i A', $end_timestamp);

                                       echo "{";
                                          echo "title: '" . $sv_name . "',";
                                          echo "start: '" . $start_datetime . "',";
                                          echo "end: '" . $end_datetime . "',";
                                          echo "description: 'Time: " . $start_time . " - " . $end_time . "',"; // Add AM or PM to the event description
                                          echo "what: '" . $booking_id . "'"; // Add booking ID
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
        eventTimeFormat: { // Format the event time to display with AM/PM designation
            hour: '2-digit',
            minute: '2-digit',
            meridiem: true // Enable displaying AM/PM
        }
   
    });

            calendar.render();
        });
    </script>
<script src="js/sweetalert2.all.min.js"></script>

</body>

</html>