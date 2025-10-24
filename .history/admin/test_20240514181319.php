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

    function toggleStaffDropdown() {
        var dateValue = $("#date").val();
        var staffDropdown = $("#staff");
        var serviceDropdown = $("#service");

        if (dateValue) {
            staffDropdown.prop("disabled", false);
        } else {
            staffDropdown.prop("disabled", true);
            serviceDropdown.prop("disabled", true);
        }
    }

    function toggleserviceDropdown() {
        var staffDropdown = $("#staff").val();
        var serviceDropdown = $("#service");

        if (staffDropdown && staffDropdown !== "---Select---") {
            serviceDropdown.prop("disabled", false);
        } else {
            serviceDropdown.prop("disabled", true);
        }
    }

    toggleStaffDropdown();
    toggleserviceDropdown();

    $("#refresh").click(function() {
        $("#employee_div").load("allrecord.php");
    });

    $("#date").change(function() {
        $("#staff").val("---Select---");
        $("#service").val("---Select---");
        $("#duration").val("");

        toggleStaffDropdown();
    });

    $("#staff").change(function() {
        toggleserviceDropdown();
    });

    $("#service").change(function() {
        var servicev = $(this).val();
        var datev = $("#date").val();
        var staffv = $("#staff").val();

        $("#test").load("getDuration.php", { servicev1: servicev }, function() {
            var durationv = $("#duration").val();

            if (servicev !== "---Select---" && datev !== "" && staffv !== "---Select---") {
                $("#employee_div").load("search.php", {
                    service: servicev,
                    staff: staffv,
                    date: datev,
                    duration: durationv
                });
            } else {
                $("#employee_div").load("allrecord.php");
            }
        });
    });

});
    </script>
</head>

<body>
    <?php include '../components/admin_header.php'; ?>
    <div class="section">
        <br>
        <div class="container">
            <br><br>
            <center>
                <h1><strong>Search/Filter data in php using Ajax</strong></h1>
            </center>
            <br>
            <div class="row">
                <form method="post" class="form-horizontal">
                    <div class="col-sm-2">
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
                    <div class="col-sm-2">
                        <input type="date" name="date" class="form-control" id="date">
                    </div>
                    <div class="col-sm-2">
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

                    <div class="col-sm-2">
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
                    <div class="col-sm-2">

                        <label class="control-label col-sm-3 col-sm-offset-2">Duration: </label>
                        <input readonly class="form-control" type="text" name="duration" id="duration">
                    </div>
                    <div class="row" id='test'></div>
            </div>

            <button type="button" name="refresh" id="refresh" class="btn btn-primary">Refresh</button>
            </form>

        </div>
        <div class="row"><br></div>
        <div class="row" id='employee_div'>


        </div>
    </div>
    <br>
    </div>


    <!-- import javascript and css bootsrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- import popper to use dropdowns, popovers, or tooltips-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="../js/admin_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>



    </script>
</body>

</html>