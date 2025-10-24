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

$dateStr = date('Y-m-d'); // Default to today's date
if (isset ($_GET['date'])) {
   $dateStr = $_GET['date']; // Get the date parameter from the URL
}

// Fetch bookings for the selected date from the database
$stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ?");
$stmt->execute([$dateStr]);
$bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   // Format booking into FullCalendar event object
   $booking = [
      'title' => 'Booked Slot',
      'start' => $row['date'] . 'T' . $row['time'], // Concatenate date and time for start datetime
      'end' => date('Y-m-d H:i:s', strtotime($row['time'] . ' +' . $row['duration'] . ' minutes')), // Calculate end datetime based on duration
      'color' => '#f00', // You can set a different color for bookings
   ];
   $bookings[] = $booking;
}
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

      .fc-event-main {
         width: 100% !important;
      }

      .fc-timegrid-slot:hover {
         background-color: lightblue;

      }

      @media only screen and (min-width: 601px) and (max-width: 1200px) {
         .calendar {
            margin: 10px !important;
            /* Add some margin between calendars */
         }
      }

      .container {
         display: flex;
         flex-wrap: wrap;
         /* Allow items to wrap to the next row */
         justify-content: center;
         /* Center items horizontally */
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
         max-width: 500px;
         margin: 40px auto;
         font-family: Arial, sans-serif;
      }

      #calendar2
       {
         max-width: 500px;
         margin: 40px auto;
         font-family: Arial, sans-serif;
      }




      .fc .fc-col-header-cell-cushion {
         display: inline-block;
         padding: 2px 4px;
         text-decoration: none;

      }

      @media (min-width: 768px) {
         .container {
            max-width: 100% !important;
         }

      }


      @media (min-width: 576px) {
         .container {
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

      /* Responsive styling */
      /* Responsive styling */
      @media screen and (max-width: 768px) {

         #calendar2,
         #calendar1 {
            margin: 20px auto;
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
            font-size: .6rem !important;
            padding: 5px !important;
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

      }
   </style>
</head>

<body>

   <?php include 'components/user-header.php'; ?>


   <section class="add-any">

      <h1 class="heading">Tempah Sesi Rawatan</h1>

      <form action="" method="post">
         <div class="flex">
            <div class="inputBox">
               <label for="name">Tarikh sesi rawatan:<span style="color: red;"></span></label>
               <input type="date" name="date" required placeholder="Masukkan tarikh sesi rawatan" class="box">
            </div>

            <div class="inputBox">
               <label for="email">Masa sesi rawatan:<span style="color: red;"></span></label>
               <input type="time" name="time" required placeholder="Masukkan masa sesi rawatan" class="box">
            </div>
            <div class="inputBox">
               <label for="name">Pesanan:<span style="color: red;"></span></label>
               <input type="text" name="remarks" required placeholder="Masukkan pesanan anda" maxlength="200"
                  class="box">
            </div>



            <div class="inputBox ">
               <label>Pakar Terapi :<span style="color: red;"></span></label>
               <select class="box" required placeholder="" name="staff_id">
                  <option value="" selected hidden>Sila Pilih Pakar Terapi Anda</option>
                  <?php

                  $result = $conn->prepare("SELECT * FROM `staffs` ORDER BY name ASC");
                  $result->execute();
                  if ($result->rowCount() > 0) {
                     while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                           <?php echo $row['name']; ?>
                        </option>
                     <?php }
                  } ?>

               </select>
            </div>

            <?php
            if (isset ($_GET['service_id'])) {
               $service_id = $_GET['service_id'];
            } else {
               $service_id = '1';
            }
            $result = $conn->prepare("SELECT * FROM `services` Where id =?");
            $result->execute([$service_id]);
            if ($result->rowCount() > 0) {
               while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                  $name_service = $row['name'];
                  $type_service = $row['type'];
               }
            }


            ?>

            <div class="inputBox ">
               <label>Pakej Rawatan Spa :<span style="color: red;"></span></label>
               <select class="box" required placeholder="Pilih Pakej Rawatan Anda" name="service_id">
                  <option value="<?php echo $_GET['service_id']; ?>" selected hidden>
                     <?php echo $type_service . ' -  ' . $name_service; ?>
                  </option>
                  <?php

                  $result = $conn->prepare("SELECT * FROM `services` ORDER BY id ASC");
                  $result->execute();
                  if ($result->rowCount() > 0) {
                     while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                           <?php echo $row['type'] . ' -  ' . $row['name']; ?>
                        </option>
                     <?php }
                  } ?>

               </select>
            </div>

            <div class="inputBox ">
               <label>Jenis Pembayaran :<span style="color: red;"></span></label>
               <select class="box" required placeholder="" name="pay_type">
                  <option value="" selected hidden>
                     Sila Pilih Jenis Pembayaran
                  </option>
                  <option value="Cash">
                     Deposit
                  </option>
                  <option value="">
                     Bayaran Penuh
                  </option>
               </select>
            </div>
         </div>
         <input type="submit" value="Tempah" class="option-btn" name="submit">
      </form>
   </section>
   <section>
      <div class="container">
         <!-- Calendar 1 -->
         <div id="calendar1" class="calendar">
            <!-- Calendar 1 content goes here -->
         </div>

         <!-- Calendar 2 -->
         <div id="calendar2" class="calendar">
            <!-- Calendar 2 content goes here -->
         </div>
      </div>
   </section>




   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>
   <script>

      document.addEventListener('DOMContentLoaded', function () {
         const urlParams = new URLSearchParams(window.location.search);
         const dateStr = urlParams.get('date');

         var calendarEl2 = document.getElementById('calendar2');

         var calendar2 = new FullCalendar.Calendar(calendarEl2, {
            initialView: 'timeGridDay',
            slotDuration: '00:15:00',
            slotLabelInterval: '00:15:00',
            defaultDate: dateStr,
            headerToolbar: {
               left: '',
               center: 'title',
               right: ''
            },
            slotMinTime: '10:00',
            slotMaxTime: '22:00',
            allDaySlot: false,
            businessHours: {
               startTime: '10:00',
               endTime: '19:00',
               daysOfWeek: [0, 1, 3, 4, 5, 6]
            },
            events: [
               <?php
               $bookings_query = $conn->prepare("SELECT * FROM `bookings`");
               $bookings_query->execute();
               while ($booking = $bookings_query->fetch(PDO::FETCH_ASSOC)) {
                  $start_datetime = $booking['date'] . 'T' . $booking['time'];
                  $start_timestamp = strtotime($start_datetime);
                  $end_timestamp = $start_timestamp + ($booking['duration'] * 60);
                  $end_datetime = date('Y-m-d\TH:i:s', $end_timestamp);

                  echo "{";
                  echo "title: '" . 'Booked Slot' . "',";
                  echo "start: '" . $start_datetime . "',";
                  echo "end: '" . $end_datetime . "'";
                  echo "},";
               }

               
               ?>
            ],
            eventClick: function (info) {
               alert('Event clicked: ' + info.event.title);
            },
            eventMouseEnter: function (info) {
               info.el.style.backgroundColor = 'lightgray';
            },
            eventMouseLeave: function (info) {
               info.el.style.backgroundColor = '';
            },
            dateClick: function (info) {
              // Check if the clicked cell belongs to Tuesday
              if (info.date.getDay() === 2) {
                  // If it's Tuesday, prevent the default action
                  info.jsEvent.preventDefault();
                  alert('No Available Booking is Available in TUesdays.');
               } else {
                  // For other days, proceed with the default action
                  alert('Event clicked: ' + info.event.title);
               }
            }
         });

         calendarEl2.addEventListener('mouseover', function (e) {
            if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
               e.target.style.backgroundColor = 'lightblue';
            }
         });

         calendarEl2.addEventListener('mouseout', function (e) {
            if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
               e.target.style.backgroundColor = '';
            }
         });

         calendarEl2.addEventListener('click', function (e) {
            if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
               var selectedCells = document.querySelectorAll('.selected-cell');
               selectedCells.forEach(function (cell) {
                  cell.classList.remove('selected-cell');
               });
               e.target.classList.add('selected-cell');
            }
         });

         calendar2.render();

         var calendarEl1 = document.getElementById('calendar1');

         var calendar1 = new FullCalendar.Calendar(calendarEl1, {
            initialView: 'dayGridMonth',
            slotDuration: '00:15:00',
            slotLabelInterval: '00:15:00',
            headerToolbar: {
               left: 'prev,next today',
               center: 'title',
               right: ''
            },
            businessHours: {
               startTime: '10:00',
               endTime: '23:00',
               daysOfWeek: [0, 1, 3, 4, 5, 6]
            },
            slotMinTime: '10:00',
            slotMaxTime: '23:00',
            allDaySlot: false,
           
            dayCellContent: function (info) {
               var date = info.date.getDate();
               if (info.view.type === 'timeGridWeek' || info.view.type === 'timeGridDay') {
                  return '';
               }
               if (info.date.getDay() === 2) {
                  return { html: '<div class="fc-daygrid-day-content"><div class="date">' + date + '</div><button disabled class="btnd btn-secondary" >Off Day</button></div>' };
               }

               var buttonHtml = '';
               var isAvailable = true;

               if (isAvailable) {
                  buttonHtml = '<button class="btnd btn-success" onclick="goToTimeGridDay(\'' + info.dateStr + '\')">Available Slot</button>';
               } else {
                  buttonHtml = '<button class="btnd btn-danger" onclick="goToTimeGridDay(\'' + info.dateStr + '\')">Booked</button>';
               }
               return { html: '<div class="fc-daygrid-day-content"><div class="date">' + date + '</div>' + buttonHtml + '</div>' };
            },
            dateClick: function (info) {
               goToTimeGridDay(info.dateStr);
            },
            slotLaneContent: function (slotLaneInfo) {
               if (calendar1.view.type === 'timeGridDay' && slotLaneInfo.date.getDay() === 2) {
                  return '';
               } else {
                  var buttonHtml = '<button class="btnt btn-success" onclick="bookSlot(\'' + slotLaneInfo.dateStr + '\')">Book Available Slot</button>';
                  return { html: '<div style="text-align:center;">' + buttonHtml + '</div>' };
               }
            }
         });

         calendarEl1.addEventListener('mouseover', function (e) {
            if (e.target.classList.contains('fc-daygrid-day')) {
               e.target.style.backgroundColor = '#c0c0c0';
            }
         });

         calendarEl1.addEventListener('mouseout', function (e) {
            if (e.target.classList.contains('fc-daygrid-day')) {
               e.target.style.backgroundColor = '';
            }
         });

         calendar1.render();

         function goToTimeGridDay(dateStr) {
            calendar2.gotoDate(dateStr);
            calendar2.changeView('timeGridDay');
         }

         function bookSlot(dateStr) {
            var button = document.querySelector('button[data-date="' + dateStr + '"]');
            if (button) {
               button.classList.add('btn-selected');
            }
         }
      });

   </script>



</body>

</html>