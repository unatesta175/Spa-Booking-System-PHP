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
    includeHeaderAdmin();
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
    <!-- WAJIB FOR AJAX CALL -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>




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
    <script type="text/javascript">
        $(document).ready(function() {

            $("#timeslot_div").load("getInitialTimeslot.php");


        });
    </script>
</head>

<body>
<?php include '../components/staff-header.php'; ?>
    <div class="section">
        <br>
        <div class="container">
            <br>

            <div class="row" style="background-color:white; border-radius: 25px; padding:15px; box-shadow: var(--box-shadow);">
                <h1 class="text-center" style=" font-size: 4rem;
   color:var(--black);
   margin-bottom: 2rem;
   text-align: center;
   text-transform: capitalize;
   font-family: 'Gilroymedium', sans-serif !important;
   font-weight: 800 !important;">Tempah Sesi Rawatan</h1>
                <div class="col-md-4 col-sm-6">
                    <div class="section add-any">
                        <form id="registration_form" style="box-shadow: none;" action="javascript:void(0)" method="post">
                            <div class="inputBox">
                                <label>Pengguna :</span> </label>

                                <select name="client" required placeholder="Pilih Pengguna" class="box" class="form-control" id="client">
                                    <option>Pilih Pengguna</option>
                                    <?php


                                    $select_accounts = $conn->prepare("SELECT * FROM `clients`");
                                    $select_accounts->execute();
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="inputBox">
                                <label for="date">Tarikh sesi rawatan : </label>
                                <input style="font-family: 'Gilroymedium', sans-serif;" style="font-size:1.3rem;" type="text" name="date" id="date" class="box" placeholder="Date">
                            </div>

                            <?php


                                $select_accounts = $conn->prepare("SELECT * FROM `staffs` WHERE id =?");
                                    $select_accounts->execute([$staff_id]);
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                                           . $row['id'] . ">" . $row['name'] . "</option>";
                                        }
                                    }

                                    ?>
                            <div class="inputBox">
                                <label>Pakar Terapi :<span style="color: red;"></span></label>
                                <select class="box" required placeholder="Pilih Pakar Terapi" name="staff" class="form-control" id="staff">
                                    <option>Pilih Pakar Terapi</option>
                                    
                                </select>

                            </div>

                            <div class="inputBox">
                                <label>Pakej Rawatan Spa :</label>
                                <select name="service" placeholder="Pilih Pakej Rawatan" class="box" id="service">
                                    <option>Pilih Pakej Rawatan</option>
                                    <?php
                                    $select_accounts = $conn->prepare("SELECT * FROM `services`");
                                    $select_accounts->execute();
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

                                            echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                                        }
                                    }


                                    ?>
                                </select>
                            </div>
                            <div class="inputBox">

                                <label for="name">Tempoh masa sesi rawatan : </label>
                                <input readonly class="box" placeholder="Tempoh Masa" type="text" name="duration" id="duration">
                            </div>

                            <div class="inputBox">
                                <label for="name">Slot Masa : </label>
                                <input style="font-family: 'Gilroymedium', sans-serif;" readonly style="font-size:1.3rem;" type="text" name="timeslot" id="timeslotz" class="box" placeholder="Slot Masa">
                            </div>

                            <div class="row" id='test'></div>

                            <input type="hidden" id="endtime" name="endtime">
                            <input type="hidden" id="starttime" name="starttime">

                            <button type="submit" id="Submit" class="option-btn">Tempah</button>
                            <div id="messages"></div>
                            <!-- <button type="button" name="refresh" id="refresh" class="btn btn-primary">Refresh</button> -->
                        </form>
                    </div>
                </div>

                <div class="col-md-8 col-sm-6" id='timeslot_div'>

                </div>
                <div class="container mt-3">
                    <a href="booking-record.php" class="btns btn-secondary"><i class="fas fa-arrow-left"></i>Kembali ke
                        Pakej</a>
                </div>
            </div>
            <br>
        </div>
        <br>
    </div>



    <!-- import javascript and css bootsrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- import popper to use dropdowns, popovers, or tooltips-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="../js/staff_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr
        flatpickr('#date', {
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
    </script>


    <script>
        $(document).ready(function() {
            $("#Submit").click(function(e) {
                e.preventDefault(); // Prevent the default form submission behavior

                // Collect form data
                var client = $("#client").val();
                var date = $("#date").val();
                var staff = $("#staff").val();
                var service = $("#service").val();
                var duration = $("#duration").val();
                var timeslot = $("#timeslotz").val();
                var starttime = $("#starttime").val();
                var endtime = $("#endtime").val();
                var clientname = $("#clientname").val();
                var staffname = $("#staffname").val();
                var servicename = $("#servicename").val();

                // Perform input validation
                if (client === "Pilih Pengguna" || date === "Date" || staff === "Pilih Pakar Terapi" || service === "Pilih Pakej Rawatan" || duration === "Tempoh Masa" || starttime === "") {
                    // Display an error message
                    Swal.fire({
                        title: 'Input Validation',
                        text: 'Sila lengkapkan semua maklumat tempahan.',
                        icon: 'error',
                        customClass: {
                            popup: 'my-custom-popup',
                            title: 'my-custom-title',
                            content: 'my-custom-content', // Apply custom CSS to all text components
                            confirmButton: 'my-custom-confirm-button',
                        }
                    });
                } else {


                    var formattedDate = formatDate(date);
                    var formattedStartTime = formatTime(starttime);
                    var formattedEndTime = formatTime(endtime);

                    var bookingInfo = "Tarikh : <strong>" + formattedDate + "</strong><br>" +
                        "Pelanggan : <strong>" + clientname + "</strong> <br>" +
                    "Pakar Terapi : <strong>" + staffname + "</strong> <br>" +
                        "Rawatan : <strong>" + servicename + "</strong> <br>" +
                        "Tempoh Masa : <strong>" + duration + "</strong> <br>" +
                        "Slot Masa : <strong>" + formattedStartTime + " - " + formattedEndTime + "</strong> <br>";


                    // Display a confirmation dialog
                    Swal.fire({
                        title: 'Hantar maklumat tempahan',
                        // text: bookingInfo + "\n\nAdakah anda pasti untuk menempah?",
                        html: bookingInfo, // Use html instead of text
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Teruskan tempahan !',
                        cancelButtonText: 'Tidak, batalkan tempahan!',
                        customClass: {
                            popup: 'my-custom-popup',
                            title: 'my-custom-title',
                            text: 'my-custom-text',
                            confirmButton: 'my-custom-confirm-button',
                            cancelButton: 'my-custom-cancel-button'
                        }
                    }).then((result) => {
                        // If the user confirms, proceed with the AJAX request
                        if (result.isConfirmed) {
                            // Submit form data using AJAX
                            $.ajax({
                                url: "getBookingInsertion.php",
                                method: "post",
                                data: $("#registration_form").serialize(),
                                // beforeSend: function() {
                                //     $("#messages").html('<br><span class="spinner-border fast" style="width: 2rem; height: 2rem;color:green;" role="status"></span>');
                                // },
                                success: function(Response) {
                                    $("#messages").html(Response);
                                    $("#registration_form")[0].reset();
                                    $("#timeslot_div").load("getInitialTimeslot.php");
                                    setTimeout(function() {
                                        window.location.href = 'booking-record.php';
                                    }, 1000);
                                },
                            });
                        } else {
                            // If the user cancels, do nothing
                            // If the user cancels, do nothing
                            Swal.fire({
                                title: 'Proses terbatal',
                                text: 'Tempahan anda tidak berjaya dan terbatal.',
                                icon: 'info',
                                customClass: {
                                    popup: 'my-custom-popup',
                                    title: 'my-custom-title',
                                    content: 'my-custom-content', // Apply custom CSS to all text components
                                    confirmButton: 'my-custom-confirm-button',
                                    cancelButton: 'my-custom-cancel-button'
                                }
                            });
                            // location.reload();
                        }
                    });
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to format the date
        function formatDate(dateString) {
            var date = new Date(dateString);
            var options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }

        // Function to format the time
        function formatTime(timeString) {
            var timeParts = timeString.split(":");
            var hour = parseInt(timeParts[0]);
            var minute = parseInt(timeParts[1]);
            var meridian = hour >= 12 ? 'PM' : 'AM';
            hour = hour % 12 || 12;
            return hour + ':' + ('0' + minute).slice(-2) + ' ' + meridian;
        }
    </script>
</body>

</html>