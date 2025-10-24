<?php

include 'components/connect.php';

session_start();

if (isset ($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

if (isset ($_POST['submit'])) {


   $date = $_POST['date'];
   $date = filter_var($date, FILTER_SANITIZE_STRING);
   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING);
 
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
   
   $duration = $duration_service +30;
   
   $pay_method="Perbankan Dalam Talian";



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

   echo $new_id;

   // Step 3: Insert the new booking with the incremented ID
   $insert_user = $conn->prepare("INSERT INTO `bookings` (id, date, time, duration, remarks, datetimeapplied, claimstat, bookingstat, pay_method, pay_type, pay_amount,  pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
   $insert_user->execute([$new_id, $date, $time, $duration, $remarks, $currentDateTime, "Pending", "Ditempah", $pay_method, $pay_type, $pay_amount, "Telah Bayar", $user_id, $service_id, $staff_id]);
   $message[] = 'Anda sudah berjaya menempah sesi rawatan';

   // Function to increment ID
   

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
/* Minimalist styling for FullCalendar */

/* Overall container */
#calendar {
    max-width: 500px;
    margin: 40px auto;
    font-family: Arial, sans-serif;
}


.fc .fc-col-header-cell-cushion {
    display: inline-block;
    padding: 2px 4px;
    text-decoration: none
}

.fc .fc-daygrid-day-number {
    padding: 4px;
    position: relative;
    z-index: 4;
    text-decoration: none
}

.fc table {
    border-collapse: collapse;
    border-spacing: 0px;
    font-size: 1.5rem;
}
/* Responsive styling */
@media screen and (max-width: 768px) {
    #calendar {
        margin: 20px auto;
    }
}

</style>
</head>
<body>
   
<?php include 'components/user-header.php'; ?>


<section class="add-any">

<h1 class="heading">Tempah Sesi Rawatan</h1>

<form action="" method="post">
   <div class="flex">
      <div class="inputBox">
         <label for="name">Tarikh sesi rawatan:<span style="color: red;"></span></label>
         <input type="date"  name="date" required placeholder="Masukkan tarikh sesi rawatan" 
            class="box">
      </div>

      <div class="inputBox">
         <label for="email">Masa sesi rawatan:<span style="color: red;"></span></label>
         <input type="time"  name="time" required placeholder="Masukkan masa sesi rawatan" 
            class="box"> 
      </div>
      <div class="inputBox">
         <label for="name">Pesanan:<span style="color: red;"></span></label>
         <input type="text" name="remarks" required placeholder="Masukkan pesanan anda" maxlength="200"
            class="box">
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

      <?php

      $result = $conn->prepare("SELECT * FROM `services` Where id =?");
$result->execute([$_GET['service_id']]);
      if ($result->rowCount() > 0) {
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
           
               $name_service = $row['name']; 
               $type_service = $row['type']; 
         }
      } 
      
      
      ?>

      <div class="inputBox ">
         <label>Pakej Rawatan Spa :<span style="color: red;"></span></label>
         <select class="box" required placeholder="Pilih Pakej Rawatan Anda" name="service_id">
            <option value="<?php echo $_GET['service_id']; ?>" selected hidden>
               <?php echo $type_service .' -  '.$name_service; ?>
            </option>
            <?php

$result = $conn->prepare("SELECT * FROM `services` ORDER BY id ASC");
            $result->execute();
            if ($result->rowCount() > 0) {
               while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                  <option value="<?php echo $row['id']; ?>">
                     <?php echo $row['type'].' -  '.$row['name']; ?>
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
</section>


<div id='calendar'>
</div>


<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: '2024-03-21',
        slotDuration: '00:15:00',   // Each slot is 15 minutes
      slotLabelInterval: '00:15:00', // Show time labels every 15 minutes
     
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listDay'
        },
        businessHours: {            // Define business hours
            startTime: '10:00',    // Start time
            endTime: '19:00',      // End time
            daysOfWeek: [0,1, 3, 4, 5,6] // Business hours for Monday to Friday
        },
        
        events: [
            <?php
            // Fetch booking data from the database
            $bookings_query = $conn->prepare("SELECT * FROM `bookings`");
            $bookings_query->execute();
            while ($booking = $bookings_query->fetch(PDO::FETCH_ASSOC)) {
                // Format the start date and time
                $start_datetime = $booking['date'] . 'T' . $booking['time'];

                // Calculate the end time by adding the duration to the start time
                $start_timestamp = strtotime($start_datetime);
                $end_timestamp = $start_timestamp + ($booking['duration'] * 60); // Convert duration to seconds

                // Format the end date and time
                $end_datetime = date('Y-m-d\TH:i:s', $end_timestamp);
                
                // Output the event
                echo "{";
                echo "title: '" . $booking['remarks'] . "',";
                echo "start: '" . $start_datetime . "',";
                echo "end: '" . $end_datetime . "'";
                echo "},";
            }
            ?>
        ]
    });

    calendar.render();
});
</script>


</body>
</html>