
It seems you're trying to fetch the duration of a service from the database based on the selected service ID using PHP, and then outputting it to the HTML. However, there are a couple of issues in your code:

You're trying to use jQuery (JavaScript) functions inside PHP code, which won't work. PHP is server-side, while jQuery runs on the client-side.

Your jQuery code is commented out (<!-- -->), so it won't execute.

Here's a corrected version of your code:

php
Copy code
<?php
include '../components/connect.php';

// Check if service ID is provided in the POST request
if(isset($_POST['servicev1'])) {
    // Prepare and execute query to fetch service duration based on service ID
    $select_servicev = $conn->prepare("SELECT * FROM `service` WHERE id = ?");
    $select_servicev->execute([$_POST['servicev1']]);

    // If rows are found, output duration input field
    if ($select_servicev->rowCount() > 0) {
        $row = $select_servicev->fetch(PDO::FETCH_ASSOC);
        echo "<input id='durationfetch' value='" . $row['duration'] . "' />";
    }
}
?>