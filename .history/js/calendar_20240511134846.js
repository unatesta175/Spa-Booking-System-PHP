
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        slotDuration: '00:15:00',   // Each slot is 15 minutes
        slotLabelInterval: '01:00:00', // Show time labels every 15 minutes
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        businessHours: {            // Define business hours
            startTime: '10:00',    // Start time
            endTime: '19:00',      // End time (11 p.m.)
            daysOfWeek: [0, 1, 3, 4, 5, 6] // Business hours for Monday to Friday
        },
        editable: true,
    dayMaxEvents: true, // allow "more" link when too many events
    navLinks: true,
        dateClick: function(info) {
alert('clicked ' + info.dateStr);
},
select: function(info) {
alert('selected ' + info.startStr + ' to ' + info.endStr);
},
        events: [
                            <?php
                            $bookings_query = $conn->prepare("SELECT * FROM `bookings`");
                            $bookings_query->execute();
                            while ($booking = $bookings_query->fetch(PDO::FETCH_ASSOC)) {
                               $start_datetime = $booking['date'] . 'T' . $booking['starttime'];
                               $start_timestamp = strtotime($start_datetime);
                               $end_timestamp = $start_timestamp + ($booking['duration'] * 60);
                               $end_datetime = date('Y-m-d\TH:i:s', $end_timestamp);
                               $service_id = $booking['service_id'];
                               $booking_id = $booking['booking_id'];
                           $bookings_query1 = $conn->prepare("SELECT * FROM `services` WHERE id=?");
                            $bookings_query1->execute([$service_id]);
                            while ($booking1 = $bookings_query1->fetch(PDO::FETCH_ASSOC)) {

                               $sv_name = $booking1['name'];}                                   

                               // Extract AM/PM designation
                               $start_time = date('h:i A', strtotime($booking['starttime']));
                               $end_time = date('h:i A', $end_timestamp);

                               echo "{";
                                  echo "title: '" . $sv_name . "',";
                                  echo "start: '" . $start_datetime . "',";
                                  echo "end: '" . $end_datetime . "',";
                                  echo "description: 'Time: " . $start_time . " - " . $end_time . "',"; // Add AM or PM to the event description
                                  echo "id: '" . $booking_id . "'"; // Add booking ID
                                  echo "},";
                              }


                            ?>
                            ],
                            slotMinTime: '10:00', // Minimum time to display (10 a.m.)
slotMaxTime: '23:00', // Maximum time to display (11 p.m.)
scrollTime: '08:00', // Scroll to 8 a.m. initially
slotLabelInterval: { hours: 1 }, // Show time labels every hour
slotLabelFormat: {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
},
eventTimeFormat: { // Format the event time to display with AM/PM designation
    hour: '2-digit',
    minute: '2-digit',
    meridiem: true // Enable displaying AM/PM
}

});

    calendar.render();
});
