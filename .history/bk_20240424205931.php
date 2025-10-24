<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;

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
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>



    <style>

    </style>
</head>

<body>
    <?php include 'components/user-header.php'; ?>
    <div class="section">
        <br>
        <div class="container">
            <div class="section add-any">
                <form id="bookingForm">
                    <h1 class="text-center">Tempah untuk tarikh:</h1>\
                    <div class="inputBox">
                        <label for="datepicker">Tarikh sesi rawatan:</label>
                        <input style="font-family: 'Gilroymedium', sans-serif;" style="font-size:1.3rem;" type="text"
                            name="date" id="datepicker" class="box" placeholder="Date">
                    </div>
                    <div class="inputBox">
                        <label>Pakar Terapi:</label>
                        <select class="box" required placeholder="" name="staff_id" id="staff_id">
                            <option value="" <?php echo isset($_GET['staff']) ? '' : 'selected'; ?>>Sila Pilih Pakar
                                Terapi Anda</option>
                            <?php
                            $result = $conn->prepare("SELECT * FROM `staffs` ORDER BY name ASC");
                            $result->execute();
                            if ($result->rowCount() > 0) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <option value="<?php echo $row['id']; ?>" <?php echo (isset($_GET['staff']) && $_GET['staff'] == $row['id']) ? 'selected' : ''; ?>>
                                        <?php echo $row['name']; ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="inputBox">
                        <label>Pakej Rawatan Spa:</label>
                        <select class="box" required placeholder="Pilih Pakej Rawatan Anda" name="service_id"
                            id="service_id">
                            <option value="" selected hidden>Pilih Pakej Rawatan Anda</option>
                            <?php
                            $result = $conn->prepare("SELECT * FROM `services` ORDER BY id ASC");
                            $result->execute();
                            if ($result->rowCount() > 0) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <option value="<?php echo $row['id']; ?>"
                                        data-duration="<?php echo $row['duration'] + 30; ?>">
                                        <?php echo $row['type'] . ' - ' . $row['name']; ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="inputBox">
                        <label for="name">Tempoh masa sesi rawatan:</label>
                        <input style="font-family: 'Gilroymedium', sans-serif;" readonly style="font-size:1.3rem;"
                            type="text" name="duration" id="duration" class="box" placeholder="Duration">
                    </div>

                    <div class="inputBox">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
            <br>
            <br>
        </div>
        <br>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#bookingForm').submit(function (e) {
                e.preventDefault(); // Prevent default form submission

                // Get form data
                var formData = $(this).serialize();

                // Send form data to the server using AJAX
                $.ajax({
                    type: 'POST',
                    url: window.location.href, // Change this to the URL of your PHP script
                    data: formData,
                    success: function (response) {
                        // Handle the response from the server if needed
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
                        // Handle errors
                        console.error(error);
                    }
                });
            });
        });
    </script>
    <script>
        // Add event listener to service select element
        document.getElementById('service_id').addEventListener('change', function () {
            // Retrieve selected service duration
            var selectedOption = this.options[this.selectedIndex];
            var duration = selectedOption.getAttribute('data-duration');

            // Update duration input value
            document.getElementById('duration').value = duration;
        });
    </script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr
        flatpickr('#datepicker', {
            dateFormat: 'Y-m-d', // Date format (Year-Month-Day)
            minDate: 'today', // Minimum selectable date (today's date)
            defaultDate: new Date(), // Default date selected (today's date)
            altInput: true, // Enable alternate input field (to display formatted date)
            altFormat: 'F j, Y', // Date format for alternate input field (e.g., January 1, 2023)
            // enableTime: false, // Enable time selection
            // time_24hr: false, // Use 24-hour time format
            // hourIncrement: 1, // Increment value for hours
            // minuteIncrement: 5, // Increment value for minutes
            // allowInput: true, // Allow manual input of date
            disableMobile: true, // Disable Flatpickr on mobile devices
            inline: false, // Display Flatpickr inline instead of in a dropdown
            mode: 'single', // Date selection mode ('single', 'multiple', or 'range')
            weekNumbers: true, // Display week numbers
            appendTo: document.body, // Specify parent element for Flatpickr calendar
            plugins: [], // Array of additional plugins to enable
            locale: 'ms', // Locale for language and date formatting
            wrap: false, // Wrap input field with Flatpickr container
            clickOpens: true, // Clicking on input field opens the calendar
            onChange: function (selectedDates, dateStr, instance) {
                // Callback function when date is changed
                console.log('Selected Date:', dateStr);
            },
            onClose: function (selectedDates, dateStr, instance) {
                // Callback function when calendar is closed
                console.log('Flatpickr Closed');
            },
            onOpen: function (selectedDates, dateStr, instance) {
                // Callback function when calendar is opened
                console.log('Flatpickr Opened');
            },
            onReady: function (selectedDates, dateStr, instance) {
                // Callback function when Flatpickr is initialized and ready
                console.log('Flatpickr Ready');
            },
            onMonthChange: function (selectedDates, dateStr, instance) {
                // Callback function when month changes
                console.log('Month Changed');
            },
            onYearChange: function (selectedDates, dateStr, instance) {
                // Callback function when year changes
                console.log('Year Changed');
            },
            onOpen: function (selectedDates, dateStr, instance) {
                // Callback function when calendar is opened
                console.log('Flatpickr Opened');
            },
            onError: function (err) {
                // Callback function for error handling
                console.error(err);
            },
            onValueUpdate: function (selectedDates, dateStr, instance) {
                // Callback function when value is updated
                console.log('Value Updated');
            }
        });
    </script>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>



</body>

</html>