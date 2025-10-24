<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;


$existingBookings = [];
if (isset($_GET['date'])) {
   $date = $_GET['date'];
   $selectedStaff = isset($_GET['staff']) ? $_GET['staff'] : null;

   $stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? AND staff_id = ?");
   $stmt->execute([$date, $selectedStaff]);

   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $existingBookings[] = $row;
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
      $msg = "<div class='alert alert-danger'>Anda tidak boleh menempah slot masa ini dengan ahli terapi yang sama /Sudah ada tempahan bertindih untuk Ahli Terapi yang dipilih!</div>";
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
function timeslots($duration, $cleanup, $start, $end, $existingBookings)
{
   // Convert start and end times to timestamps
   $startTime = strtotime($start); // Convert start time to timestamp
   $endTime = strtotime($end); // Convert end time to timestamp

   $slots = array(); // Initialize an array to store available timeslots

   // Loop through each timeslot
   for ($currentTime = $startTime; $currentTime < $endTime; $currentTime += ($duration + $cleanup) * 60) {
      // Calculate end time for the current timeslot
      $endTimeSlot = $currentTime + $duration * 60;

      // Skip lunchtime (1 pm - 2 pm)
      // if (date('H', $currentTime) == '13') {
      //    continue;
      // }
      // Check if the current timeslot overlaps with any existing booking
      $overlap = false; // Flag to indicate if there's an overlap
      foreach ($existingBookings as $booking) {
         $bookingStartTime = strtotime($booking['time']); // Convert booking start time to timestamp
         $bookingEndTime = $bookingStartTime + $booking['duration'] * 60; // Calculate booking end time

         // If the timeslots overlap, set overlap flag to true
         if (
            ($currentTime >= $bookingStartTime && $currentTime < $bookingEndTime) ||
            ($endTimeSlot > $bookingStartTime && $endTimeSlot <= $bookingEndTime)
         ) {
            $overlap = true;
            break;
         }
      }

      // Format timeslot start and end times in 24-hour format
      $slotStartTime = date('H:i', $currentTime); // Format start time
      $slotEndTime = date('H:i', $endTimeSlot); // Format end time

      // Add timeslot to the array with overlap flag
      $slots[] = array(
         'timeslot' => $slotStartTime . ' - ' . $slotEndTime . ' ' . date('A', $currentTime),
         'overlap' => $overlap // Store whether the timeslot overlaps with an existing booking
      );
   }

   return $slots; // Return the array of available timeslots with overlap flags
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
   <!-- Flatpickr CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


   <style>
      .baby {

         width: 100% !important;
      }

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


         <div class="section add-any">
            <form>



               <div class="inputBox">
                  <label for="datepicker">Tarikh sesi rawatan:</label>
                  <input style="font-family: 'Gilroymedium', sans-serif;" style="font-size:1.3rem;" type="text"
                     name="date" id="datepicker" class="box" placeholder="Date">
               </div>
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
                     <option value="" selected hidden>Pilih Pakej Rawatan Anda</option>
                     <?php
                     $result = $conn->prepare("SELECT * FROM `services` ORDER BY id ASC");
                     $result->execute();
                     if ($result->rowCount() > 0) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                           <option value="<?php echo $row['id']; ?>" data-duration="<?php echo $row['duration']; ?>">
                              <?php echo $row['type'] . ' -  ' . $row['name']; ?>
                           </option>
                        <?php }
                     } ?>
                  </select>
               </div>

               <div class="inputBox">
                  <label for="name">Tempoh masa sesi rawatan:<span style="color: red;"></span></label>
                  <input style="font-family: 'Gilroymedium', sans-serif;" readonly style="font-size:1.3rem;" type="text"
                     name="duration" id="duration" class="box" placeholder="Duration">
               </div>


            </form>
         </div>
         <br>

         <div class="row"
            style="background-color:white; border-radius: 25px; padding:15px; box-shadow: var(--box-shadow);">

            <div class="col-md-12">
               <?php echo isset($msg) ? $msg : ""; ?>
            </div>
            <?php if (!empty($_GET['date']) && !empty($_GET['staff'])): ?>
               <?php
               // Check if the selected date is a Tuesday
               $selectedDate = new DateTime($_GET['date']);
               if ($selectedDate->format('N') == 2) { // 'N' format returns the ISO-8601 numeric representation of the day of the week (1 for Monday, 2 for Tuesday, etc.)
                  ?>
                  <h1 id="selectedDate" class="text-center">It is off day on Tuesday</h1>
               <?php } else { ?>
                  <h1 id="selectedDate" class="text-center">Tempah untuk tarikh : <?php echo $_GET['date']; ?></h1>
                  <?php
                  $timeslots = timeslots($duration, $cleanup, $start, $end, $existingBookings);
                  foreach ($timeslots as $ts) {
                     // Convert the timeslot string to a timestamp
                     $timestamp = strtotime(explode(' - ', $ts['timeslot'])[0]);

                     // Extract the start time with AM/PM information
                     $start_time = date('h:i A', $timestamp);
                     ?>
                     <div class="col-md-2">
                        <div class="form-group m-3">
                           <?php
                           // Check if the timeslot is booked
                           if ($ts['overlap']) { ?>
                              <button class="btns baby btn-danger book" data-timeslot="<?php echo $ts['timeslot']; ?>">
                                 <?php echo $start_time; ?> <!-- Display start time with AM/PM -->
                              </button>
                           <?php } else { ?>
                              <button class="btns baby btn-success book" data-timeslot="<?php echo $ts['timeslot']; ?>">
                                 <?php echo $start_time; ?> <!-- Display start time with AM/PM -->
                              </button>
                           <?php } ?>
                        </div>
                     </div>
                  <?php }
               }
               ?>
            <?php else: ?>
               <div class="col-md-12">
                  <p>Please select both a date and a staff member to view available timeslots.</p>
               </div>
            <?php endif; ?>

            <div class="container mt-3">
               <a href="booking.php" class="btns btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
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
    $(".book").click(function (event) {
        // Get the timeslot and overlap status
        var timeslot = $(this).attr('data-timeslot');
        var overlap = $(this).hasClass('btn-danger'); // Check if the timeslot is red (booked)

        // If the timeslot is booked (red), prevent the default click behavior
        if (overlap) {
            event.preventDefault();
            // Optionally, you can display a message to inform the user that the timeslot is already booked
            alert("This timeslot is already booked. Please choose another timeslot.");
        } else {
            // If the timeslot is available (green), proceed with booking
            $("#slot").html(timeslot);
            $("#timeslot").val(timeslot);
            $('#myModal').modal('show');
        }
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
 // Function to get URL parameter value by name
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    document.getElementById('staff_id').addEventListener('change', function () {
        // Get the selected staff ID
        var selectedStaffId = this.value;

        // Get the current value of the date parameter from the URL
        var currentDate = getUrlParameter('date');

        // Update the URL without refreshing the page
        history.pushState(null, null, '?staff=' + selectedStaffId + '&date=' + currentDate);

        // Store the selected staff ID in localStorage
        localStorage.setItem('selectedStaffId', selectedStaffId);

        // Reload the page
        location.reload();
    });

    // Set the value of the staff selection input to the stored staff ID
    var storedStaffId = localStorage.getItem('selectedStaffId');
    if (storedStaffId) {
        document.getElementById('staff_id').value = storedStaffId;
    }

    // Function to update datepicker value from URL parameter
    function updateDatepickerFromUrl() {
        var dateFromUrl = getUrlParameter('date');
        if (dateFromUrl) {
            document.getElementById('datepicker').value = dateFromUrl;
        }
    }

    // Call the function to update datepicker value from URL on page load
    updateDatepickerFromUrl();

    // Update datepicker value from URL parameter after page reload
    window.addEventListener('popstate', function () {
        updateDatepickerFromUrl();
    });



      document.getElementById('service_id').addEventListener('change', function () {
         var selectedOption = this.options[this.selectedIndex];
         var duration = parseInt(selectedOption.dataset.duration);

         // Format the duration using the provided formatDuration function
         var formattedDuration = formatDuration(duration);

         // Set the formatted duration as the value of the duration input element
         document.getElementById('duration').value = formattedDuration;
      });
      function formatDuration(minutes) {
         // Add 30 minutes to the duration
         minutes += 30;

         // Calculate hours and remaining minutes
         var hours = Math.floor(minutes / 60);
         var remainingMinutes = minutes % 60;

         // Construct the formatted duration string
         var formattedDuration = "";
         if (hours > 0) {
            formattedDuration += hours + " Jam";
         }
         if (remainingMinutes > 0) {
            if (hours > 0) {
               formattedDuration += " " + remainingMinutes + " Minit";
            } else {
               formattedDuration = remainingMinutes + " Minit";
            }
         }
         return formattedDuration;
      }

      document.getElementById('service_id').addEventListener('change', function () {
         // Get the selected service ID
         var selectedServiceId = this.value;

         // Store the selected service ID in localStorage
         localStorage.setItem('selectedServiceId', selectedServiceId);

         // Append the selected service ID to the URL without reloading the page
         var urlParams = new URLSearchParams(window.location.search);
         var currentDate = urlParams.get('date');
         var selectedStaffId = localStorage.getItem('selectedStaffId');
         var newUrl = '?staff=' + selectedStaffId + '&date=' + currentDate + '&service=' + selectedServiceId;
         history.pushState(null, null, newUrl);

         // Check if both staff and service selections are made
         if (selectedServiceId && selectedStaffId) {
            // Show the timeslots container
            document.getElementById('timeslotsContainer').style.display = 'block';
         }
      });


   </script>


   <!-- Flatpickr JS -->
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>
      // Initialize Flatpickr
      flatpickr('#datepicker', {
         dateFormat: 'Y-m-d', // Date format (Year-Month-Day)
         minDate: 'today', // Minimum selectable date (today's date)
         // defaultDate: new Date(), // Default date selected (today's date)
         altInput: true, // Enable alternate input field (to display formatted date)
         altFormat: 'F j, Y', // Date format for alternate input field (e.g., January 1, 2023)
         // enableTime: false, // Enable time selection
         // time_24hr: false, // Use 24-hour time format
         // hourIncrement: 1, // Increment value for hours
         // minuteIncrement: 5, // Increment value for minutes
         // allowInput: true, // Allow manual input of date
         disableMobile: false, // Disable Flatpickr on mobile devices
         inline: false, // Display Flatpickr inline instead of in a dropdown
         mode: 'single', // Date selection mode ('single', 'multiple', or 'range')
         // weekNumbers: true, // Display week numbers
         appendTo: document.body, // Specify parent element for Flatpickr calendar
         plugins: [], // Array of additional plugins to enable
         locale: 'ms', // Locale for language and date formatting
         // wrap: false, // Wrap input field with Flatpickr container
         clickOpens: true, // Clicking on input field opens the calendar
         // disable: [
         //     function (date) {
         //         // Return true to disable Tuesdays
         //         return (date.getDay() === 2); // 0: Sunday, 1: Monday, ..., 6: Saturday
         //     }
         // ],
         onChange: function (selectedDates, dateStr, instance) {
            // Callback function when date is changed
            console.log('Selected Date:', dateStr);
         },
         onClose: function (selectedDates, dateStr, instance) {
            // Callback function when calendar is closed
            console.log('Flatpickr Closed');
         },
         onOpen: function (selectedDates, dateStr, instance) {
            // Callback function when calendar is opened
            console.log('Flatpickr Opened');
         },
         onReady: function (selectedDates, dateStr, instance) {
            // Callback function when Flatpickr is initialized and ready
            console.log('Flatpickr Ready');
         },
         onMonthChange: function (selectedDates, dateStr, instance) {
            // Callback function when month changes
            console.log('Month Changed');
         },
         onYearChange: function (selectedDates, dateStr, instance) {
            // Callback function when year changes
            console.log('Year Changed');
         },
         onOpen: function (selectedDates, dateStr, instance) {
            // Callback function when calendar is opened
            console.log('Flatpickr Opened');
         },
         onError: function (err) {
            // Callback function for error handling
            console.error(err);
         },
         onValueUpdate: function (selectedDates, dateStr, instance) {
            // Callback function when value is updated
            console.log('Value Updated');
         }
      });
   </script>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
         // Add event listener to date input field
         document.getElementById('datepicker').addEventListener('change', function () {
            // Retrieve selected date value
            var selectedDate = this.value;

            // Parse the current URL
            var urlParams = new URLSearchParams(window.location.search);

            // Update the 'date' parameter with the selected date
            urlParams.set('date', selectedDate);

            // Construct the new URL with the updated parameters
            var newUrl = window.location.pathname + '?' + urlParams.toString();

            // Update the browser's URL without reloading the page
            window.history.pushState({}, '', newUrl);

            location.reload();
         });
      });
   </script>

</body>

</html>