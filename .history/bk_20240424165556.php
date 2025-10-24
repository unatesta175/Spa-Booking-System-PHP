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
$duration_sv = isset($_GET['duration']) ? $_GET['duration'] : '';

function timeslots($duration, $cleanup, $start, $end, $existingBookings, $duration_sv) {
    // Convert start and end times to timestamps
    $startTime = strtotime($start); // Convert start time to timestamp
    $endTime = strtotime($end); // Convert end time to timestamp

    $slots = array(); // Initialize an array to store available timeslots

    // Convert duration_sv to minutes
    $duration_sv_minutes = intval($duration_sv) * 60;

    // Loop through each timeslot
    for ($currentTime = $startTime; $currentTime < $endTime; $currentTime += ($duration + $cleanup) * 60) {
        // Calculate end time for the current timeslot
        $endTimeSlot = $currentTime + ($duration + $duration_sv_minutes) * 60;

        // Check if the timeslot overlaps with rest time (13:00 to 14:00), end time (19:00 and above), or existing bookings
        $overlap = false; // Flag to indicate if there's an overlap
        foreach ($existingBookings as $booking) {
            $bookingStartTime = strtotime($booking['time']); // Convert booking start time to timestamp
            $bookingEndTime = $bookingStartTime + $booking['duration'] * 60; // Calculate booking end time

            // If the timeslots overlap, set overlap flag to true
            if (($currentTime >= $bookingStartTime && $currentTime < $bookingEndTime) ||
                ($endTimeSlot > $bookingStartTime && $endTimeSlot <= $bookingEndTime)) {
                $overlap = true;
                break;
            }
        }

        // Check if the timeslot overlaps with rest time (13:00 to 14:00) or end time (19:00 and above)
        if (!$overlap && !($currentTime >= strtotime('13:00') && $endTimeSlot <= strtotime('14:00')) && $endTimeSlot <= strtotime('19:00')) {
            // Format timeslot start and end times in 24-hour format
            $slotStartTime = date('H:i', $currentTime); // Format start time
            $slotEndTime = date('H:i', $endTimeSlot); // Format end time

            // Add timeslot to the array
            $slots[] = array(
                'timeslot' => $slotStartTime . ' - ' . $slotEndTime . ' ' . date('A', $currentTime)
            );
        }
    }

    return $slots; // Return the array of available timeslots
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

   </style>
</head>

<body>

   <?php include 'components/user-header.php'; ?>
   <div class="section">
      <br>
      <div class="container">


         <div class="section add-any">
            <form>


               <h1 class="text-center">Tempah untuk tarikh :
                  <?php echo date('m/d/Y', strtotime($date)); ?>
               </h1>
               <div class="inputBox ">
                  <label>Pakar Terapi :<span style="color: red;"></span></label>
                  <select class="box" required placeholder="" name="staff_id" id="staff_id">
                  <option value="" <?php echo isset($_GET['staff']) ? '' : 'selected'; ?>>Sila Pilih Pakar Terapi Anda</option>
                     <?php

                     $result = $conn->prepare("SELECT * FROM `staffs` ORDER BY name ASC");
                     $result->execute();
                     if ($result->rowCount() > 0) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                          <option value="<?php echo $row['id']; ?>" <?php echo (isset($_GET['staff']) && $_GET['staff'] == $row['id']) ? 'selected' : ''; ?>>
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
                           <option value="<?php echo $row['id']; ?>" data-duration="<?php echo $row['duration']+30; ?>">
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




         <br>
      </div>
      <br>
   </div>






   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

  

</body>

</html>