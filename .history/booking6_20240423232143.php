<?php

include 'components/connect.php';

session_start();

if (isset ($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;




if ($_SERVER["REQUEST_METHOD"] == "POST") {


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
   $_SESSION['booking_success'] = true;
   // Function to increment ID


   if (isset ($_SESSION['booking_success'])) {
      // Prepare JavaScript for showing the Sweet Alert
      echo "<script>
           window.onload = function() {
               Swal.fire({
                   title: 'Anda berjaya membuat tempahan!',
                   text: 'Sesi Tempahan sudah berjaya ditempah!',
                   icon: 'success',
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
           }
         </script>";
      unset($_SESSION['login_success']); // Unset the flag
   } else {
      echo "";
   }



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
      .fc-timegrid-col-events {
         width: 100% !important;
      }

      .my-custom-popup {
         font-size: 16px;
         /* Change the font size as needed */
      }

      /* Define the font size for the title */
      .my-custom-title {
         font-size: 20px;
         /* Change the font size as needed */
      }

      /* Define the font size for the text */
      .my-custom-text {
         font-size: 18px;
         /* Change the font size as needed */
      }

      /* Define the font size for the confirm button */
      .my-custom-confirm-button {
         font-size: 16px;
         /* Change the font size as needed */
      }

      /* Define the font size for the cancel button */
      .my-custom-cancel-button {
         font-size: 16px;
         /* Change the font size as needed */
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
         height:100%
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

      }
   </style>
</head>

<body>





   <?php include 'components/user-header.php'; ?>



   <section id="bruh">

      <div class="booking container mt-5 p-3"
         style="background-color: var(--white);box-shadow: 0 .5rem 1rem rgba(0,0,0,.1); border-radius:15px; ">
         <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0"
               aria-valuemax="100"></div>
         </div>

         <form id="step1">
            <h2>Pilih tarikh dan masa sesi terapi</h2>

            <div class="flex">
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
                     $duration_service = $row['duration'];
                     $duration = $duration_service + 30;
                  }
               }


               ?>
               <div class="inputBox">
                  <label for="name">Tempoh masa sesi rawatan:<span style="color: red;"></span></label>
                  <input readonly style="font-size:1.3rem;" type="text" name="duration" class="box"
                     value="<?= formatDuration($duration); ?>">
               </div>
               <div class="inputBox">
                  <label for="name">Tarikh sesi rawatan:<span style="color: red;">*</span></label>
                  <input style="font-size:1.3rem;" type="date" name="date" required
                     placeholder="Masukkan tarikh sesi rawatan" class="box" id="dateInput">
               </div>

               <div class="inputBox">
                  <label for="email">Masa sesi rawatan:<span style="color: red;">*</span></label>
                  <input type="time" name="time" required placeholder="Masukkan masa sesi rawatan" class="box"
                     id="timeInput">
               </div>
            </div>
            <button type="button" class="btns btn-primarys next">Next</button>
         </form>

         <form id="step2" style="display: none;">
            <h2>Isi Maklumat Tempahan</h2>
            <div class="flex">

               <div class="inputBox">
                  <label for="name">Pesanan:<span style="color: red;"></span></label>
                  <input type="text" name="remarks" placeholder="Masukkan pesanan anda" maxlength="200" class="box">
               </div>



               <div class="inputBox ">
                  <label>Pakar Terapi :<span style="color: red;">*</span></label>
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
                  <label>Pakej Rawatan Spa :<span style="color: red;">*</span></label>
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
                  <label>Jenis Pembayaran :<span style="color: red;">*</span></label>
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
            <button type="button" class="btns btn-secondarys prev">Previous</button>
            <button type="button" class="btns btn-primarys next">Next</button>
         </form>

         <form id="step3" style="display: none;" method="post" action="">
            <h2>Invoice</h2>
            <!-- <div class="form-group">
               <label for="input3">Input 3</label>
               <input type="text" class="form-control" id="input3" placeholder="Enter Input 3">
            </div> -->
            <input required type="hidden" name="date" value="">
            <input required type="hidden" name="time" value="">
            <input type="hidden" name="remarks" value="">
            <input required type="hidden" name="staff_id" value="">
            <input required type="hidden" name="service_id" value="">
            <input required type="hidden" name="pay_type" value="">
            <button type="button" class="btns btn-secondarys prev">Previous</button>
            <button type="submit" class="btns btn-success">Tempah</button>

         </form>
      </div>
   </section>

   <section>
      <!-- <div style="background-color:white; border-radius:15px;padding:10px;  box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
      margin-bottom:5px;">
         <h3>1. Pilih tarikh slot terapi dalam kalendar dibawah</h3>
         <h3>2. Pilih slot masa terapi berdasarkan tarikh yang dipilih</h3>
      </div> -->

      <div class="containerc">
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

   <script src="js/script.js"></>
         <!-- jQuery and Bootstrap JS -->
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script src="js/sweetalert2.all.min.js"></script>


   <script>
      // Create event listener for both calendar
                                    document.addEventListener('DOMContentLoaded', function () {
         // Retrieve date parameter from URL
         const urlParams = new URLSearchParams(window.location.search);
                                    const dateStr = urlParams.get('date');



                                    // Initialize Calendar1
                                    var calendarEl1 = document.getElementById('calendar1');
                                    var calendarEl2 = document.getElementById('calendar2');


                                    // Add year and month dropdowns to the header toolbar of calendar1
                                    var headerToolbar1 = {
                                       left: 'prevYear,prev,next,nextYear today',
                                    center: 'title',
                                    right: '',
         };

                                    // Add year and month dropdowns only for calendar1


                                    // Declare Calendar1
                                    var calendar1 = new FullCalendar.Calendar(calendarEl1, {
                                       initialView: 'dayGridMonth',
                                    slotDuration: '00:15:00',
                                    slotLabelInterval: '00:15:00',
                                    headerToolbar: headerToolbar1,
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
                  return {html: '<div class="fc-daygrid-day-content"><div class="date">' + date + '</div><button disabled class="btnd btn-secondary" >Off Day</button></div>' };
               }

                                    var buttonHtml = '';
                                    var isAvailable = true;

                                    if (isAvailable) {
                                       buttonHtml = '<button class="btnd btn-success" onclick="goToTimeGridDay(\'' + info.dateStr + '\')">Available Slot</button>';
               } else {
                                       buttonHtml = '<button class="btnd btn-danger" onclick="goToTimeGridDay(\'' + info.dateStr + '\')">Booked</button>';
               }
                                    return {html: '<div class="fc-daygrid-day-content"><div class="date">' + date + '</div>' + buttonHtml + '</div>' };
            },
                                    dateClick: function (info) {
                                       goToTimeGridDay(info.dateStr);
            },
                                    slotLaneContent: function (slotLaneInfo) {
               if (calendar1.view.type === 'timeGridDay' && slotLaneInfo.date.getDay() === 2) {
                  return '';
               } else {
                  var buttonHtml = '<button class="btnt btn-success" onclick="bookSlot(\'' + slotLaneInfo.dateStr + '\')">Book Available Slot</button>';
                                    return {html: '<div style="text-align:center;">' + buttonHtml + '</div>' };
               }
            }
         });

                                    // Declare calendar2
                                    var calendar2 = new FullCalendar.Calendar(calendarEl2, {
                                       initialView: 'timeGridDay',
                                    slotDuration: '02:00:00',
                                    slotLabelInterval: '02:00:00',
                                    defaultDate: dateStr,
                                    headerToolbar: {
                                       left: '',
                                    center: 'title',
                                    right: ''
            },
                                    slotMinTime: '10:00',
                                    slotMaxTime: '19:00',
                                    allDaySlot: false,
                                    eventColor: '#cf0000',
                                    businessHours: {
                                       startTime: '10:00',
                                    endTime: '19:00',
                                    daysOfWeek: [0, 1, 3, 4, 5, 6]
            },
                                    slotLabelContent: function (arg) {
                                       // Extract start and end times
                                       let startTime = arg.date;
                                    let endTime = new Date(startTime.getTime() + 120 * 60 * 1000); // Adding 15 minutes

                                    // Format start and end times as "HH:MM" strings
                                    let startHours = startTime.getHours().toString().padStart(2, '0');
                                    let startMinutes = startTime.getMinutes().toString().padStart(2, '0');
                                    let endHours = endTime.getHours().toString().padStart(2, '0');
                                    let endMinutes = endTime.getMinutes().toString().padStart(2, '0');

                                    // Return formatted time range
                                    return `${startHours}:${startMinutes} - ${endHours}:${endMinutes}`;
            },
                                    slotLaneContent: function (slotLaneInfo) {



                                    // Check if the slot is within business hours and not within the rest time (1 PM to 2 PM)
                                    if (
                                    slotLaneInfo.date.getDay() !== 2 && // Not Tuesday
                  slotLaneInfo.date.getHours() >= 10 && // After opening time
                                    slotLaneInfo.date.getHours() < 19 && // Before closing time
                                    !(slotLaneInfo.date.getHours() === 13 && slotLaneInfo.date.getMinutes() === 0) // Not between 1 PM to 2 PM
                                    ) {
                  // Display "Available Slot" for time slot cells within business hours
                  
                  return {
                                       html: '<div class="available-slot" style="color: #155724; background-color:#d4edda;border-color: #c3e6cb; text-align: center; ">Available Slot</div>'
                  };
               } else if (
                                    slotLaneInfo.date.getHours() === 13 && slotLaneInfo.date.getMinutes() === 0 // Between 1 PM to 2 PM
                                    ) {
                  // Display "Staff Rest Hour" for time slot cells during rest time
                  return {
                                       html: '<div class="rest-hour" style="color: #721c24; background-color:#f8d7da;border-color: #f5c6cb; text-align: center; ">Staff Rest Hour</div>'
                  };
               } else {
                  // Return an empty div for time slot cells outside business hours
                  return {
                                       html: ''
                  };
               }
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

                                       // Extract AM/PM designation
                                       $start_time = date('h:i A', strtotime($booking['time']));
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
                                    eventClick: function (info) {
                                       Swal.fire({
                                          title: 'Slot Ini sudah ditempah',
                                          text: 'Sila pilih slot masa yang lain ',
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
                                          } // Customize cancel button text
                                       });


            },
            // eventMouseEnter: function (info) {
                                       //    info.el.style.backgroundColor = 'lightgray';
                                       // },
                                       // eventMouseLeave: function (info) {
                                       //    info.el.style.backgroundColor = '';
                                       // },
                                       dateClick: function (info) {
               var clickedDate = info.date;
                                    var dateStr = clickedDate.toLocaleDateString('ms-MY', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

                                    // Check if the clicked time slot is within the staff rest hour (1 PM to 2 PM)
                                    if (clickedDate.getHours() === 13 && clickedDate.getMinutes() === 0) {
                                       Swal.fire({
                                          title: 'Tiada Tempahan dibuka pada jam 1 p.m. hingga 2 p.m.',
                                          text: 'Sila pilih slot masa yang lain ',
                                          icon: 'info',
                                          confirmButtonText: 'OK',
                                          customClass: {
                                             popup: 'my-custom-popup',
                                             title: 'my-custom-title',
                                             text: 'my-custom-text',
                                             confirmButton: 'my-custom-confirm-button',
                                             cancelButton: 'my-custom-cancel-button'
                                          }
                                       });
               } else {
                  // Get hour and minute values from the clicked time slot
                  var hour = ('0' + clickedDate.getHours()).slice(-2);
                                    var minute = ('0' + clickedDate.getMinutes()).slice(-2);

                                    // Format hour and minute values to HH:MM format
                                    var timeStr = hour + ':' + minute;

                                    // Populate the input fields with the selected date and time
                                    var dateInput = document.getElementById('dateInput');
                                    var timeInput = document.getElementById('timeInput');

                                    if (dateInput && timeInput) {



                                       Swal.fire({
                                          title: 'Slot Masa Terapi',
                                          text: 'Adakah anda pasti untuk pilih masa  ' + timeStr + ' pada ' + dateStr + '?',
                                          icon: 'info',
                                          showCancelButton: true, // Display cancel button
                                          confirmButtonText: 'Pasti',
                                          cancelButtonText: 'Pilih yang lain',
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
                                          } // Customize cancel button text
                                       }).then((result) => {
                                          // Check if the user clicked the cancel button
                                          if (result.dismiss === Swal.DismissReason.cancel) {
                                             // Handle cancel action here
                                             dateInput.value = '';
                                             timeInput.value = '';

                                          } else if (result.isConfirmed) {
                                             // Handle confirm action here if needed
                                             dateInput.value = clickedDate.toISOString().split('T')[0];
                                             timeInput.value = timeStr;

                                          }
                                       });

                  }
               }
            }

         });

                                    // End of Calendar2 declaration



                                    // configuration for Calendar 1
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



                                    // Add CSS to change cursor to pointer on hover for calendar2 time slot cells
                                    var calendar2TimeSlotCells = document.querySelectorAll('.fc-timegrid-slot');
                                    calendar2TimeSlotCells.forEach(function (cell) {
                                       cell.style.cursor = 'pointer';
         });
                                    // Configuration options for calendar2
                                    calendarEl2.addEventListener('mouseover', function (e) {
            if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
                                       e.target.style.backgroundColor = 'Green';
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



                                    document.addEventListener('mouseover', function (event) {
            // Check if the mouseover event target or its ancestors have the class ".available-slot"
            if (event.target.closest('.available-slot')) {
                                       event.target.style.backgroundColor = '#8FBC8F'; // Change background color on hover
            }
         });

                                    document.addEventListener('mouseout', function (event) {
            // Check if the mouseout event target or its ancestors have the class ".available-slot"
            if (event.target.closest('.available-slot')) {
                                       event.target.style.backgroundColor = '#d4edda'; // Reset background color on mouseout
            }
         });

                                    document.addEventListener('click', function (event) {
            // Check if the click event target or its ancestors have the class ".available-slot"
            if (event.target.closest('.available-slot')) {
                                       event.target.style.border = '2px solid #228B22'; // Add border on click

                                    // Add mouseout event listener to remove border when mouse moves away
                                    event.target.addEventListener('mouseout', function () {
                                       event.target.style.border = ''; // Reset border when mouse moves away
               });
            }
         });

                                    // Render Calendar1
                                    calendar1.render();
                                    calendar2.render();

         // Some Additional Functions
         // CLick button on Calendar1 and then it will display the date timegridday view on calendar2
         // function goToTimeGridDay(dateStr) {
                                       //    var clickedDate = new Date(dateStr);
                                       //    if (clickedDate.getDay() === 2) { // Check if clicked date is Tuesday
                                       //       alert('No Bookings are available in Tuesdays.');
                                       //    } else {
                                       //       var selectedDateText = 'Anda sudah pilih hari ' + clickedDate.toLocaleDateString('ms-MY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                                       //       document.getElementById('selected-date').textContent = selectedDateText;
                                       //       calendar2.gotoDate(dateStr);
                                       //       calendar2.changeView('timeGridDay');

                                       //    }
                                       // }
                                       // CLick button on Calendar1 and then it will display the date timegridday view on calendar2
                                       function goToTimeGridDay(dateStr) {
                                          var clickedDate = new Date(dateStr);
                                          if (clickedDate.getDay() === 2) { // Check if clicked date is Tuesday

                                             // Display SweetAlert
                                             Swal.fire({
                                                title: 'Tiada Tempahan dibuka pada hari Selasa',
                                                text: 'Sila pilih hari yang lain ',
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
                                                } // Customize cancel button text
                                             });
                                          } else {
                                             var selectedDateText = 'You have selected ' + clickedDate.toLocaleDateString('en-US', { weekday: 'long' }) + ' ';

                                             // Update date input field
                                             updateDateInput(clickedDate.toISOString().split('T')[0]);

                                             // Set the selected date text directly to the h2 element owned by Calendar2
                                             var h2Element = calendarEl2.querySelector('.fc-toolbar-title');
                                             if (h2Element) {
                                                h2Element.textContent = selectedDateText;
                                             }

                                             calendar2.gotoDate(dateStr);
                                             calendar2.changeView('timeGridDay');
                                          }
                                       }

         // Function to update date input field with selected date
         function updateDateInput(date) {
            // Get date input field
            var dateInput = document.querySelector('input[name="date"]');
                                    // Update value of date input field

                                    Swal.fire({
                                       title: 'Slot Tarikh Terapi',
                                    text: 'Adakah anda pasti pilih tarikh ' + date + ' ?',
                                    icon: 'info',
                                    showCancelButton: true, // Display cancel button
                                    confirmButtonText: 'Pasti',
                                    cancelButtonText: 'Pilih yang lain',
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
               } // Customize cancel button text
            }).then((result) => {
               // Check if the user clicked the cancel button
               if (result.dismiss === Swal.DismissReason.cancel) {
                                       // Handle cancel action here
                                       dateInput.value = '';
               } else if (result.isConfirmed) {
                                       // Handle confirm action here if needed
                                       dateInput.value = date;
               }
            });
         }

                                    // Function to update time input field with selected time
                                    function updateTimeInput(time) {
            // Get time input field
            var timeInput = document.querySelector('input[name="time"]');
                                    // Update value of time input field
                                    timeInput.value = time;


         }

                                    //  Display button as selected in Calendar1
                                    function bookSlot(dateStr) {
            var button = document.querySelector('button[data-date="' + dateStr + '"]');
                                    if (button) {
                                       button.classList.add('btn-selected');
            }
         }


      });

   </script>

   <script>
                                    $(document).ready(function () {
         var currentStep = 1;
                                    $('.next').click(function () {
            if (currentStep < 3) {
                                       $('#step' + currentStep).hide();
                                    currentStep++;
                                    $('#step' + currentStep).show();
                                    updateProgressBar();
            }
         });
                                    $('.prev').click(function () {
            if (currentStep > 1) {
                                       $('#step' + currentStep).hide();
                                    currentStep--;
                                    $('#step' + currentStep).show();
                                    updateProgressBar();
            }
         });
                                    function updateProgressBar() {
            var progress = (currentStep - 1) * 50;
                                    $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
         }

                                    // Copy user inputs from Form 1 and Form 2 to hidden inputs in Form 3
                                    $('.next').click(function () {
            if (currentStep === 3) {
                                       $('#step3 input[name="date"]').val($('#step1 input[name="date"]').val());
                                    $('#step3 input[name="time"]').val($('#step1 input[name="time"]').val());
                                    $('#step3 input[name="remarks"]').val($('#step2 input[name="remarks"]').val());
                                    $('#step3 input[name="staff_id"]').val($('#step2 select[name="staff_id"]').val());
                                    $('#step3 input[name="service_id"]').val($('#step2 select[name="service_id"]').val());
                                    $('#step3 input[name="pay_type"]').val($('#step2 select[name="pay_type"]').val());
            }
         });
      });
   </script>

   <script>
                                    // Get the input fields
                                    const dateInput = document.getElementById('dateInput');
                                    const timeInput = document.getElementById('timeInput');

                                    // Add click event listeners to the input fields
                                    dateInput.addEventListener('click', function () {
                                       // Display SweetAlert informing the user that the input is not clickable
                                       Swal.fire({
                                          icon: 'warning',
                                          title: 'info ',
                                          text: 'Sila pilih tarikh tempahan terapi daripada calendar dibawah.',
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
      });

                                    timeInput.addEventListener('click', function () {
                                       // Display SweetAlert informing the user that the input is not clickable
                                       Swal.fire({
                                          icon: 'warning',
                                          title: 'Info',
                                          text: 'Sila pilih masa tempahan terapi daripada slot yang kosong berdasarkan tarikh yang dipilih.',
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
      });
   </script>
   <script>
                                    document.addEventListener("DOMContentLoaded", function () {
         const form = document.querySelector("#step3");

                                    form.addEventListener("submit", function (event) {
                                       event.preventDefault(); // Prevent form submission

                                    // Validate all input fields
                                    const inputs = form.querySelectorAll("input[type='text'], input[type='date'], input[type='time'], select");
                                    let isValid = true;

            inputs.forEach(input => {
               if (!input.value.trim()) {
                                       isValid = false;
                                    input.classList.add("invalid"); // Optionally add a visual indication for invalid fields
               } else {
                                       input.classList.remove("invalid");
               }
            });

                                    if (isValid) {
                                       // If all inputs are valid, submit the form
                                       form.submit();
            } else {
                                       // If any input is invalid, display an error message or take appropriate action
                                       Swal.fire({
                                          title: 'Maklumat tempahan tidak lengkap',
                                          text: 'Sila isi maklumat selengkapnya ',
                                          icon: 'warning',
                                          confirmButtonText: 'OK',
                                          customClass: {
                                             popup: 'my-custom-popup',
                                             title: 'my-custom-title',
                                             text: 'my-custom-text',
                                             confirmButton: 'my-custom-confirm-button',
                                             cancelButton: 'my-custom-cancel-button'
                                          }
                                       });
            }
         });
      });
   </script>

</body>

</html>