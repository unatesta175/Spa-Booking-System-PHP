
<?php 
$duration = 15; // Duration of each appointment in minutes
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
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeslot in ajax</title>
</head>
<body>

<form style="box-shadow: none;" action="" method="post">




<div class="inputBox">
   <label for="datepicker">Tarikh sesi rawatan:</label>
   <input style="font-family: 'Gilroymedium', sans-serif;" style="font-size:1.3rem;" type="text"
      name="date" id="datepicker" class="box" placeholder="Date">
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
} ?>




<div class="inputBox ">
   <label>Pakej Rawatan Spa :<span style="color: red;"></span></label>
   <select style="appearance: none; " disabled class="box" name="service_id" id="service_id"
      onchange="updateUrlWithServiceDetails()">
      <option value="<?php echo $_GET['service_id']; ?>" selected
         data-duration='<?php echo $durationsv + 30; ?>'>
         <?php echo $typesv . ' - ' . $namesv; ?>
      </option>
   </select>
</div>

<input type="hidden" id="service_duration" name="service_duration">

<div class="inputBox">
   <label for="name">Tempoh masa sesi rawatan: * <span style="color: red;"></span></label>
   <input aria-readonly style="font-family: 'Gilroymedium', sans-serif;" readonly
      style="font-size:1.3rem;" type="text" value="<?php echo formatDuration($durationsv + 30); ?>"
      name="duration" id="duration" class="box" placeholder="Duration">
</div>

<div class="inputBox">
   <label for="name">Slot Masa * <span style="color: red;"></span></label>
   <input aria-readonly style="font-family: 'Gilroymedium', sans-serif;" readonly
      style="font-size:1.3rem;" type="text" value="" name="timeslot" id="timeslotz" class="box"
      placeholder="Slot Masa">
</div>

<input type="hidden" id="endtime" name="endtime">
<input type="hidden" id="starttime" name="starttime">

<input type="submit" value="Tempah" class="option-btn" name="submit">
</form>
    
<div class="col-md-8 col-sm-6">
               <?php echo isset($msg) ? $msg : ""; ?> <!-- Display any message if set -->

               <?php if (!empty($_GET['date']) && !empty($_GET['staff'])): ?>
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
                           ?>
                           <div style="margin:5px;" class="form-group m-3">
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
                        <?php }
                        ?>
                     </div>
                  <?php }
                  ?>
               <?php else: ?>
                  <h1 id="selectedDate" class="text-center" style="margin:20px; font-size:2rem;">Sila pilih tarikh tempahan
                     dan pakar terapi untuk lihat slot masa yang terbuka</h1>
                  <!-- Display message for selecting date and staff -->
                  <div class="timeslots-container">
                     <div style="margin:1rem !important;" class='alert alert-secondary'>Ini ruangan untuk slot masa
                        tempahan.</div> <!-- Display default message -->
                  </div>
               <?php endif; ?>
            </div>


</body>
</html>