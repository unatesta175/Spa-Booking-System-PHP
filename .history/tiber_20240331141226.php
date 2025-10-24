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

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">



  <style>
  
  </style>
</head>

<body>

  <?php include 'components/user-header.php'; ?>

  <div class="section">
    <div class="container m-5">
      <div class="wrapper ">
        <header>
          <p class="current-date"></p>
          <div class="icons">
            <!-- Button to go to previous year -->
            <span id="prevYear" class="material-symbols-rounded">arrow_left</span>
            <!-- Button to go to previous month -->
            <span id="prev" class="material-symbols-rounded">chevron_left</span>
            <!-- Button to go to current day of the month -->
            <button id="currentDayBtn"  class="btn btn-outline-primary">Today</button>
            <!-- Button to go to next month -->
            <span id="next" class="material-symbols-rounded">chevron_right</span>
            <!-- Button to go to next year -->
            <span id="nextYear" class="material-symbols-rounded">arrow_right</span>
          </div>
        </header>
        <div class="calendar">
          <ul class="weeks">
            <li>Sun</li>
            <li>Mon</li>
            <li>Tue</li>
            <li>Wed</li>
            <li>Thu</li>
            <li>Fri</li>
            <li>Sat</li>
          </ul>
          <ul class="days"></ul>
        </div>
      </div>
      <div class="timeslot"></div>
    </div>
  </div>










  <?php include 'components/footer.php'; ?>

  <script src="js/script.js"></script>
  <script>
    // Retrieve necessary elements from the DOM
    const daysTag = document.querySelector(".days"),
      currentDate = document.querySelector(".current-date"),
      prevNextIcon = document.querySelectorAll(".icons span"),
      currentDayBtn = document.getElementById("currentDayBtn"),
      prevYearBtn = document.getElementById("prevYear"),
      nextYearBtn = document.getElementById("nextYear");

    // getting new date, current year and month
    let date = new Date(),
      currYear = date.getFullYear(),
      currMonth = date.getMonth();

    // storing full name of all months in array
    const months = ["January", "February", "March", "April", "May", "June", "July",
      "August", "September", "October", "November", "December"];

    const renderCalendar = () => {
      let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
      let liTag = "";

      for (let i = firstDayofMonth; i > 0; i--) {
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
      }

      for (let i = 1; i <= lastDateofMonth; i++) {
        let isToday = i === date.getDate() && currMonth === new Date().getMonth()
          && currYear === new Date().getFullYear() ? "active" : "";
        liTag += `<li class="${isToday}">${i}</li>`;
      }

      for (let i = lastDayofMonth; i < 6; i++) {
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
      }
      currentDate.innerText = `${months[currMonth]} ${currYear}`;
      daysTag.innerHTML = liTag;
    };

    renderCalendar();

    // Add event listeners to each date element
    daysTag.addEventListener("click", (event) => {
      if (event.target.tagName === "LI" && !event.target.classList.contains("inactive")) {
        const selectedDate = event.target.innerText;
        const eventData = prompt("Enter event data for " + selectedDate + " " + months[currMonth] + " " + currYear + ":");
        if (eventData !== null) {
          // Store the event data for the selected date (you can use localStorage, a database, etc.)
          // For demonstration, I'm just logging the data to the console
          console.log("Event data for " + selectedDate + " " + months[currMonth] + " " + currYear + ": " + eventData);
          // You can further enhance this by updating the UI to display the event data
        }
      }
    });

    // Add event listeners to previous and next icons to navigate through months
    prevNextIcon.forEach(icon => {
      icon.addEventListener("click", () => {
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
        if (currMonth < 0 || currMonth > 11) {
          date = new Date(currYear, currMonth, new Date().getDate());
          currYear = date.getFullYear();
          currMonth = date.getMonth();
        } else {
          date = new Date();
        }
        renderCalendar();
      });
    });

    // Add event listener for the current day button
    currentDayBtn.addEventListener("click", () => {
      date = new Date(); // Get the current date
      currYear = date.getFullYear(); // Update current year
      currMonth = date.getMonth(); // Update current month
      renderCalendar(); // Render the calendar with current date
    });

    // Add event listener for the previous year button
    prevYearBtn.addEventListener("click", () => {
      currYear--; // Decrement current year
      renderCalendar(); // Render the calendar with updated year
    });

    // Add event listener for the next year button
    nextYearBtn.addEventListener("click", () => {
      currYear++; // Increment current year
      renderCalendar(); // Render the calendar with updated year
    });
  </script>



</body>

</html>