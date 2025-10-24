<?php

include 'components/connect.php';

session_start();

if (isset ($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
}
;

if ($_SERVER["REQUEST_METHOD"] == "POST") {


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

  echo $new_id;

  // Step 3: Insert the new booking with the incremented ID
  $insert_user = $conn->prepare("INSERT INTO `bookings` (id, date, time, duration, remarks, datetimeapplied, claimstat, bookingstat, pay_method, pay_type, pay_amount,  pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $insert_user->execute([$new_id, $date, $time, $duration, $remarks, $currentDateTime, "Pending", "Ditempah", $pay_method, $pay_type, $pay_amount, "Telah Bayar", $user_id, $service_id, $staff_id]);
  $message[] = 'Anda sudah berjaya menempah sesi rawatan';
  $_SESSION['booking_success'] = true;
  // Function to increment ID



}

// if(isset($_SESSION['booking_success'])) {
//     // Prepare JavaScript for showing the Sweet Alert
//     $SuccessScript = "<script>
//             window.onload = function() {
//                 Swal.fire({
//                     title: 'Berjaya!',
//                     text: 'Anda sudah berjaya log masuk!',
//                     icon: 'success',
//                     confirmButtonText: 'OK'

//                 });
//             }
//           </script>";
//     unset($_SESSION['login_success']); // Unset the flag
//  } else {
//     $SuccessScript = "";
//  }

include 'components/wishlist_cart.php';

$dateStr = date('Y-m-d'); // Default to today's date
if (isset ($_GET['date'])) {
  $dateStr = $_GET['date']; // Get the date parameter from the URL
}

// Fetch bookings for the selected date from the database
$stmt = $conn->prepare("SELECT * FROM bookings WHERE date = ?");
$stmt->execute([$dateStr]);
$bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  // Format booking into FullCalendar event object
  $booking = [
    'title' => 'Booked Slot',
    'start' => $row['date'] . 'T' . $row['time'], // Concatenate date and time for start datetime
    'end' => date('Y-m-d H:i:s', strtotime($row['time'] . ' +' . $row['duration'] . ' minutes')), // Calculate end datetime based on duration
    'color' => '#f00', // You can set a different color for bookings
  ];
  $bookings[] = $booking;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap Stepper Example</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
  <section>
    <div class="container mt-5 p-3"
      style="background-color: var(--white);box-shadow: 0 .5rem 1rem rgba(0,0,0,.1); border-radius:15px; ">
      <div class="progress mb-4">
        <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0"
          aria-valuemax="100"></div>
      </div>

      <form id="step1">
        <h2>Pilih tarikh dan masa sesi terapi</h2>
        <div class="form-group">
          < <div class="inputBox">
              <label for="name">Tarikh sesi rawatan:<span style="color: red;"></span></label>
              <input style="font-size:1.3rem;"type="date" name="date" required placeholder="Masukkan tarikh sesi rawatan" class="box">
            </div>

            <div class="inputBox">
              <label for="email">Masa sesi rawatan:<span style="color: red;"></span></label>
              <input type="time" name="time" required placeholder="Masukkan masa sesi rawatan" class="box">
            </div>
        </div>
        <button type="button" class="btns btn-primarys next">Next</button>
      </form>

      <form id="step2" style="display: none;" method="post" action="">
        <h2>Isi Maklumat Tempahan</h2>
          <div class="form-group">
           
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
            if (isset ($_GET['service_id'])) {
              $service_id = $_GET['service_id'];
            } else {
              $service_id = '1';
            }
            $result = $conn->prepare("SELECT * FROM `services` Where id =?");
            $result->execute([$service_id]);
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
                  <?php echo $type_service . ' -  ' . $name_service; ?>
                </option>
                <?php

                $result = $conn->prepare("SELECT * FROM `services` ORDER BY id ASC");
                $result->execute();
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?php echo $row['id']; ?>">
                      <?php echo $row['type'] . ' -  ' . $row['name']; ?>
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
          <button type="button" class="btns btn-secondarys prev">Previous</button>
            <button type="button" class="btns btn-primarys next">Next</button>
      </form>

      <form id="step3" style="display: none;">
        <h2>Step 3</h2>
        <div class="form-group">
          <label for="input3">Input 3</label>
          <input type="text" class="form-control" id="input3" placeholder="Enter Input 3">
        </div>
        <button type="button" class="btns btn-secondarys prev">Previous</button>
       <button type="submit" class="btns btn-success">Tempah</button>
        
      </form>
    </div>

  </section>

  <!-- jQuery and Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function () {
      var currentStep = 1;

      $('.next').click(function () {
        if (currentStep < 3) {
          $('#step' + currentStep).hide();
          currentStep++;
          $('#step' + currentStep).show();
          updateProgressBar();
        }
      });

      $('.prev').click(function () {
        if (currentStep > 1) {
          $('#step' + currentStep).hide();
          currentStep--;
          $('#step' + currentStep).show();
          updateProgressBar();
        }
      });

      function updateProgressBar() {
        var progress = (currentStep - 1) * 50;
        $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
      }
    });
  </script>

</body>

</html>