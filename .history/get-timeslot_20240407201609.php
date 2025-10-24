<?php
include 'components/connect.php';

if(isset($_GET['staff_id'])) {
   $staffId = $_GET['staff_id'];
   // Perform database query to fetch available timeslots for the selected staff
   // Modify the query according to your database structure and requirements
   $stmt = $conn->prepare("SELECT DISTINCT timeslot FROM bookings WHERE staff_id = ?");
   $stmt->execute([$staffId]);

   // Construct HTML options for timeslots dropdown
   $options = '';
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $options .= '<option value="' . $row['timeslot'] . '">' . $row['timeslot'] . '</option>';
   }

   echo $options; // Output HTML options
}
?>
