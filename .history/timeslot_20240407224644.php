<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

$selectedStaff = isset($_GET['staff']) ? $_GET['staff'] : null;

if (isset($_GET['date'])) {

   $date = $_GET['date'];

   // Prepare SQL statement to fetch bookings for the given month and year
   $stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? && staff_id=?");
   $stmt->execute([$date, $selectedStaff]);

   // Fetch the results into an array
   $bookings = [];
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      // Store booking dates in an array
      $bookings[] = $row['timeslot'];
   }
}

if (isset($_POST['submit'])) {

   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING);
   $timeslot = $_POST['timeslot'];
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


   // Check if the timeslot is already booked for any staff
   $stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? AND timeslot = ?");
   $stmt->execute([$date, $timeslot]);

   $existing_bookings = [];
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $existing_bookings[] = $row;
   }

   $booking_allowed = true;
   $booked_staff_ids = [];
   foreach ($existing_bookings as $booking) {
      $booked_staff_ids[] = $booking['staff_id'];
      if ($booking['staff_id'] == $staff_id) {
         // User already booked the same timeslot with the same staff
         $booking_allowed = false;
         break;
      }
   }

   if (!$booking_allowed || count(array_unique($booked_staff_ids)) < count($existing_bookings)) {
      $msg = "<div class='alert alert-danger'>You cannot book this timeslot with the same staff or there is already an overlapping booking for the selected staff!</div>";
   } else {
      // Insert new booking if the timeslot is available
      $insert_user = $conn->prepare("INSERT INTO `bookings` (id, timeslot, date, time, duration, remarks, datetimeapplied, claimstat, bookingstat, pay_method, pay_type, pay_amount, pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $insert_user->execute([$new_id, $timeslot, $date, $time, $duration, $remarks, $currentDateTime, "Pending", "Ditempah", $pay_method, $pay_type, $pay_amount, "Telah Bayar", $user_id, $service_id, $staff_id]);

      // Push successful booking to the $bookings array
      $bookings[] = $timeslot;

      // Set success message
      $msg = "<div class='alert alert-success'>Your booking was successful!</div>";
   }




}

$duration = 15;
$cleanup = 0;
$start = "10:00";
$end = "19:00";

function timeslots($duration, $cleanup, $start, $end)
{

   $start = new DateTime($start);
   $end = new DateTime($end);
   $interval = new DateInterval("PT" . $duration . "M");
   $cleanupInterval = new DateInterval("PT" . $cleanup . "M");
   $slots = array();

   for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupInterval)) {

      $endPeriod = clone $intStart;
      $endPeriod->add($interval);
      if ($endPeriod > $end) {

         break;
      }

      $slots[] = $intStart->format("H:iA") . " - " . $endPeriod->format("H:iA");
   }

   return $slots;
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

   <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>



   <style>
      .alert {
         font-family: 'Gilroymedium', !important;
         font-size: 1.7rem !important;
      }

      @media (max-width: 768px) {

         /* Adjust the breakpoint as needed */
         .col-md-2 {
            width: 33%;
            /* Display each column at 50% width on mobile devices */
         }
      }

      .btn-success {
         color: #fff !important;
         background-color: #15c271 !important;
         border-color: #198754 !important;

      }

      .btns {
         display: inline-block;
         font-weight: 600;
         color: #fff;
         text-align: center;
         text-decoration: none;
         padding: 10px 20px;
         border-radius: 5px;
         margin: 0 5px;
         transition: background-color 0.3s ease;
         font-family: 'Gilroymedium', !important;
         font-size: 1.3rem !important;
      }

      .btns:hover {
         background-color: #0056b3;
      }
   </style>
</head>

<body>

   <?php include 'components/user-header.php'; ?>
   <div class="section">
      <br>
      <div class="container">
         <br>
         <h1 class="text-center">Tempah untuk tarikh :
            <?php echo date('m/d/Y', strtotime($date)); ?>
         </h1>

         <form >
           

         <div class="inputBox ">
                              <label>Pakar Terapi :<span style="color: red;"></span></label>
                              <select class="box" required placeholder="" name="staff_id" id="staff_id">
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


                           <div class="inputBox ">
                              <label>Pakej Rawatan Spa :<span style="color: red;"></span></label>
                              <select class="box" required placeholder="Pilih Pakej Rawatan Anda" name="service_id" id="service_id">
                                 <option value="" selected hidden>
                                    Pilih Pakej Rawatan Anda
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

                           <?php
              
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
         </form>
         <div class="row">
            <div class="col-md-12">
               <?php echo isset($msg) ? $msg : ""; ?>
            </div>
            <?php
            $timeslots = timeslots($duration, $cleanup, $start, $end);
            foreach ($timeslots as $ts) {
               ?>
               <div class="col-md-2">
                  <div class="form-group m-3">
                     <?php if (in_array($ts, $bookings)) { ?>
                        <button class="btns btn-danger book" data-timeslot="<?php echo $ts; ?>">
                           <?php echo $ts; ?>
                        </button>
                     <?php } else { ?>
                        <button class="btns btn-success book" data-timeslot="<?php echo $ts; ?>">
                           <?php echo $ts; ?>
                        </button>
                     <?php } ?>
                  </div>
               </div>
            <?php } ?>
         </div>
         <div class="container mt-3">
            <a href="javascript:history.go(-1)" class="btns btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
         </div>

         <br>
      </div>
      <br>
   </div>





   <!-- Modal -->
   <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

         <!-- Modal content-->
         <div style="border-radius:25px;" class="modal-content">
            <div class="modal-header" style="text-align: center;">

               <center>
                  <h2 style="margin:10px !important;  font-family: 'Gilroymedium', sans-serif !important;"
                     class="modal-title">Tempah: <span id="slot"></h2>
               </center>

            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="modal-related col-md-12">
                     <form action="" method="post">
                        <div class="flex">
                           <div class="inputBox">
                              <label for="">Masa sesi rawatan:<span style="color: red;"></span></label>
                              <input type="text" readonly name="timeslot" required id="timeslot" class="box">
                           </div>

                           <div class="inputBox">
                              <label for="email">Masa sesi rawatan:<span style="color: red;"></span></label>
                              <input type="time" name="time" required placeholder="Masukkan masa sesi rawatan"
                                 class="box">
                           </div>
                           <div class="inputBox">
                              <label for="name">Pesanan:<span style="color: red;"></span></label>
                              <input type="text" name="remarks" required placeholder="Masukkan pesanan anda"
                                 maxlength="200" class="box">
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


                           <div class="inputBox ">
                              <label>Pakej Rawatan Spa :<span style="color: red;"></span></label>
                              <select class="box" required placeholder="Pilih Pakej Rawatan Anda" name="service_id">
                                 <option value="" selected hidden>
                                    Pilih Pakej Rawatan Anda
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
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <!-- Close button in the modal footer -->
            </div>

         </div>

      </div>
   </div>


   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

   <script>
      $(document).ready(function () {
         $(".book").click(function () {
            var timeslot = $(this).attr('data-timeslot');
            $("#slot").html(timeslot);
            $("#timeslot").val(timeslot);
            $('#myModal').modal('show');
         });
      });

      $(document).ready(function () {
         // Function to close the modal when the close button in the header is clicked
         $(".modal-header .btn-close").click(function () {
            $("#myModal").modal('hide');
         });

         // Function to close the modal when the close button in the footer is clicked
         $(".modal-footer .btn-secondary").click(function () {
            $("#myModal").modal('hide');
         });
      });
   </script>

<script>
  
    // Add event listener to the select element
    document.getElementById('staff_id').addEventListener('change', function() {
        // Get the selected staff ID
        var selectedStaffId = this.value;
        
        // Send AJAX request to the server
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'timeslot.php?staff=' + selectedStaffId , true);
        xhr.send();
        
        // Update the URL without refreshing the page
        history.pushState(null, null, '?staff=' + selectedStaffId + '&date=' + $date);
    });

</script>


</body>

</html>