<?php
// Include database connection and start session
include 'components/connect.php';
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Process form submission if any
if (isset($_POST['submit'])) {
    // Sanitize input data
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);
    $remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
    $staff_id = filter_var($_POST['staff_id'], FILTER_SANITIZE_STRING);
    $service_id = filter_var($_POST['service_id'], FILTER_SANITIZE_STRING);
    $pay_type = filter_var($_POST['pay_type'], FILTER_SANITIZE_STRING);

    // Fetch service details for duration and price
    $result = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
    $result->execute([$service_id]);

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $duration_service = $row['duration'];
            $pay_amount = $row['price'];
        }
    }

    // Calculate duration and set payment method
    $duration = $duration_service + 30;
    $pay_method = "Perbankan Dalam Talian";

    // Get current date and time
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');

    // Generate new booking ID
    $get_latest_id = $conn->query("SELECT id FROM bookings ORDER BY id DESC LIMIT 1");
    $latest_id_row = $get_latest_id->fetch(PDO::FETCH_ASSOC);

    function incrementId($id)
    {
        $prefix = substr($id, 0, 2);
        $number = (int) substr($id, 2);
        $number++;
        return $prefix . sprintf('%08d', $number);
    }

    if ($latest_id_row) {
        $latest_id = $latest_id_row['id'];
        $new_id = incrementId($latest_id);
    } else {
        $new_id = 'KB00000001';
    }

    // Insert new booking
    $insert_user = $conn->prepare("INSERT INTO `bookings` (id, date, time, duration, remarks, datetimeapplied, claimstat, bookingstat, pay_method, pay_type, pay_amount,  pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_user->execute([$new_id, $date, $time, $duration, $remarks, $currentDateTime, "Pending", "Ditempah", $pay_method, $pay_type, $pay_amount, "Telah Bayar", $user_id, $service_id, $staff_id]);
    $message[] = 'Anda sudah berjaya menempah sesi rawatan';
}
$dateStr = 2024-03-13;
// Fetch bookings for the selected date and within business hours from the database
$stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ? AND (TIME(time) BETWEEN '10:00:00' AND '19:00:00')");
$stmt->execute([$dateStr]);
$bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $booking = [
        'title' => 'Booked Slot',
        'start' => $row['date'] . 'T' . $row['time'],
        'end' => date('Y-m-d H:i:s', strtotime($row['time'] . ' +' . $row['duration'] . ' minutes')),
        'color' => '#f00',
    ];
    $bookings[] = $booking;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Your meta tags, CSS links, and other headers go here -->
</head>
<body>
   
   <!-- Your HTML content including the form and other elements -->

   <div id='calendar'></div>

   <!-- Your footer content including JavaScript imports and scripts -->

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         var calendarEl = document.getElementById('calendar');

         var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay',
            initialDate: '2024-03-21',
            slotDuration: '00:15:00',
            slotLabelInterval: '00:15:00',
            headerToolbar: {
               left: 'prev,next today',
               center: 'title',
               right: 'dayGridMonth,timeGridWeek,timeGridDay,listDay'
            },
            businessHours: {
               startTime: '10:00',
               endTime: '19:00',
               daysOfWeek: [0, 1, 3, 4, 5, 6]
            },
            events: <?php echo json_encode($bookings); ?>
         });

         calendar.render();
      });
   </script>

</body>
</html>
