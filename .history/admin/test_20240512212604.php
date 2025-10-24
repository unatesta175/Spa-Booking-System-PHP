<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin-login.php');
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
    includeHeaderAdmin()
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

    <!-- Starting of Data tables requirements -->

    <!-- Bootstrap The most important for Data Tables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />

    <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

    <style>
        /* Tooltip container */
        .tooltip {
            font-size: 14px;
            /* Adjust font size */
            max-width: 250px;
            /* Set maximum width */
        }

        /* Tooltip arrow */
        .tooltip .arrow:before {
            border-top-color: #007bff !important;
            /* Set arrow color */
        }

        /* Tooltip title */
        .tooltip .tooltip-inner {
            background-color: #007bff;
            /* Set tooltip background color */
            color: #fff;
            /* Set tooltip text color */
            border-radius: 4px;
            /* Set border radius */
            padding: 8px 12px;
            /* Set padding */
        }

        .btns.selected {
            background-color: #4CAF50;
            /* Darker green for selected timeslot */
            color: white;
            border: 3px solid #4CAF50;
            /* Matching border for clarity */
        }

        .baby {

            width: 100% !important;
        }

        .alert {
            font-family: 'Gilroymedium', !important;
            font-size: 1.7rem !important;
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
            margin: 0 0px;
            transition: background-color 0.3s ease;
            font-family: 'Gilroymedium', !important;
            font-size: 1.3rem !important;
        }



        .btns.baby.btn-success:hover {
            background-color: #28a745 !important;
            /* Change to a darker shade of green */
        }



        .form-group {
            flex: 0 0 calc(25% - 20px);
            /* Set the width of each button container to 50% */
            margin: 0px;
            /* Adjust margin as needed */
            white-space: nowrap;
        }

        .timeslots-container {
            display: flex;
            /* Use flexbox layout */
            flex-wrap: wrap;
            /* Allow items to wrap to the next line */
            justify-content: center;
            /* Center items horizontally */
            border: 1.75px solid #eee;
            /* Adding a black border */
            margin-bottom: 10px;
            /* Maintaining existing bottom margin */
            max-height: 400px;
            /* Maximum height */
            overflow-y: auto;
            /* Allow vertical scrolling */
            overflow-x: hidden;
            /* Hide horizontal scroll */
            padding: 0px;
            /* Add padding inside the border */
            border-radius: 10px;
            /* Optional: Adds rounded corners to the border */
        }
    </style>

</head>

<body>
    <?php include '../components/admin_header.php'; ?>
    <div class="section">
        <br>
        <div class="container">








            <br>
        </div>
        <br>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.1/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <!-- import javascript and css bootsrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- import popper to use dropdowns, popovers, or tooltips-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="../js/admin_script.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
   <script src="js/sweetalert2.all.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>

    //tooltip

    $(document).ready(function() {
         $('[data-toggle="tooltip"]').tooltip();
      });

    //geturl function
    function getUrlParameter(name) {
         name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
         var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
         var results = regex.exec(location.search);
         return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
      };

    //staff_id change
    document.getElementById('staff_id').addEventListener('change', function() {
         // Get the selected staff ID
         var selectedStaffId = this.value;

         // Get the current value of the date parameter from the URL
         var currentDate = getUrlParameter('date');
         var currentserviceid = getUrlParameter('service_id');
         var currentserviceduration = getUrlParameter('service_duration');

         // Update the URL without refreshing the page
         history.pushState(null, null, '?staff=' + selectedStaffId + '&date=' + currentDate + '&service_id=' + currentserviceid + '&service_duration=' + currentserviceduration);

         // Store the selected staff ID in localStorage
         localStorage.setItem('selectedStaffId', selectedStaffId);

         // Reload the page
         location.reload();
      });
    //service_id change --> change duration value
    //format duration
    //service_id change 

    //datepicker definition
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
         onChange: function(selectedDates, dateStr, instance) {
            // Callback function when date is changed
            console.log('Selected Date:', dateStr);
         },
         onClose: function(selectedDates, dateStr, instance) {
            // Callback function when calendar is closed
            console.log('Flatpickr Closed');
         },
         onOpen: function(selectedDates, dateStr, instance) {
            // Callback function when calendar is opened
            console.log('Flatpickr Opened');
         },
         onReady: function(selectedDates, dateStr, instance) {
            // Callback function when Flatpickr is initialized and ready
            console.log('Flatpickr Ready');
         },
         onMonthChange: function(selectedDates, dateStr, instance) {
            // Callback function when month changes
            console.log('Month Changed');
         },
         onYearChange: function(selectedDates, dateStr, instance) {
            // Callback function when year changes
            console.log('Year Changed');
         },
         onOpen: function(selectedDates, dateStr, instance) {
            // Callback function when calendar is opened
            console.log('Flatpickr Opened');
         },
         onError: function(err) {
            // Callback function for error handling
            console.error(err);
         },
         onValueUpdate: function(selectedDates, dateStr, instance) {
            // Callback function when value is updated
            console.log('Value Updated');
         }
      });
    //datepicker change
    function updateDatepickerFromUrl() {
         var dateFromUrl = getUrlParameter('date');
         if (dateFromUrl) {
            document.getElementById('datepicker').value = dateFromUrl;
         }
      }
      // Call the function to update datepicker value from URL on page load
      updateDatepickerFromUrl();

      // Update datepicker value from URL parameter after page reload
      window.addEventListener('popstate', function() {
         updateDatepickerFromUrl();
      });
    
      document.addEventListener('DOMContentLoaded', function() {
         // Add event listener to date input field
         document.getElementById('datepicker').addEventListener('change', function() {
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
    //duration change


   </script>
</body>

</html>