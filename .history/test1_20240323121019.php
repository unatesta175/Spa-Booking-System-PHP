<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap Stepper Example</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body>

<div class="container mt-5 p-3" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.1); border-radius:15px; ">
  <div class="progress mb-4">
    <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Step 1</div>
  </div>

  <form id="step1">
    <h2>Step 1</h2>
    <div class="container">
         <!-- Calendar 1 -->
         <div id="calendar1" class="calendar">
            <!-- Calendar 1 content goes here -->
         </div>

         <!-- Calendar 2 -->
         <div id="calendar2" class="calendar">
            <!-- Calendar 2 content goes here -->
         </div>
      </div>
    <button type="button" class="btn btn-primary next">Next</button>
  </form>

  <form id="step2" style="display: none;">
    <h2>Step 2</h2>
    <div class="form-group">
      <label for="input2">Input 2</label>
      <input type="text" class="form-control" id="input2" placeholder="Enter Input 2">
    </div>
    <button type="button" class="btn btn-secondary prev">Previous</button>
    <button type="button" class="btn btn-primary next">Next</button>
  </form>

  <form id="step3" style="display: none;">
    <h2>Step 3</h2>
    <div class="form-group">
      <label for="input3">Input 3</label>
      <input type="text" class="form-control" id="input3" placeholder="Enter Input 3">
    </div>
    <button type="button" class="btn btn-secondary prev">Previous</button>
    <button type="button" class="btn btn-primary next">Next</button>
  </form>

  <form id="step4" style="display: none;">
    <h2>Step 4</h2>
    <div class="form-group">
      <label for="input4">Input 4</label>
      <input type="text" class="form-control" id="input4" placeholder="Enter Input 4">
    </div>
    <button type="button" class="btn btn-secondary prev">Previous</button>
    <button type="submit" class="btn btn-success">Submit</button>
  </form>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function() {
    var currentStep = 1;

    $('.next').click(function() {
      $('#step' + currentStep).hide();
      currentStep++;
      $('#step' + currentStep).show();
      updateProgressBar();
    });

    $('.prev').click(function() {
      $('#step' + currentStep).hide();
      currentStep--;
      $('#step' + currentStep).show();
      updateProgressBar();
    });

    function updateProgressBar() {
      var progress = (currentStep - 1) * 33.33;
      $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
    }
  });
</script>

<script>
      // Create event listener for both calendar
      document.addEventListener('DOMContentLoaded', function () {
         // Retrieve date parameter from URL
         const urlParams = new URLSearchParams(window.location.search);
         const dateStr = urlParams.get('date');



         // Initialize Calendar1
         var calendarEl1 = document.getElementById('calendar1');
         var calendarEl2 = document.getElementById('calendar2');


         // Add year and month dropdowns to the header toolbar of calendar1
         var headerToolbar1 = {
            left: 'prevYear,prev,next,nextYear today',
            center: 'title',
            right: '',
         };

         // Add year and month dropdowns only for calendar1
       

         // Declare Calendar1
         var calendar1 = new FullCalendar.Calendar(calendarEl1, {
            initialView: 'dayGridMonth',
            slotDuration: '00:15:00',
            slotLabelInterval: '00:15:00',
            headerToolbar: headerToolbar1,
            businessHours: {
               startTime: '10:00',
               endTime: '23:00',
               daysOfWeek: [0, 1, 3, 4, 5, 6]
            },
            slotMinTime: '10:00',
            slotMaxTime: '23:00',
            allDaySlot: false,

            dayCellContent: function (info) {
               var date = info.date.getDate();
               if (info.view.type === 'timeGridWeek' || info.view.type === 'timeGridDay') {
                  return '';
               }
               if (info.date.getDay() === 2) {
                  return { html: '<div class="fc-daygrid-day-content"><div class="date">' + date + '</div><button disabled class="btnd btn-secondary" >Off Day</button></div>' };
               }

               var buttonHtml = '';
               var isAvailable = true;

               if (isAvailable) {
                  buttonHtml = '<button class="btnd btn-success" onclick="goToTimeGridDay(\'' + info.dateStr + '\')">Available Slot</button>';
               } else {
                  buttonHtml = '<button class="btnd btn-danger" onclick="goToTimeGridDay(\'' + info.dateStr + '\')">Booked</button>';
               }
               return { html: '<div class="fc-daygrid-day-content"><div class="date">' + date + '</div>' + buttonHtml + '</div>' };
            },
            dateClick: function (info) {
               goToTimeGridDay(info.dateStr);
            },
            slotLaneContent: function (slotLaneInfo) {
               if (calendar1.view.type === 'timeGridDay' && slotLaneInfo.date.getDay() === 2) {
                  return '';
               } else {
                  var buttonHtml = '<button class="btnt btn-success" onclick="bookSlot(\'' + slotLaneInfo.dateStr + '\')">Book Available Slot</button>';
                  return { html: '<div style="text-align:center;">' + buttonHtml + '</div>' };
               }
            }
         });

         // Declare calendar2
         var calendar2 = new FullCalendar.Calendar(calendarEl2, {
            initialView: 'timeGridDay',
            slotDuration: '00:15:00',
            slotLabelInterval: '00:15:00',
            defaultDate: dateStr,
            headerToolbar: {
               left: '',
               center: 'title',
               right: ''
            },
            slotMinTime: '10:00',
            slotMaxTime: '22:00',
            allDaySlot: false,
            eventColor: 'red',
            businessHours: {
               startTime: '10:00',
               endTime: '19:00',
               daysOfWeek: [0, 1, 3, 4, 5, 6]
            },
            slotLaneContent: function (slotLaneInfo) {
               // Check if the slot is within business hours
               if (slotLaneInfo.date.getDay() !== 2 && // Not Tuesday
                  slotLaneInfo.date.getHours() >= 10 && // After opening time
                  slotLaneInfo.date.getHours() < 19) {   // Before closing time
                  // Display "Available Slot" for time slot cells within business hours
                  return {
                     html: '<div class="available-slot" style=" color: #155724; background-color:#d4edda;border-color: #c3e6cb; text-align: center; ">Available Slot</div>'
                  };
               } else {
                  // Return an empty div for time slot cells outside business hours
                  return { html: '' };
               }
            },

            events: [
               <?php
               $bookings_query = $conn->prepare("SELECT * FROM `bookings`");
               $bookings_query->execute();
               while ($booking = $bookings_query->fetch(PDO::FETCH_ASSOC)) {
                  $start_datetime = $booking['date'] . 'T' . $booking['time'];
                  $start_timestamp = strtotime($start_datetime);
                  $end_timestamp = $start_timestamp + ($booking['duration'] * 60);
                  $end_datetime = date('Y-m-d\TH:i:s', $end_timestamp);

                  // Extract AM/PM designation
                  $start_time = date('h:i A', strtotime($booking['time']));
                  $end_time = date('h:i A', $end_timestamp);

                  echo "{";
                  echo "title: '" . 'Booked Slot' . "',";
                  echo "start: '" . $start_datetime . "',";
                  echo "end: '" . $end_datetime . "',";
                  echo "description: 'Time: " . $start_time . " - " . $end_time . "'"; // Add AM or PM to the event description
                  echo "},";
               }


               ?>
            ],
            eventClick: function (info) {
               alert('Event clicked: ' + info.event.title);
            },
            // eventMouseEnter: function (info) {
            //    info.el.style.backgroundColor = 'lightgray';
            // },
            // eventMouseLeave: function (info) {
            //    info.el.style.backgroundColor = '';
            // },
            dateClick: function (info) {

               alert('You clicked on an empty time slot at: ' + info.dateStr);
            }
         });

         // End of Calendar2 declaration



         // configuration for Calendar 1
         calendarEl1.addEventListener('mouseover', function (e) {
            if (e.target.classList.contains('fc-daygrid-day')) {
               e.target.style.backgroundColor = '#c0c0c0';
            }
         });

         calendarEl1.addEventListener('mouseout', function (e) {
            if (e.target.classList.contains('fc-daygrid-day')) {
               e.target.style.backgroundColor = '';
            }
         });



         // Add CSS to change cursor to pointer on hover for calendar2 time slot cells
         var calendar2TimeSlotCells = document.querySelectorAll('.fc-timegrid-slot');
         calendar2TimeSlotCells.forEach(function (cell) {
            cell.style.cursor = 'pointer';
         });
         // Configuration options for calendar2
         calendarEl2.addEventListener('mouseover', function (e) {
            if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
               e.target.style.backgroundColor = 'Green';
            }
         });

         calendarEl2.addEventListener('mouseout', function (e) {
            if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
               e.target.style.backgroundColor = '';
            }
         });

         calendarEl2.addEventListener('click', function (e) {
            if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
               var selectedCells = document.querySelectorAll('.selected-cell');
               selectedCells.forEach(function (cell) {
                  cell.classList.remove('selected-cell');
               });
               e.target.classList.add('selected-cell');
            }
         });


         document.addEventListener('mouseover', function (event) {
            // Check if the mouseover event target or its ancestors have the class ".available-slot"
            if (event.target.closest('.available-slot')) {
               event.target.style.backgroundColor = '#8FBC8F'; // Change background color on hover
            }
         });

         document.addEventListener('mouseout', function (event) {
            // Check if the mouseout event target or its ancestors have the class ".available-slot"
            if (event.target.closest('.available-slot')) {
               event.target.style.backgroundColor = '#d4edda'; // Reset background color on mouseout
            }
         });

         document.addEventListener('click', function (event) {
            // Check if the click event target or its ancestors have the class ".available-slot"
            if (event.target.closest('.available-slot')) {
               event.target.style.border = '2px solid #228B22'; // Add border on click

               // Add mouseout event listener to remove border when mouse moves away
               event.target.addEventListener('mouseout', function () {
                  event.target.style.border = ''; // Reset border when mouse moves away
               });
            }
         });

         // Render Calendar1
         calendar1.render();
         calendar2.render();

         // Some Additional Functions
         // CLick button on Calendar1 and then it will display the date timegridday view on calendar2
         // function goToTimeGridDay(dateStr) {
         //    var clickedDate = new Date(dateStr);
         //    if (clickedDate.getDay() === 2) { // Check if clicked date is Tuesday
         //       alert('No Bookings are available in Tuesdays.');
         //    } else {
         //       var selectedDateText = 'Anda sudah pilih hari ' + clickedDate.toLocaleDateString('ms-MY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
         //       document.getElementById('selected-date').textContent = selectedDateText;
         //       calendar2.gotoDate(dateStr);
         //       calendar2.changeView('timeGridDay');

         //    }
         // }
         // CLick button on Calendar1 and then it will display the date timegridday view on calendar2
         function goToTimeGridDay(dateStr) {
            var clickedDate = new Date(dateStr);
            if (clickedDate.getDay() === 2) { // Check if clicked date is Tuesday
               alert('No Bookings are available on Tuesdays.');
            } else {
               var selectedDateText = 'You have selected ' + clickedDate.toLocaleDateString('en-US', { weekday: 'long' }) + ', ';


               // Set the selected date text directly to the h2 element owned by Calendar2
               var h2Element = calendarEl2.querySelector('.fc-toolbar-title');
               if (h2Element) {
                  h2Element.textContent = selectedDateText;
               }

               calendar2.gotoDate(dateStr);
               calendar2.changeView('timeGridDay');
            }
         }

         //  Display button as selected in Calendar1
         function bookSlot(dateStr) {
            var button = document.querySelector('button[data-date="' + dateStr + '"]');
            if (button) {
               button.classList.add('btn-selected');
            }
         }
      });

   </script>
</body>
</html>
