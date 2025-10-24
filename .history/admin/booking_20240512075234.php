<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin-login.php');
}


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

if (isset($_GET['service_duration'])) {
    $dura = $_GET['service_duration'];
} else {
    $dura = '';
};

if (isset($_GET['date'])) {
    $requestedDate = new DateTime($_GET['date']); // Convert the 'date' parameter to a DateTime object
    $requestedDate->setTime(0, 0, 0); // Set time to midnight

    $today = new DateTime(); // Get the current date
    $today->setTime(0, 0, 0); // Set time to midnight

    // Check if the requested date is not today or if it's in the past
    if ($requestedDate < $today) {
        // Redirect to a forbidden page or display an error message
        header('HTTP/1.0 403 Forbidden');
        echo "Forbidden: You are not allowed to access past dates.";
        exit;
    }
} else {
    // No date provided, allow access
    $requestedDate = new DateTime();
}

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

    $r1 = $conn->prepare("SELECT * FROM `clients` WHERE id = ?");
    $r1->execute([$user_id]);
    if ($r1->rowCount() > 0) {
        while ($rr1 = $r1->fetch(PDO::FETCH_ASSOC)) {


            $phoneno = $rr1['phoneno'];
            $email = $rr1['email'];
            $name = $rr1['name'];
        }
    }

    // Set timezone to Kuala Lumpur
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');

    // Define the incrementId function
    function incrementId($id)
    {
        // Extract the numeric part of the ID
        $number = (int) substr($id, 2);

        // Increment the numeric part by 1
        $number++;

        // Format the incremented number with leading zeros and concatenate with the prefix
        return 'KB' . sprintf('%08d', $number);
    }

    // Connect to your database
    // Assuming you have already created a PDO connection object $conn

    // Retrieve the latest ID from the database
    $get_latest_id = $conn->query("SELECT booking_id FROM bookings ORDER BY id DESC LIMIT 1");
    $latest_id_row = $get_latest_id->fetch(PDO::FETCH_ASSOC);

    // Step 2: Increment the retrieved ID to generate the new ID
    if ($latest_id_row) {
        $latest_id = $latest_id_row['booking_id'];
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
        $insert_user = $conn->prepare("INSERT INTO `bookings` (booking_id, timeslot, date, starttime, endtime, duration, datetimeapplied, claimstat, bookingstat, pay_amount, pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_user->execute([$new_id, $timeslot, $date, $starttime, $endtime, $duration, $currentDateTime, "Pending", "Ditempah", $pay_amount, "Belum Bayar", $user_id, $service_id, $staff_id]);

        $query = $conn->query("SELECT MAX(booking_id) FROM bookings");
        $last_inserted_id = $query->fetchColumn();

        $bruh = (1 * 100);
        $rmx100 = ($pay_amount * 100);
        $some_data = array(
            'userSecretKey' => 'm8zfj65c-2fzo-gq3b-rwhw-xvneusqy7wuy',
            'categoryCode' => '2n8qqo61',
            'billName' => $last_inserted_id,
            'billDescription' => $name,
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            // 'billAmount' => $rmx100,
            'billAmount' =>  $bruh,
            'billReturnUrl' => 'index.php',
            'billCallbackUrl' => '',
            'billExternalReferenceNo' => '',
            'billTo' => $name,
            'billEmail' => $email,
            'billPhone' => $phoneno,
            'billSplitPayment' => 0,
            'billSplitPaymentArgs' => '',
            'billPaymentChannel' => 0,
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $obj = json_decode($result, true);
        $billcode = $obj[0]['BillCode'];

        $update_admin_pass = $conn->prepare("UPDATE `bookings` SET billcode = ? WHERE booking_id = ?");
        $update_admin_pass->execute([$billcode, $last_inserted_id]);

        // Push successful booking to the $bookings array
        $bookings[] = $timeslot;
        $_SESSION['book_success'] = true; // Set login success flag
        header('location:record.php');
        exit;
        // Set success message
        // $msg = "<div class='alert alert-success'>Tempahan anda berjaya!</div>";
    }
}

$duration = $dura; // Duration of each appointment in minutes
$cleanup = 0; // Time gap between appointments in minutes
$start = "10:00"; // Start time for appointments
$end = "19:00"; // End time for appointments
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
                        <form style="box-shadow: none;" action="" method="post">




                            <div class="inputBox">
                                <label for="datepicker">Tarikh sesi rawatan:</label>
                                <input style="font-family: 'Gilroymedium', sans-serif;" style="font-size:1.3rem;" type="text" name="date" id="datepicker" class="box" placeholder="Date">
                            </div>
                            <div class="inputBox ">
                                <label>Pakar Terapi :<span style="color: red;"></span></label>
                                <select class="box" required placeholder="" name="staff_id" id="staff_id">
                                    <option value="" selected>Sila Pilih Pakar Terapi Anda</option>
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

                            $result = $conn->prepare("SELECT * FROM `services` WHERE id =?");
                            $result->execute([$_GET['service_id']]);
                            if ($result->rowCount() > 0) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                                    $namesv = $row['name'];
                                    $typesv = $row['type'];
                                    $durationsv = $row['duration'];
                                }
                            } 
                            ?>

                                  if()$_ISSET$durationsv


                            <div class="inputBox ">
                                <label>Pakej Rawatan Spa :<span style="color: red;"></span></label>
                                <select required placeholder="" class="box" name="service_id" id="service_id" onchange="updateUrlWithServiceDetails()">
                                    <option value="" selected data-duration='<?php echo $durationsv + 30; ?>' selected>Sila Pilih Pakej Rawatan Anda </option>
                                    <?php

                                    $result = $conn->prepare("SELECT * FROM `services` ORDER BY type ASC");
                                    $result->execute();
                                    if ($result->rowCount() > 0) {
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?php echo $row['id']; ?>">
                                                <?php echo $row['type'].' - '. $row['name']; ?>
                                            </option>
                                    <?php }
                                    } ?>

                                </select>
                            </div>

                            <input type="hidden" id="service_duration" name="service_duration">

                            <div class="inputBox">
                                <label for="name">Tempoh masa sesi rawatan: * <span style="color: red;"></span></label>
                                <input aria-readonly style="font-family: 'Gilroymedium', sans-serif;" readonly style="font-size:1.3rem;" type="text" value="<?php echo formatDuration($durationsv + 30); ?>" name="duration" id="duration" class="box" placeholder="Duration">
                            </div>

                            <div class="inputBox">
                                <label for="name">Slot Masa * <span style="color: red;"></span></label>
                                <input aria-readonly style="font-family: 'Gilroymedium', sans-serif;" readonly style="font-size:1.3rem;" type="text" value="" name="timeslot" id="timeslotz" class="box" placeholder="Slot Masa">
                            </div>

                            <input type="hidden" id="endtime" name="endtime">
                            <input type="hidden" id="starttime" name="starttime">

                            <input type="submit" value="Tempah" class="option-btn" name="submit">
                        </form>
                    </div>

                </div>
                <div class="col-md-8 col-sm-6">
                    <?php echo isset($msg) ? $msg : ""; ?> <!-- Display any message if set -->

                    <?php if (!empty($_GET['date']) && !empty($_GET['staff'])) : ?>
                        <!-- Check if date and staff parameters are not empty in the URL -->
                        <?php
                        // Check if the selected date is a Tuesday
                        $selectedDate = new DateTime($_GET['date']); // Create a DateTime object from the selected date
                        if ($selectedDate->format('N') == 2) { // Check if the day of the week is Tuesday (2)
                        ?>
                            <h1 id="selectedDate" class="text-center" style="margin:20px; font-size:2rem;">Anda pilih tarikh pada
                                hari Selasa iaitu hari tutup Kapas Beauty Spa</h1> <!-- Display message for Tuesday -->
                            <div class="timeslots-container">
                                <div style="margin:1rem !important;" class='alert alert-secondary'>Tiada slot masa tempahan terbuka
                                    pada hari Selasa.</div> <!-- Display message for no available slots on Tuesday -->
                            </div>
                        <?php } else { ?>
                            <h1 id="selectedDate" class="text-center" style="margin:20px; font-size:2.5rem;">Tarikh Tempahan :
                                <?php echo $_GET['date']; ?>
                            </h1> <!-- Display selected date -->
                            <div class="timeslots-container">
                                <?php
                                $timeslots = timeslots($duration, $cleanup, $start, $end, $existingBookings); // Get available timeslots
                                foreach ($timeslots as $ts) {
                                    // Convert the timeslot string to a timestamp
                                    $timestamp = strtotime(explode(' - ', $ts['timeslot'])[0]);

                                    // Extract the start time with AM/PM information
                                    $start_time = date('h:i A', $timestamp);

                                    // Calculate end time
                                    $end_time = date('h:i A', $timestamp + $duration * 60);

                                    // Set tooltip title
                                    $tooltip_title = $start_time . ' - ' . $end_time;


                                ?>
                                    <div style="margin:5px;" class="form-group m-3">
                                        <?php
                                        // Check if the timeslot is booked
                                        if ($ts['overlap']) { ?>
                                            <button class="btns baby btn-danger book" data-timeslot="<?php echo $ts['timeslot']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $tooltip_title; ?>">
                                                <?php echo $start_time; ?> <!-- Display start time with AM/PM -->
                                            </button>
                                        <?php } else { ?>
                                            <button class="btns baby btn-success book" data-timeslot="<?php echo $ts['timeslot']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $tooltip_title; ?>">
                                                <?php echo $start_time; ?> <!-- Display start time with AM/PM -->
                                            </button>
                                        <?php } ?>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        <?php }
                        ?>
                    <?php else : ?>
                        <h1 id="selectedDate" class="text-center" style="margin:20px; font-size:2rem;">Sila pilih tarikh tempahan
                            dan pakar terapi untuk lihat slot masa yang terbuka</h1>
                        <!-- Display message for selecting date and staff -->
                        <div class="timeslots-container">
                            <div style="margin:1rem !important;" class='alert alert-secondary'>Ini ruangan untuk slot masa
                                tempahan.</div> <!-- Display default message -->
                        </div>
                    <?php endif; ?>
                </div>


                <div class="container mt-3">
                    <a href="service-package.php" class="btns btn-secondary"><i class="fas fa-arrow-left"></i>Kembali ke
                        Pakej</a>
                </div>
            </div>






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
   <script>

    //tooltip
    //geturl function
    //staff_id change
    //service_id change
    //format duration
    

   </script>
</body>

</html>