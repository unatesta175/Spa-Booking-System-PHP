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
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />
  <!-- WAJIB FOR AJAX CALL -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>




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
  <script type="text/javascript">
    $(document).ready(function() {

      // Function to enable/disable staff dropdown based on date input value
      function toggleStaffDropdown() {
        var dateValue = $("#date").val(); // Get date input value
        var staffDropdown = $("#staff"); // Get staff dropdown element
        var serviceDropdown = $("#service");

        // Check if date value is set
        if (dateValue) {
          // Date is set, enable staff dropdown
          staffDropdown.prop("disabled", false);
        } else {
          // Date is not set, disable staff dropdown and reset its value
          staffDropdown.prop("disabled", true);
          serviceDropdown.prop("disabled", true);
          // Reset staff dropdown value
        }


      }

      function toggleserviceDropdown() {
        var staffDropdown = $("#staff").val(); // Get date input value
        var serviceDropdown = $("#service"); // Get staff dropdown element
        var durationDropdown = $("#duration");
        // Check if date value is set
        if (staffDropdown && staffDropdown !== "---Select---") {
          // Date is set, enable staff dropdown
          serviceDropdown.prop("disabled", false);

          $("#service").val("---Select---");
          $("#duration").val("");
        } else {
          // Date is not set, disable staff dropdown and reset its value
          serviceDropdown.prop("disabled", true);
          // Reset staff dropdown value
        }
      }

      // Call the function when the page loads
      toggleStaffDropdown();
      toggleserviceDropdown();

      // $("#employee_div").load("allrecord.php");

      $("#refresh").click(function() {
        $("#employee_div").load("allrecord.php");
      });

      // Listen for changes in the date input
      $("#date").change(function() {
        // Reset the value of the staff dropdown
        $("#staff").val("---Select---"); // Reset staff dropdown value
        $("#service").val("---Select---");
        $("#duration").val("");


        // Call the function when the date input value changes
        toggleStaffDropdown();
      });

      $("#staff").change(function() {
        var selected = $(this).val();
        // var date = $("#date").val();
        // $("#desig_div").load("desig.php", {
        //     poststaff: selected,
        //     postdate: date
        // });

        toggleserviceDropdown();
      });

      $("#service").change(function() {
        var servicev = $(this).val();
        var datev = $("#date").val(); // Get date input value
        var staffv = $("#staff").val();



        $("#test").load("getDuration.php", {
          servicev1: servicev
        });
        // Get date input value


        // Check if the selected value is not "---Select---"
        if (servicev !== "---Select---" && datev !== "dd/mm/yyyy" && staffv !== "---Select---") {
          $("#employee_div").load("search.php", {
            service: servicev,
            staff: staffv,
            date: datev
          });
        } else {
          // Load allrecord.php if any of the conditions are not met
          $("#employee_div").load("allrecord.php");
        }
      });



    });
  </script>
</head>

<body>
  <?php include '../components/admin_header.php'; ?>
  <div align="center" class="container py-5 my-5 col-5">

    <form id="registration_form" method="post" action="javascript:void(0)">
      <div>
        <label class="control-label col-sm-3 col-sm-offset-2">Client: </label>
        <select name="client" class="form-control" id="client">
          <option>---Select---</option>
          <?php


          $select_accounts = $conn->prepare("SELECT * FROM `clients`");
          $select_accounts->execute();
          if ($select_accounts->rowCount() > 0) {
            while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
            }
          }

          ?>
        </select>
      </div>
      <label class="control-label col-sm-3 col-sm-offset-2">Date: </label>
      <div>
        <input type="date" name="date" class="form-control" id="date">
      </div>
      <div>
        <label class="control-label col-sm-3 col-sm-offset-2">Therapist: </label>
        <select name="staff" class="form-control" id="staff">
          <option>---Select---</option>
          <?php


          $select_accounts = $conn->prepare("SELECT * FROM `staffs`");
          $select_accounts->execute();
          if ($select_accounts->rowCount() > 0) {
            while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
            }
          }

          ?>
        </select>

      </div>

      <div>
        <label class="control-label col-sm-3 col-sm-offset-2">Service: </label>
        <select name="service" class="form-control" id="service">
          <option>---Select---</option>
          <?php
          $select_accounts = $conn->prepare("SELECT * FROM `services`");
          $select_accounts->execute();
          if ($select_accounts->rowCount() > 0) {
            while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

              echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
            }
          }


          ?>
        </select>
      </div>
      <div>

        <label class="control-label col-sm-3 col-sm-offset-2">Duration: </label>
        <input readonly class="form-control" type="text" name="duration" id="duration">
      </div>
      <div class="row" id='test'></div>
  </div>
  <input type="text" name="name" placeholder="Name"> <br><br>
  <input type="email" name="email" placeholder="E-mail" required="required"><br><br>

  <button type="submit" id="Submit" class="btn btn-purple">Submit</button>

  <div id="messages"></div>
  </form>

  </div>



  <script>
    //SENDING DATA BY AJAX
    $(document).ready(function() {
      $("#Submit").click(function(e) {
        e.preventDefault();

        var client = $("input[name=client]").val();
        var date = $("input[name=date]").val();
        var staff = $("input[name=staff]").val();
        var service = $("input[name=service]").val();
        var duration = $("input[name=duration]").val();

        if (client == "---selected---") {
          $("#messages").html('<p style="color:red;">Email id Required! </p>');

          setTimeout(function() {
            $("#messages").html("");
          }, 1000);
        } else {
          /* Submit form data using ajax*/

          $.ajax({
            url: "backend.php",
            method: "post",
            data: $("#registration_form").serialize(),

            beforeSend: function() {
              $("#messages").html(
                '<br><span class="spinner-border fast"  style="width: 2rem; height: 2rem;color:green;"  role="status"></span>'
              );
            },

            success: function(Response) {
              $("#messages").html(Response);
              $("#registration_form")[0].reset();

              setTimeout(function() {
                $("#messages").html("");
              }, 20000);
            },
          });
        }
      });
    });
  </script>
</body>

</html>