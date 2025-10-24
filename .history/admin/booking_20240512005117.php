<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin-login.php');
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

    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />

    <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

    <style>

    </style>

</head>

<body>

<div class="section">
      <br>
      <div class="container">



         <br>

         <div class="row"
            style="background-color:white; border-radius: 25px; padding:15px; box-shadow: var(--box-shadow);">
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
               </div>

            </div>
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


            <div class="container mt-3">
               <a href="service-package.php" class="btns btn-secondary"><i class="fas fa-arrow-left"></i>Kembali ke
                  Pakej</a>
            </div>
         </div>






         <br>
      </div>
      <br>
   </div>
   
    <?php include '../components/admin_header.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.1/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <!-- import javascript and css bootsrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- import popper to use dropdowns, popovers, or tooltips-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="../js/admin_script.js"></script>

</body>

</html>