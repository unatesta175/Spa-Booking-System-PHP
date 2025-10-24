<?php
include '../components/connect.php';
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
    $serviceId = $_POST['service'];
    $staffId = $_POST['staff'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];

    $select_accounts = $conn->prepare("SELECT * FROM `bookings` WHERE service_id = ? AND staff_id = ? AND date = ?");
    $select_accounts->execute([$serviceId, $staffId, $date]);

    if ($select_accounts->rowCount() > 0) {
        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                   <td>" . $row['booking_id'] . "</td>
                   <td>" . $row['date'] . "</td>
                   <td>" . $row['service_id'] . "</td>
                   <td>" . $row['staff_id'] . "</td>
                   <td>" . $row['timeslot'] . "</td>
                   <td>" . $row['duration'] . "</td>
                   <td>" . $duration . "</td>
               </tr>";
        }
    }
    ?>
</table>
