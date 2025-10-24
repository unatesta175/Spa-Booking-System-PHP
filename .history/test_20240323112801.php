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

    // Function to increment ID


}
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
    <title>Bootstrap Modal Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@10" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <!-- Button to Open the Modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            Open Modal
        </button>

        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Submit Form</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Form to submit data -->
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="date">Tarikh sesi rawatan:<span style="color: red;"></span></label>
                                <input type="date" name="date" required placeholder="Masukkan tarikh sesi rawatan"
                                class="form-control" id="date">
                            </div>

                            <div class="form-group">
                                <label for="time">Masa sesi rawatan:<span style="color: red;"></span></label>
                                <input type="time" name="time" required placeholder="Masukkan masa sesi rawatan"
                                class="form-control" id="time">
                            </div>
                            <div class="form-group">
                                <label for="name">Pesanan:<span style="color: red;"></span></label>
                                <input type="text" name="remarks" required placeholder="Masukkan pesanan anda"
                                    maxlength="200" class="form-control" id="remarks">
                            </div>



                            <div class="form-group ">
                                <label for="staff_id">Pakar Terapi :<span style="color: red;"></span></label>
                                <select class="form-control" required placeholder="" name="staff_id" id="staff_id">
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

                            <div class="form-group ">
                                <label for="service_id">Pakej Rawatan Spa :<span style="color: red;"></span></label>
                                <select class="form-control" required placeholder="Pilih Pakej Rawatan Anda" name="service_id" id="service_id">
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

                            <div class="form-group ">
                                <label for="pay_type">Jenis Pembayaran :<span style="color: red;"></span></label>
                                <select class="form-control" required placeholder="" name="pay_type"  id="pay_type">
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
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#bookingForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        // Show SweetAlert2 success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'You have successfully booked.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Redirect to another page if needed
                            window.location.href = 'another_page.php';
                        });
                    },
                    error: function(xhr, status, error) {
                        // Show SweetAlert2 error message if submission fails
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing your request.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>