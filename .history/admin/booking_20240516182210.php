<?php
include '../components/connect.php';
include '../components/functions.php';

session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin-login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php includeHeaderAdmin(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />
    <style>
        .tooltip { font-size: 14px; max-width: 250px; }
        .tooltip .arrow:before { border-top-color: #007bff !important; }
        .tooltip .tooltip-inner { background-color: #007bff; color: #fff; border-radius: 4px; padding: 8px 12px; }
        .btns.selected { background-color: #4CAF50; color: white; border: 3px solid #4CAF50; }
        .baby { width: 100% !important; }
        .alert { font-family: 'Gilroymedium'; font-size: 1.7rem !important; }
        .btn-success { color: #fff !important; background-color: #15c271 !important; border-color: #198754 !important; }
        .btns { display: inline-block; font-weight: 600; color: #fff; text-align: center; padding: 10px 20px; border-radius: 5px; margin: 0 0px; transition: background-color 0.3s ease; font-family: 'Gilroymedium'; font-size: 1.3rem !important; }
        .btns.baby.btn-success:hover { background-color: #28a745 !important; }
        .form-group { flex: 0 0 calc(25% - 20px); margin: 0px; white-space: nowrap; }
        .timeslots-container { display: flex; flex-wrap: wrap; justify-content: center; border: 1.75px solid #eee; margin-bottom: 10px; max-height: 400px; overflow-y: auto; overflow-x: hidden; padding: 0px; border-radius: 10px; }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap.min.js"></script>
    <script>
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
                    $("#service").val("---Select---");
                    $("#duration").val("");
                } else {
                    serviceDropdown.prop("disabled", true);
                }
            }

            toggleStaffDropdown();
            toggleserviceDropdown();
            $("#timeslot_div").load("getInitialTimeslot.php");

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

                $("#test").load("getDuration.php", { servicev1: servicev });

                if (servicev !== "---Select---" && datev !== "dd/mm/yyyy" && staffv !== "---Select---") {
                    $("#timeslot_div").load("getTimeslot.php", {
                        service: servicev,
                        staff: staffv,
                        date: datev,
                        duration: $("#duration").val()
                    });
                }
            });
        });
    </script>
</head>
<body>
    <?php include '../components/admin_header.php'; ?>
    <div class="section">
        <br>
        <div class="container">
            <br>
            <div class="row" style="background-color:white; border-radius: 25px; padding:15px; box-shadow: var(--box-shadow);">
                <h1 class="text-center" style="font-size: 4rem; color:var(--black); margin-bottom: 2rem; text-align: center; text-transform: capitalize; font-family: 'Gilroymedium', sans-serif !important; font-weight: 800 !important;">Tempah Sesi Rawatan</h1>
                <div class="col-md-4 col-sm-6">
                    <div class="section add-any">
                        <form id="booking_form" style="box-shadow: none;" action="javascript:void(0)" method="post">
                            <div class="inputBox">
                                <label>Pengguna :</label>
                                <select name="client" required class="box" id="client">
                                    <option>---Select---</option>
                                    <?php
                                    $select_accounts = $conn->prepare("SELECT * FROM `clients`");
                                    $select_accounts->execute();
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="inputBox">
                                <label for="date">Tarikh sesi rawatan :</label>
                                <input style="font-family: 'Gilroymedium'; font-size:1.3rem;" type="text" name="date" id="date" class="box" placeholder="Date">
                            </div>
                            <div class="inputBox">
                                <label>Pakar Terapi :</label>
                                <select class="box" required name="staff" id="staff">
                                    <option>---Select---</option>
                                    <?php
                                    $select_accounts = $conn->prepare("SELECT * FROM `staffs`");
                                    $select_accounts->execute();
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="inputBox">
                                <label>Pakej Rawatan Spa :</label>
                                <select name="service" class="box" id="service">
                                    <option>---Select---</option>
                                    <?php
                                    $select_accounts = $conn->prepare("SELECT * FROM `services`");
                                    $select_accounts->execute();
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="inputBox">
                                <label for="name">Tempoh masa sesi rawatan :</label>
                                <input readonly class="box" placeholder="Duration" type="text" name="duration" id="duration">
                            </div>
                            <div class="inputBox">
                                <label for="name">Slot Masa :</label>
                                <input readonly style="font-family: 'Gilroymedium'; font-size:1.3rem;" type="text" name="timeslot" id="timeslotz" class="box" placeholder="Timeslot">
                            </div>
                            <br>
                            <br>
                            <button type="submit" id="submit_booking" name="submit_booking" class="btns baby btn-success" style="width: 100%;" onclick="submitForm();">Hantar</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-8 col-sm-6" id="timeslot_div">
                    <!-- Timeslot buttons will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitForm() {
            var client = $("#client").val();
            var date = $("#date").val();
            var staff = $("#staff").val();
            var service = $("#service").val();
            var duration = $("#duration").val();
            var timeslot = $("#timeslotz").val();

            if (client === "---Select---" || date === "" || staff === "---Select---" || service === "---Select---" || duration === "" || timeslot === "") {
                alert("Please fill in all the required fields.");
                return false;
            }

            $.ajax({
                url: 'backend.php',
                type: 'POST',
                data: {
                    client: client,
                    date: date,
                    staff: staff,
                    service: service,
                    duration: duration,
                    timeslot: timeslot
                },
                success: function(response) {
                    alert(response);
                    $("#client").val("---Select---");
                    $("#date").val("");
                    $("#staff").val("---Select---");
                    $("#service").val("---Select---");
                    $("#duration").val("");
                    $("#timeslotz").val("");
                }
            });
        }
    </script>
</body>
</html>
