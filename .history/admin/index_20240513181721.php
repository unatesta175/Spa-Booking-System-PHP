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
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Submit form without reloading page</title>

            <!-- CSS Files Start Here-->
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
                    
            <!-- CSS Files End Here-->

                <link  href="https://fonts.googleapis.com/css2?family=ABeeZee&family=Acme&family=Balsamiq+Sans&family=Bowlby+One+SC&family=Fredoka+One&family=Josefin+Sans:wght@700&family=Lobster&display=swap" rel="stylesheet">
          

            <!-- Script Files Start Here--> 
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
            <!-- Script Files Ends Here-->    
            <style>
            
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
<body  >

<div align="center" class="container py-5 my-5 col-5">

    <form id="registration_form" method="post"  action="javascript:void(0)">
    <div >
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
                    <label class="control-label col-sm-3 col-sm-offset-2" >Date: </label>
                    <div >
                        <input type="date" name="date" class="form-control" id="date">
                    </div>
                    <div >
                    <label class="control-label col-sm-3 col-sm-offset-2" >Therapist: </label>
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

                    <div >
                    <label class="control-label col-sm-3 col-sm-offset-2" >Service: </label>
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

                    <label class="control-label col-sm-3 col-sm-offset-2" >Duration: </label>
                        <input readonly  class="form-control" type="text" name="duration" id="duration">
                    </div>
                    <div class="row" id='test'></div>
            </div>
                    <input type="text"  name="name"  placeholder="Name" > <br><br>
                    <input type="email"  name="email"  placeholder="E-mail" required="required"><br><br>
     
                    <button type="submit" id="Submit"  class="btn btn-purple" >Submit</button>
    
    <div id="messages"></div>
    </form> 

</div>
	
	 
	 
	<script >//SENDING DATA BY AJAX
$(document).ready(function () {
  $("#Submit").click(function (e) {
    e.preventDefault();

    var email = $("input[name=email]").val();
    var name = $("input[name=name]").val();

    if (email == "") {
      $("#messages").html('<p style="color:red;">Email id Required! </p>');

      setTimeout(function () {
        $("#messages").html("");
      }, 1000);
    } else {
      /* Submit form data using ajax*/

      $.ajax({
        url: "backend.php",
        method: "post",
        data: $("#registration_form").serialize(),

        beforeSend: function () {
          $("#messages").html(
            '<br><span class="spinner-border fast"  style="width: 2rem; height: 2rem;color:green;"  role="status"></span>'
          );
        },

        success: function (Response) {
          $("#messages").html(Response);
          $("#registration_form")[0].reset();

          setTimeout(function () {
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