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
      $msg = "<div class='alert alert-danger'>Anda tidak boleh menempah slot masa ini dengan ahli terapi yang sama /Sudah ada tempahan bertindih untuk ahli terapi yang dipilih!</div>";
   } else {
      // Insert new booking if the timeslot is available
      $insert_user = $conn->prepare("INSERT INTO `bookings` (id, timeslot, date, starttime, endtime, duration, datetimeapplied, claimstat, bookingstat, pay_amount, pay_stat, client_id, service_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $insert_user->execute([$new_id, $timeslot, $date, $starttime, $endtime, $duration,  $currentDateTime, "Pending", "Ditempah", $pay_amount, "Belum Bayar", $user_id, $service_id, $staff_id]);

      // Push successful booking to the $bookings array
      $bookings[] = $timeslot;
  header('location:booking.php');
      // Set success message
      $msg = "<div class='alert alert-success'>Tempahan anda berjaya!</div>";
   }


}

$duration = 15;
$cleanup = 0;
$start = "10:00";
$end = "19:00";
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

   <script src="js/jquery.dataTables.min.js"></script>
   <script src="js/dataTables.bootstrap.min.js"></script>
   <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />

   <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
   <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
   <!-- Ending of Data tables requirements -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">



   <style>


   </style>
</head>

<body>

   <?php include 'components/user-header.php'; 
   ?>
   <?php echo $loginSuccessScript; ?>
   <div class="section">
      <br>
       <!-- start of Tables -->
   <div class="container">

<div class="row">
   <div class="col-lg-12">
      <div class="card card-default rounded-0 shadow">
         <div class="card-header">
            <div class="row">
               <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                  <h3 class="card-title">Senarai Pengguna</h3>
               </div>
               <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-end">
                  <button type="button" class="option-btn"><a href="addStudentcar.php"
                        style="text-decoration: none;" class="text-light">Tambah user</a></button>
               </div> -->
            </div>
            <div style="clear:both"></div>
         </div>
         <div class="card-body">

            <div class="button-container">
               <button class="option-btn" id="export-btn">
                  <i class="fa-regular fa-file-excel"></i> Eksport ke Excel
               </button>
               <button class="option-btn" id="pdf-btn">
                  <i class="fa-regular fa-file-pdf"></i> Eksport ke PDF
               </button>
            </div>
            

            <div class="row">
               <div class="col-sm-12 table-responsive">
                  <table id="myTable" class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           <th>No.</th>
                           <th>ID </th>
                           <th>Nama </th>
                           <th>I/C</th>
                           <th>Emel</th>
                           <th>No.Tel</th>
                           <th>Tarikh Lahir</th>
                           <th>Alamat</th>
                           <th>Status Perkahwinan </th>
                           <th>Agama</th>
                           <th>Pekerjaan</th>
                           <th>Syarikat</th>
                           <th>FBN </th>
                           <th>IGN</th>
                           <th>Alahan</th>
                           <th>Tindakan</th>
                        </tr>

                     </thead>
                     <tbody>
                        <?php
                        $no = 1;
                        $select_accounts = $conn->prepare("SELECT * FROM `clients`");
                        $select_accounts->execute();
                        if ($select_accounts->rowCount() > 0) {
                           while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


                              $id = $row['id'];
                              $name = $row['name'];
                              $ic = $row['ic'];
                              $email = $row['email'];
                              $phoneno = $row['phoneno'];
                              $datebirth = $row['datebirth'];
                              $address = $row['address'];
                              $maritalstat = $row['maritalstat'];
                              $religion = $row['religion'];
                              $occupation = $row['occupation'];
                              $company = $row['company'];
                              $fb = $row['fb'];
                              $ig = $row['ig'];
                              $allergy = $row['allergy'];
                              $datetimeregistered = $row['datetimeregistered'];
                              



                              echo '<tr>
                               <td>' . $no . '</td>
                               <td>' . $id . '</td>
                               <td>' . $name . '</td>
                               <td>' . $ic . '</td>
                               <td>' . $email . '</td>
                               <td>' . $phoneno . '</td>
                               <td>' . $datebirth . '</td>
                               <td>' . $address . '</td>
                               <td>' . $maritalstat . '</td>
                               <td>' . $religion . '</td>
                               <td>' . $occupation . '</td>
                               <td>' . $company . '</td>
                               <td>' . $fb . '</td>
                               <td>' . $ig . '</td>
                               <td>' . $allergy . '</td>
                           
                               <td>
      <a href="client-accounts.php?delete=' . $id . '" onclick="return confirm(\'Adakah anda pasti untuk buang akaun ini? Maklumat berkaitan pengguna ini akan dibuang!\');" class="delete-btn">Delete</a>

  
      </td>
                               </tr>';
                              $no++;
                           }
                        }
                        ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<!-- End of Tables -->






   <?php include 'components/footer.php'; ?>

 


<!-- start of Tables -->
<script>
      $(document).ready(function () {
         $('#myTable').DataTable({
            "language": {
               "search": "Cari:",
               "lengthMenu": "Papar _MENU_ rekod setiap halaman",
               "zeroRecords": "Tiada data yang sepadan",
               "info": "Papar halaman _PAGE_ dari _PAGES_",
               "infoEmpty": "Tiada rekod",
               "infoFiltered": "(disaring dari jumlah _MAX_ rekod)"
            }
         });
      });
   </script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.1/xlsx.full.min.js"></script>
   <script>
      var table = document.getElementById("myTable");

      document.getElementById("export-btn").addEventListener("click", function () {
         var wb = XLSX.utils.table_to_book(table);
         XLSX.writeFile(wb, "Client Data.xlsx");
      });
   </script>

   <script>
document.getElementById("pdf-btn").addEventListener("click", function () {
    const doc = new jspdf.jsPDF();
    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 5; // Constant margin for the page
    const usableWidth = pageWidth - (2 * margin); // Calculate usable width

    const headers = [["No.", "ID", "Nama", "I/C", "Emel", "No.Tel", "Tarikh Lahir", "Alamat", "Status Perkahwinan", "Agama", "Pekerjaan", "Syarikat", "FBN", "IGN", "Alahan"]];
    const numberOfColumns = headers[0].length;
    const columnWidth = usableWidth / numberOfColumns; // Evenly distribute column width

    // Gather data, excluding 'Tindakan' column
    const data = [];
    document.querySelectorAll("#myTable tbody tr").forEach(row => {
        const rowData = [];
        row.querySelectorAll("td").forEach((cell, index, cells) => {
            if (index < cells.length - 1) { // Exclude the 'Tindakan' column
                rowData.push(cell.innerText);
            }
        });
        data.push(rowData);
    });

    doc.autoTable({
        head: headers,
        body: data,
        theme: 'grid',
        startY: 20, // Start 20 units down from the top of the page
        styles: {
            fontSize: 3, // Reduced font size for content
            cellPadding: 2, // Constant cell padding
            overflow: 'linebreak', // Allow text wrapping for content
            cellWidth: columnWidth, // Apply calculated column width
        },
        headStyles: {
            fillColor: [255, 255, 255],
            textColor: [0, 0, 0],
            fontStyle: 'bold',
            overflow: 'visible', // Prevent text wrapping in headers
            cellPadding: 2, // Ensure padding is consistent with body
            cellWidth: columnWidth, // Apply calculated column width to headers as well
        },
        margin: { horizontal: margin, top: 20, bottom: 20 }, // Apply constant margins
    });

    doc.save('Senarai Pengguna.pdf');
});

   </script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>




   <!-- import javascript and css bootsrap bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
      crossorigin="anonymous"></script>
   <!-- import popper to use dropdowns, popovers, or tooltips-->
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
      integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE"
      crossorigin="anonymous"></script>



<!-- End of Tables -->

<script>const passwordInput = document.getElementById("passLogin");
      const togglePassword = document.getElementById("togglePassword");

      togglePassword.addEventListener("click", function () {
         if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");
         } else {
            passwordInput.type = "password";
            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");
         }
      });</script>

   <script>const passwordInputx = document.getElementById("passLoginx");
      const togglePasswordx = document.getElementById("togglePasswordx");

      togglePasswordx.addEventListener("click", function () {
         if (passwordInputx.type === "password") {
            passwordInputx.type = "text";
            togglePasswordx.classList.remove("fa-eye");
            togglePasswordx.classList.add("fa-eye-slash");
         } else {
            passwordInputx.type = "password";
            togglePasswordx.classList.remove("fa-eye-slash");
            togglePasswordx.classList.add("fa-eye");
         }
      });</script>

   <script src="../js/script.js"></script>

</body>

</html>