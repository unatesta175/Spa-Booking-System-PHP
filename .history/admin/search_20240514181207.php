<?php
include '../components/connect.php';

// Debugging: Log POST data
error_log(print_r($_POST, true));

$date = $_POST['date'];
$serviceId = $_POST['service'];
$staffId = $_POST['staff'];
$duration = $_POST['duration'];
?>

<table class="table table-striped table-responsive">
    <tr>
        <th>No Tempahan </th>
        <th>Tarikh Tempahan </th>
        <th>Pakej Terapi</th>
        <th>Nama Pakar Terapi</th>
        <th>Slot Masa Tempahan</th>
        <th>Tempoh Masa</th>
        <th>Test</th>
    </tr>
    <?php
    $select_accounts = $conn->prepare("SELECT * FROM `bookings` WHERE service_id = ? AND staff_id = ? AND date = ?");
    $select_accounts->execute([$serviceId, $staffId, $date]);

    if ($select_accounts->rowCount() > 0) {
        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                   <td>" . htmlspecialchars($row['booking_id']) . "</td>
                   <td>" . htmlspecialchars($row['date']) . "</td>
                   <td>" . htmlspecialchars($row['service_id']) . "</td>
                   <td>" . htmlspecialchars($row['staff_id']) . "</td>
                   <td>" . htmlspecialchars($row['timeslot']) . "</td>
                   <td>" . htmlspecialchars($row['duration']) . "</td>
                   <td>Date: " . htmlspecialchars($duration) . "</td>
               </tr>";
        }
    }
    ?>
</table>
