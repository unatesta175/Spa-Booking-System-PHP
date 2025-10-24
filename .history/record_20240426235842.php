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

 
      $starttime = $_POST['starttime'];
      $starttime = filter_var($starttime, FILTER_SANITIZE_STRING);
  
    
  
   $endtime = $_POST['endtime'];
   $endtime = filter_var($endtime, FILTER_SANITIZE_STRING);
   $timeslot = $_POST['timeslot'];
   $staff_id = $_POST['staff_id'];
   $staff_id = filter_var($staff_id, FILTER_SANITIZE_STRING);
   $service_id = $_GET['service_id'];
   $service_id = filter_var($service_id, FILTER_SANITIZE_STRING);
   


   $result = $conn->prepare("SELECT * FROM `services` Where id =?");
   $result->execute([$service_id]);
   if ($result->rowCount() > 0) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

         $duration_service = $row['duration'];
         $pay_amount = $row['price'];
      }
   }

   $duration = $duration_service + 30;

  



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
      $msg = "<div class='alert alert-danger'>Anda tidak boleh menempah slot masa ini dengan ahli terapi yang sama /Sudah ada tempahan bertindih untuk ahli terapi yang dipilih!</div>";
   } else {
      // Insert new booking if the timeslot is available
      $insert_user = $conn->prepare("INSERT INTO `bookings` (id, timeslot, date, starttime, endtime, duration, datetimeapplied, claimstat, bookingstat, pay_amount, pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $insert_user->execute([$new_id, $timeslot, $date, $starttime, $endtime, $duration,  $currentDateTime, "Pending", "Ditempah", $pay_amount, "Belum Bayar", $user_id, $service_id, $staff_id]);

      // Push successful booking to the $bookings array
      $bookings[] = $timeslot;
  header('location:booking.php');
      // Set success message
      $msg = "<div class='alert alert-success'>Tempahan anda berjaya!</div>";
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
         $bookingStartTime = strtotime($booking['starttime']); // Convert booking start time to timestamp
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

   <script src="../js/jquery.dataTables.min.js"></script>
   <script src="../js/dataTables.bootstrap.min.js"></script>
   <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />

   <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
   <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
   <!-- Ending of Data tables requirements -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">



   <style>


   </style>
</head>

<body>

   <?php include 'components/user-header.php'; 
   ?>
   <?php echo $loginSuccessScript; ?>
   <div class="section">
      <br>
      <div class="container">



         <br>

         <div class="row"
            style="background-color:white; border-radius: 25px; padding:15px; box-shadow: var(--box-shadow);">
            <h1 class="text-center" style=" font-size: 4rem;
   color:var(--black);
   margin-bottom: 2rem;
   text-align: center;
   text-transform: capitalize;
   font-family: 'Gilroymedium', sans-serif !important;
   font-weight: 800 !important;">Tempah Sesi Rawatan</h1>
            

            <div class="container mt-3">
               <a href="service-package.php" class="btns btn-secondary"><i class="fas fa-arrow-left"></i>Kembali ke
                  Pakej</a>
            </div>
         </div>






         <br>
      </div>
      <br>
   </div>







   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

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
         var currentserviceid = getUrlParameter('service_id');

         // Update the URL without refreshing the page
         history.pushState(null, null, '?staff=' + selectedStaffId + '&date=' + currentDate + '&service_id=' + currentserviceid);

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
         var newUrl = '?staff=' + selectedStaffId + '&date=' + currentDate + '&service_id=' + selectedServiceId;
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
            localStorage.setItem('selectedDate', selectedDate);
            location.reload();

         });
      });
   </script>
   <script>

      document.addEventListener('DOMContentLoaded', function () {
         // Add event listener to the duration input field
         document.getElementById('duration').addEventListener('change', function () {
            // Get the new duration value
            var newDuration = this.value;

            // Update the duration value in localStorage
            localStorage.setItem('selectedDuration', newDuration);
         });

         // Add event listener to the service ID select element
         document.getElementById('service_id').addEventListener('change', function () {
            // Get the new service ID value
            var newServiceId = this.value;

            // Update the service ID value in localStorage
            localStorage.setItem('selectedServiceId', newServiceId);
         });

         // Function to retrieve and set initial values from localStorage
         function setInitialValuesFromLocalStorage() {
            // Retrieve the duration and service ID values from localStorage
            var storedDuration = localStorage.getItem('selectedDuration');
            var storedServiceId = localStorage.getItem('selectedServiceId');

            // Set the duration value in the input field

         }


      });
   </script>

   <script>
      document.addEventListener('DOMContentLoaded', function () {
         // Retrieve the value from local storage
         var storedValue = localStorage.getItem('storedInputValue');

         // Check if the value exists in local storage
         if (storedValue !== null) {
            // Find the input element using its ID
            var inputElement = document.getElementById('inputId');

            // Set the retrieved value as the value of the input element
            inputElement.value = storedValue;
         }
      });

   </script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const timeslotButtons = document.querySelectorAll('.book.btn-success'); // Select all timeslot buttons

    timeslotButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove selected class from all buttons
            timeslotButtons.forEach(btn => btn.classList.remove('selected'));

            // Add selected class to the clicked button
            this.classList.add('selected');

            // Extract the start time from the button's text
            const startTimeText = this.textContent.trim();
            const startTime = convertTo24Hour(startTimeText);

            // Get the service duration from a hidden input or URL
            const serviceDuration = parseInt(document.getElementById('service_duration').value);

            // Check if serviceDuration is a valid number
            if (!isNaN(serviceDuration)) {
                // Calculate end time
                const endTime = calculateEndTime(startTime, serviceDuration);

                // Update the timeslotz input with the selected timeslot and duration
                document.getElementById('timeslotz').value = `${startTime} - ${endTime}`;
                // Update the endtime hidden input
                document.getElementById('endtime').value = endTime;
                document.getElementById('starttime').value = startTime;
            } else {
                console.error('Invalid service duration provided.');
            }
        });
    });

    // Function to calculate end time based on start time and service duration
    function calculateEndTime(startTime, duration) {
        const startTimeMoment = moment(startTime, 'HH:mm');
        const endTimeMoment = startTimeMoment.clone().add(duration, 'minutes'); // Use clone to prevent modifying the original moment
        return endTimeMoment.format('HH:mm'); // Output in 24-hour format with colon
    }

    // Function to convert time to 24-hour format
    function convertTo24Hour(time) {
        const [hours, minutes, period] = time.split(/[:\s]/);
        let hour = parseInt(hours);
        const isPM = period.toLowerCase() === 'pm';

        if (hour !== 12 && isPM) {
            hour += 12;
        } else if (hour === 12 && !isPM) {
            hour = 0;
        }

        return `${hour.toString().padStart(2, '0')}:${minutes}`;
    }
});
</script>

<script>
   function updateUrlWithServiceDetails() {
    var serviceSelect = document.getElementById('service_id');
    var selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
    var serviceDuration = selectedOption.getAttribute('data-duration'); // get the duration from the data attribute

    // Set the hidden input's value
    document.getElementById('service_duration').value = serviceDuration;

    var currentUrl = new URL(window.location.href);
    var params = currentUrl.searchParams;

    // Set service duration in URL query parameters
    params.set('service_duration', serviceDuration);

    // Construct the new URL
    var newUrl = currentUrl.pathname + '?' + params.toString();
    window.history.pushState({ path: newUrl }, '', newUrl);
}

// Call the update function when the page loads if parameters exist
document.addEventListener('DOMContentLoaded', function() {
    if(window.location.search.length > 0) {
        updateUrlWithServiceDetails();
    }
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="js/sweetalert2.all.min.js"></script>
</body>

</html>