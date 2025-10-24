<?php

include '../components/connect.php'; ?>

<!-- <table class="table table-striped table-responsive">
	<tr>
		<th>No Tempahan </th>
		<th>Tarikh Tempahan </th>
		<th>Pakej Terapi</th>
		<th>Nama Pakar Terapi</th>
		<th>Slot Masa Tempahan</th>
		<th>Tempoh Masa</th>

	</tr> -->
	<?php
  
	    $existingBookings = [];
	

		$stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? AND staff_id = ?");
		$stmt->execute([$_POST['date'], $_POST['staff']]);

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$existingBookings[] = $row;
		}
	
		
$duration = $_POST['duration']; // Duration of each appointment in minutes
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


	// $select_accounts = $conn->prepare("SELECT * FROM `bookings` WHERE service_id = ? AND staff_id = ? AND date = ?");
	// $select_accounts->execute([$_POST['service'], $_POST['staff'], $_POST['date']]);
	// if ($select_accounts->rowCount() > 0) {
	// 	while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


	// 		echo "<tr>
	// 			   <td>" . $row['booking_id'] . "</td>
	// 			   <td>" . $row['date'] . "</td>
	// 			   <td>" . $row['service_id'] . "</td>
	// 			   <td>" . $row['staff_id'] . "</td>
	// 			   <td>" . $row['timeslot'] . "</td>
	// 			   <td>" . $row['duration'] . "</td>
	// 		   </tr>";
	// 	}
	// }


	?>
<!-- </table> -->

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
            
            </div>
            <div class="col-md-8 col-sm-6">
               

               <?php if (!empty($_POST['date']) && !empty($_POST['staff'])) : ?>
                  <!-- Check if date and staff parameters are not empty in the URL -->
                  <?php
                  // Check if the selected date is a Tuesday
                  $selectedDate = new DateTime($_POST['date']); // Create a DateTime object from the selected date
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
                        <?php echo $_POST['date']; ?>
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
               <a href="booking-record.php" class="btns btn-secondary"><i class="fas fa-arrow-left"></i>Kembali ke
                  Pakej</a>
            </div>
         </div>






         <br>
      </div>