

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const dateStr = urlParams.get('date');

    var calendarEl2 = document.getElementById('calendar2');

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
       businessHours: {
          startTime: '10:00',
          endTime: '19:00',
          daysOfWeek: [0, 1, 3, 4, 5, 6]
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

             echo "{";
             echo "title: '" . 'Booked Slot' . "',";
             echo "start: '" . $start_datetime . "',";
             echo "end: '" . $end_datetime . "'";
             echo "},";
          }

       , function}},
    );
          ?>
       ],
       eventClick: function (info) {
          alert('Event clicked: ' + info.event.title);
       },
       eventMouseEnter: function (info) {
          info.el.style.backgroundColor = 'lightgray';
       },
       eventMouseLeave: function (info) {
          info.el.style.backgroundColor = '';
       },
       dateClick: function (info) {



          document.addEventListener('DOMContentLoaded', function () {
             const urlParams = new URLSearchParams(window.location.search);
             const dateStr = urlParams.get('date');

             var calendarEl2 = document.getElementById('calendar2');

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
                businessHours: {
                   startTime: '10:00',
                   endTime: '19:00',
                   daysOfWeek: [0, 1, 3, 4, 5, 6]
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

                      // Check if start time is AM or PM
                      $start_time = date('h:i A', strtotime($booking['time']));

                      echo "{";
                      echo "title: '" . 'Booked Slot' . "',";
                      echo "start: '" . $start_datetime . "',";
                      echo "end: '" . $end_datetime . "',";
                      echo "color: 'red',"; // Set the event color to red
                      echo "description: 'Time: " . $start_time . "'"; // Add AM or PM to the event description
                      echo "},";
                   }
                   ?>
                ],
                eventClick: function (info) {
                   alert('Event clicked: ' + info.event.title);
                },
                eventMouseEnter: function (info) {
                   info.el.style.backgroundColor = 'lightgray';
                },
                eventMouseLeave: function (info) {
                   info.el.style.backgroundColor = '';
                },
                dateClick: function (info) {
                   // Check if the clicked cell belongs to Tuesday
                   if (info.date.getDay() === 2) {
                      // If it's Tuesday, prevent the default action
                      info.jsEvent.preventDefault();
                      alert('Time slots on Tuesdays cannot be clicked.');
                   } else {
                      // For other days, proceed with the default action
                      alert('Event clicked: ' + info.event.title);
                   }
                }
             });




document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const dateStr = urlParams.get('date');

    var calendarEl2 = document.getElementById('calendar2');

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
       businessHours: {
          startTime: '10:00',
          endTime: '19:00',
          daysOfWeek: [0, 1, 3, 4, 5, 6]
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

             echo "{";
             echo "title: '" . 'Booked Slot' . "',";
             echo "start: '" . $start_datetime . "',";
             echo "end: '" . $end_datetime . "'";
             echo "},";
          }


          ?>
       ],
       eventClick: function (info) {
          alert('Event clicked: ' + info.event.title);
       },
       eventMouseEnter: function (info) {
          info.el.style.backgroundColor = 'lightgray';
       },
       eventMouseLeave: function (info) {
          info.el.style.backgroundColor = '';
       },
       dateClick: function (info) {

          document.addEventListener('DOMContentLoaded', function () {
             const urlParams = new URLSearchParams(window.location.search);
             const dateStr = urlParams.get('date');

             var calendarEl2 = document.getElementById('calendar2');

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
                businessHours: {
                   startTime: '10:00',
                   endTime: '19:00',
                   daysOfWeek: [0, 1, 3, 4, 5, 6]
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

                      echo "{";
                      echo "title: '" . 'Booked Slot' . "',";
                      echo "start: '" . $start_datetime . "',";
                      echo "end: '" . $end_datetime . "'";
                      echo "},";
                   }


                   ?>
                ],
                eventClick: function (info) {
                   alert('Event clicked: ' + info.event.title);
                },
                eventMouseEnter: function (info) {
                   info.el.style.backgroundColor = 'lightgray';
                },
                eventMouseLeave: function (info) {
                   info.el.style.backgroundColor = '';
                },
                dateClick: function (info) {
                   // Check if the clicked cell belongs to Tuesday
                   if (info.date.getDay() === 2) {
                      // If it's Tuesday, prevent the default action
                      info.jsEvent.preventDefault();
                      alert('Time slots on Tuesdays cannot be clicked.');
                   } else {
                      // For other days, proceed with the default action
                      alert('Event clicked: ' + info.event.title);
                   }
                }
             });

             calendarEl2.addEventListener('mouseover', function (e) {
                if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
                   e.target.style.backgroundColor = 'lightblue';
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

             calendar2.render();

             var calendarEl1 = document.getElementById('calendar1');

             var calendar1 = new FullCalendar.Calendar(calendarEl1, {
                initialView: 'dayGridMonth',
                slotDuration: '00:15:00',
                slotLabelInterval: '00:15:00',
                headerToolbar: {
                   left: 'prev,next today',
                   center: 'title',
                   right: ''
                },
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

             calendar1.render();

             function goToTimeGridDay(dateStr) {
                calendar2.gotoDate(dateStr);
                calendar2.changeView('timeGridDay');
             }

             function bookSlot(dateStr) {
                var button = document.querySelector('button[data-date="' + dateStr + '"]');
                if (button) {
                   button.classList.add('btn-selected');
                }
             }
          });

          
          document.addEventListener('DOMContentLoaded', function () {
             const urlParams = new URLSearchParams(window.location.search);
             const dateStr = urlParams.get('date');

             var calendarEl2 = document.getElementById('calendar2');

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
                businessHours: {
                   startTime: '10:00',
                   endTime: '19:00',
                   daysOfWeek: [0, 1, 3, 4, 5, 6]
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

                      // Check if start time is AM or PM
                      $start_time = date('h:i A', strtotime($booking['time']));

                      echo "{";
                      echo "title: '" . 'Booked Slot' . "',";
                      echo "start: '" . $start_datetime . "',";
                      echo "end: '" . $end_datetime . "',";
                      echo "color: 'red',"; // Set the event color to red
                      echo "description: 'Time: " . $start_time . "'"; // Add AM or PM to the event description
                      echo "},";
                   }
                   ?>
                ],
                eventClick: function (info) {
                   alert('Event clicked: ' + info.event.title);
                },
                eventMouseEnter: function (info) {
                   info.el.style.backgroundColor = 'lightgray';
                },
                eventMouseLeave: function (info) {
                   info.el.style.backgroundColor = '';
                },
                dateClick: function (info) {
                   // Check if the clicked cell belongs to Tuesday
                   if (info.date.getDay() === 2) {
                      // If it's Tuesday, prevent the default action
                      info.jsEvent.preventDefault();
                      alert('Time slots on Tuesdays cannot be clicked.');
                   } else {
                      // For other days, proceed with the default action
                      alert('Event clicked: ' + info.event.title);
                   }
                }
             });

             calendarEl2.addEventListener('mouseover', function (e) {
                if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
                   e.target.style.backgroundColor = 'lightblue';
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

             calendar2.render();

             var calendarEl1 = document.getElementById('calendar1');

             var calendar1 = new FullCalendar.Calendar(calendarEl1, {
                initialView: 'dayGridMonth',
                slotDuration: '00:15:00',
                slotLabelInterval: '00:15:00',
                headerToolbar: {
                   left: 'prev,next today',
                   center: 'title',
                   right: ''
                },
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

             calendar1.render();

             function goToTimeGridDay(dateStr) {
                calendar2.gotoDate(dateStr);
                calendar2.changeView('timeGridDay');
             }

             function bookSlot(dateStr) {
                var button = document.querySelector('button[data-date="' + dateStr + '"]');
                if (button) {
                   button.classList.add('btn-selected');
                }
             }
          });
          alert('You clicked on an empty time slot at: ' + info.dateStr);
       }
    });

    calendarEl2.addEventListener('mouseover', function (e) {
       if (e.target.classList.contains('fc-timegrid-slot') && !e.target.classList.contains('fc-timegrid-slot-event')) {
          e.target.style.backgroundColor = 'lightblue';
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

    calendar2.render();

    var calendarEl1 = document.getElementById('calendar1');

    var calendar1 = new FullCalendar.Calendar(calendarEl1, {
       initialView: 'dayGridMonth',
       slotDuration: '00:15:00',
       slotLabelInterval: '00:15:00',
       headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: ''
       },
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

    calendar1.render();

    function goToTimeGridDay(dateStr) {
       var clickedDate = new Date(dateStr);
       if (clickedDate.getDay() === 2) { // Check if clicked date is Tuesday
          alert('No Bookings are available in Tuesdays.');
       } else {
          var selectedDateText = 'Anda sudah pilih hari ' + clickedDate.toLocaleDateString('ms-MY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
          document.getElementById('selected-date').textContent = selectedDateText;
          calendar2.gotoDate(dateStr);
          calendar2.changeView('timeGridDay');

       }
    }
    function bookSlot(dateStr) {
       var button = document.querySelector('button[data-date="' + dateStr + '"]');
       if (button) {
          button.classList.add('btn-selected');
       }
    }
 });
