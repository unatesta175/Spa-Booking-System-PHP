<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

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

   <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
   


<style>

#calendar {
  max-width: 1100px;
  margin: 40px auto;
  font-size: 14px;
}
</style>
</head>
<body>
   
<?php include 'components/user-header.php'; ?>










<section><div
  class="container"
>
  bs5-grid-row
</div>
<div id='calendar'></div></section>











<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    initialDate: '2024-02-07',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: [
      {
        title: 'All Day Event',
        start: '2024-02-01'
      },
      {
        title: 'Long Event',
        start: '2024-02-07',
        end: '2024-02-10'
      },
      {
        groupId: '999',
        title: 'Repeating Event',
        start: '2024-02-09T16:00:00'
      },
      {
        groupId: '999',
        title: 'Repeating Event',
        start: '2024-02-16T16:00:00'
      },
      {
        title: 'Conference',
        start: '2024-02-11',
        end: '2024-02-13'
      },
      {
        title: 'Meeting',
        start: '2024-02-12T10:30:00',
        end: '2024-02-12T12:30:00'
      },
      {
        title: 'Lunch',
        start: '2024-02-12T12:00:00'
      },
      {
        title: 'Meeting',
        start: '2024-02-12T14:30:00'
      },
      {
        title: 'Birthday Party',
        start: '2024-02-13T07:00:00'
      },
      {
        title: 'Click for Google',
        url: 'https://google.com/',
        start: '2024-02-28'
      }
    ]
  });

  calendar.render();
});

</script>


</body>
</html>