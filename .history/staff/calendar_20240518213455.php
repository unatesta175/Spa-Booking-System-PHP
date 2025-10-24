<?php

include '../components/connect.php';

session_start();


$staff_id = $_SESSION['staff_id'];

if (!isset($staff_id)) {
    header('location:staff-login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php
   include '../components/functions.php';
   includeHeader();
   ?>
   <!-- Starting of Data tables requirements -->

   <!-- Bootstrap The most important for Data Tables -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <!-- jQuery -->
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../css/calendar.css">

   <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

   <style>
      .btns {
         display: inline-block;
         font-weight: 600;
         color: #fff;
         text-align: center;
         text-decoration: none;
         padding: 10px 20px;
         border-radius: 5px;
         margin: 0 0px;
         transition: background-color 0.3s ease;
         font-family: 'Gilroymedium', !important;
         font-size: 1.3rem !important;

      }

      .green-event{
    background-color: #f75196; 
    color: white; 
}

  
   </style>

</head>

<body>

   
<?php include '../components/staff-header.php'; ?>


   <section>
      <div class="container" style="background-color:white; border-radius: 25px; padding:15px; box-shadow: var(--box-shadow);">
         <!-- <h1 class="heading " style="font-weight:bold; margin:1rem 1rem;">Kalendar Tempahan</h1> -->
         <div id='calendar'>
            </div>
            <div class="container" style="margin-top:1rem;margin-bottom:1rem;">
               <a href="booking-record.php" class="btns btn-warning"><i class="fas fa-arrow-left"></i>Lihat senarai tempahan anda</a>
            </div>
      </div>
   </section>




 

   <script src="../js/script.js"></script>
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         var calendarEl = document.getElementById('calendar');

         var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            slotDuration: '00:15:00', // Each slot is 15 minutes
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
            businessHours: { // Define business hours
               startTime: '10:00', // Start time
               endTime: '19:00', // End time (11 p.m.)
               daysOfWeek: [0, 1, 3, 4, 5, 6] // Business hours for Monday to Friday
            },
            editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            navLinks: true,
            //              dateClick: function(info) {
            //    alert('clicked ' + info.dateStr);
            //  },
            //  select: function(info) {
            //    alert('selected ' + info.startStr + ' to ' + info.endStr);
            //  },
            eventClick: function(info) {
               var eventTitle = info.event.title;
               var eventDescription = info.event.extendedProps.description;
               var eventStaff = info.event.extendedProps.staff;
               var eventbkid = info.event.extendedProps.bkid;
               var eventDate = info.event.extendedProps.bkdate;


               Swal.fire({
                  title: 'Maklumat Tempahan!',
                  //  text: 'Anda sudah berjaya log masuk!',

                  html: '<b>No Tempahan :</b> ' + eventbkid +
                     '<br><b>Tarikh :</b> ' + eventDate +
                     '<br><b>Rawatan :</b> ' + eventTitle +
                     '<br><b>Pakar Terapi :</b> ' + eventStaff +
                     '<br><b>Masa :</b> ' + eventDescription,
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
           $bookings_query = $conn->prepare("SELECT * FROM `bookings` WHERE staff_id =?");
               $bookings_query->execute();
               while ($booking = $bookings_query->fetch(PDO::FETCH_ASSOC)) {
                  $start_datetime = $booking['date'] . 'T' . $booking['starttime'];
                  $start_timestamp = strtotime($start_datetime);
                  $end_timestamp = $start_timestamp + ($booking['duration'] * 60);
                  $end_datetime = date('Y-m-d\TH:i:s', $end_timestamp);
                  $service_id = $booking['service_id'];
                  $booking_id = $booking['booking_id'];
                  $staff_id = $booking['staff_id'];
                  $date = $booking['date'];

                  $timestamp = strtotime($date); // Convert the date to a UNIX timestamp

                  $formatted_date = date('l, j F Y', $timestamp); // Format the timestamp as 'Sunday, 12 May 2024'


                  $bookings_query1 = $conn->prepare("SELECT * FROM `services` WHERE id=?");
                  $bookings_query1->execute([$service_id]);
                  while ($booking1 = $bookings_query1->fetch(PDO::FETCH_ASSOC)) {

                     $sv_name = $booking1['name'];
                  }

                  $bookings_query2 = $conn->prepare("SELECT * FROM `staffs` WHERE id=?");
                  $bookings_query2->execute([$staff_id]);
                  while ($booking2 = $bookings_query2->fetch(PDO::FETCH_ASSOC)) {

                     $st_name = $booking2['name'];
                  }


                  // Extract AM/PM designation
                  $start_time = date('h:i A', strtotime($booking['starttime']));
                  $end_time = date('h:i A', $end_timestamp);

                  echo "{";
                  echo "title: '" . $sv_name . "',";
                  echo "start: '" . $start_datetime . "',";
                  echo "end: '" . $end_datetime . "',";
                  echo "bkid: '" . $booking_id . "',";
                  echo "bkdate: '" . $formatted_date . "',";
                  echo "staff: '" . $st_name . "',";
                  echo "description: ' " . $start_time . " - " . $end_time . "',"; // Add AM or PM to the event description

                  echo "},";
               }


               ?>
            ],
            slotMinTime: '10:00', // Minimum time to display (10 a.m.)
            slotMaxTime: '23:00', // Maximum time to display (11 p.m.)
            scrollTime: '08:00', // Scroll to 8 a.m. initially
            slotLabelInterval: {
               hours: 1
            }, // Show time labels every hour
            slotLabelFormat: {
               hour: '2-digit',
               minute: '2-digit',
               hour12: true,
            },
            eventTimeFormat: { // Format the event time to display with AM/PM designation
               hour: '2-digit',
               minute: '2-digit',
               meridiem: true // Enable displaying AM/PM
            },
            views: {
               timeGridWeek: {
                  eventClassNames: 'green-event' // Add a custom class to events in timeGridWeek view
               },
               timeGridDay: {
                  eventClassNames: 'green-event' // Add a custom class to events in timeGridDay view
               }
            },

         });

         calendar.render();


      });
   </script>
   <script src="../js/sweetalert2.all.min.js"></script>

</body>

</html>