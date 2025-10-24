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

                var formData = $(this).serialize(); // Serialize all the form data

                $.ajax({
                    type: 'POST', // Use POST method
                    url: window.location.href, // Send the request to the current page URL
                    data: formData, // Data to send in the request
                    success: function (response) {
                        // This function is called if the request succeeds
                        alert('Form submitted successfully.');
                        console.log(response); // Log the server response for debugging
                    },
                    error: function (xhr, status, error) {
                        // This function is called if the request fails
                        alert('Error submitting form.');
                        console.error('Error: ' + error); // Log the error for debugging
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
            disableMobile: false, // Disable Flatpickr on mobile devices
            inline: false, // Display Flatpickr inline instead of in a dropdown
            mode: 'single', // Date selection mode ('single', 'multiple', or 'range')
            // weekNumbers: true, // Display week numbers
            appendTo: document.body, // Specify parent element for Flatpickr calendar
            plugins: [], // Array of additional plugins to enable
            locale: 'ms', // Locale for language and date formatting
            // wrap: false, // Wrap input field with Flatpickr container
            clickOpens: true, // Clicking on input field opens the calendar
            // disable: [
            //     function (date) {
            //         // Return true to disable Tuesdays
            //         return (date.getDay() === 2); // 0: Sunday, 1: Monday, ..., 6: Saturday
            //     }
            // ],
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

<script>(function() {

(function($) {
  "use strict";
  var Calendar, opts, spy;
  Calendar = function(element, options) {
    var time;
    this.$element = element;
    this.options = options;
    this.weekDays = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    this.time = new Date();
    this.currentYear = this.time.getFullYear();
    this.currentMonth = this.time.getMonth();
    if (this.options.time) {d
      time = this.splitDateString(this.options.time);
      this.currentYear = time.year;
      this.currentMonth = time.month;
    }
    this.initialDraw();
    return null;
  };
  Calendar.prototype = {
    addLeadingZero: function(num) {
      if (num < 10) {
        return "0" + num;
      } else {
        return "" + num;
      }
    },
    applyTransition: function($el, transition) {
      $el.css('transition', transition);
      $el.css('-ms-transition', '-ms-' + transition);
      $el.css('-moz-transition', '-moz-' + transition);
      return $el.css('-webkit-transition', '-webkit-' + transition);
    },
    applyBackfaceVisibility: function($el) {
      $el.css('backface-visibility', 'hidden');
      $el.css('-ms-backface-visibility', 'hidden');
      $el.css('-moz-backface-visibility', 'hidden');
      return $el.css('-webkit-backface-visibility', 'hidden');
    },
    applyTransform: function($el, transform) {
      $el.css('transform', transform);
      $el.css('-ms-transform', transform);
      $el.css('-moz-transform', transform);
      return $el.css('-webkit-transform', transform);
    },
    splitDateString: function(dateString) {
      var day, month, time, year;
      time = dateString.split('-');
      year = parseInt(time[0]);
      month = parseInt(time[1] - 1);
      day = parseInt(time[2]);
      return time = {
        year: year,
        month: month,
        day: day
      };
    },
    initialDraw: function() {
      return this.drawDays(this.currentYear, this.currentMonth);
    },
    editDays: function(events) {
      var dateString, day, dayEvents, time, _results;
      _results = [];
      for (dateString in events) {
        dayEvents = events[dateString];
        this.options.events[dateString] = events[dateString];
        time = this.splitDateString(dateString);
        day = this.$element.find('[data-year="' + time.year + '"][data-month="' + (time.month + 1) + '"][data-day="' + time.day + '"]').parent('.day');
        day.removeClass('active');
        day.find('.badge').remove();
        day.find('a').removeAttr('href');
        if (this.currentMonth === time.month || this.options.activateNonCurrentMonths) {
          _results.push(this.makeActive(day, dayEvents));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    },
    clearDays: function(days) {
      var dateString, day, time, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = days.length; _i < _len; _i++) {
        dateString = days[_i];
        delete this.options.events[dateString];
        time = this.splitDateString(dateString);
        day = this.$element.find('[data-year="' + time.year + '"][data-month="' + (time.month + 1) + '"][data-day="' + time.day + '"]').parent('.day');
        day.removeClass('active');
        day.find('.badge').remove();
        _results.push(day.find('a').removeAttr('href'));
      }
      return _results;
    },
    clearAll: function() {
      var day, days, i, _i, _len, _results;
      this.options.events = {};
      days = this.$element.find('[data-group="days"] .day');
      _results = [];
      for (i = _i = 0, _len = days.length; _i < _len; i = ++_i) {
        day = days[i];
        $(day).removeClass('active');
        $(day).find('.badge').remove();
        _results.push($(day).find('a').removeAttr('href'));
      }
      return _results;
    },
    setMonthYear: function(dateString) {
      var time;
      time = this.splitDateString(dateString);
      this.currentMonth = this.drawDays(time.year, time.month);
      return this.currentYear = time.year;
    },
    prev: function() {
      if (this.currentMonth - 1 < 0) {
        this.currentYear = this.currentYear - 1;
        this.currentMonth = 11;
      } else {
        this.currentMonth = this.currentMonth - 1;
      }
      this.drawDays(this.currentYear, this.currentMonth);
      if (this.options.onMonthChange) {
        this.options.onMonthChange.call(this);
      }
      return null;
    },
    next: function() {
      if (this.currentMonth + 1 > 11) {
        this.currentYear = this.currentYear + 1;
        this.currentMonth = 0;
      } else {
        this.currentMonth = this.currentMonth + 1;
      }
      this.drawDays(this.currentYear, this.currentMonth);
      if (this.options.onMonthChange) {
        this.options.onMonthChange.call(this);
      }
      return null;
    },
    curr: function() {
      this.currentYear = this.time.getFullYear();
      this.currentMonth = this.time.getMonth();
      this.drawDays(this.currentYear, this.currentMonth);
      if (this.options.onMonthChange) {
        this.options.onMonthChange.call(this);
      }
      return null;
    },
    addOthers: function(day, dayEvents) {
      var badge;
      if (typeof dayEvents === "object") {
        if (dayEvents.number != null) {
          badge = $("<span></span>").html(dayEvents.number).addClass("badge");
          if (dayEvents.badgeClass != null) {
            badge.addClass(dayEvents.badgeClass);
          }
          day.append(badge);
        }
        if (dayEvents.url) {
          day.find("a").attr("href", dayEvents.url);
        }
      }
      return day;
    },
    makeActive: function(day, dayEvents) {
      var classes, eventClass, i, _i, _len;
      if (dayEvents) {
        if (dayEvents["class"]) {
          classes = dayEvents["class"].split(" ");
          for (i = _i = 0, _len = classes.length; _i < _len; i = ++_i) {
            eventClass = classes[i];
            day.addClass(eventClass);
          }
        } else {
          day.addClass("active");
        }
        day = this.addOthers(day, dayEvents);
      }
      return day;
    },
    getDaysInMonth: function(year, month) {
      return new Date(year, month + 1, 0).getDate();
    },
    drawDay: function(lastDayOfMonth, yearNum, monthNum, dayNum, i) {
      var calcDate, dateNow, dateString, day, dayDate, pastFutureClass;
      var dayname = this.weekDays[i % 7];
      day = $('<div></div>').addClass("day");
      dateNow = new Date();
      dateNow.setHours(0, 0, 0, 0);
      dayDate = new Date(yearNum, monthNum - 1, dayNum);
      if (dayDate.getTime() < dateNow.getTime()) {
        pastFutureClass = "past";
      } else if (dayDate.getTime() === dateNow.getTime()) {
        pastFutureClass = "today active";
      } else {
        pastFutureClass = "future";
      }
      
      day.addClass(dayname);
      day.addClass(pastFutureClass);
      dateString = yearNum + "-" + this.addLeadingZero(monthNum) + "-" + this.addLeadingZero(dayNum);
      if (dayNum <= 0 || dayNum > lastDayOfMonth) {
        calcDate = new Date(yearNum, monthNum - 1, dayNum);
        dayNum = calcDate.getDate();
        monthNum = calcDate.getMonth() + 1;
        yearNum = calcDate.getFullYear();
        day.addClass("not-current").addClass(pastFutureClass);
        if (this.options.activateNonCurrentMonths) {
          dateString = yearNum + "-" + this.addLeadingZero(monthNum) + "-" + this.addLeadingZero(dayNum);
        }
        day.append($("<a>" + dayNum + "</a>").attr("data-day", this.addLeadingZero(dayNum)).attr("data-month", monthNum).attr("data-year", yearNum));
      }
      else
      {
          day.append($("<a>" + dayNum + "</a>").attr("data-day", this.addLeadingZero(dayNum)).attr("data-month", monthNum).attr("data-year", yearNum).attr("id", "day_"+dayNum).attr("class", dayname));
      }
      if (this.options.monthChangeAnimation) {
        this.applyTransform(day, 'rotateY(180deg)');
        this.applyBackfaceVisibility(day);
      }
      day = this.makeActive(day, this.options.events[dateString]);
      return this.$element.find('[data-group="days"]').append(day);
    },
    drawSlot: function(a, b) {
      //return this.$element.find('weekend').
      jQuery("#mytimeslot").remove();
      if (jQuery(a.parentElement).hasClass("past"))
      {
          return "";
      }
      if (jQuery(a.parentElement).hasClass("today"))
      {
          //return ""; //include today
      }
      var clickedon = parseInt(a.id.replace("day_",""));
      var totalsundays =  jQuery('a.sun').length;
      for (var i=0; i<totalsundays; i++)
      {
          var currentsunday = jQuery('a.sun')[i].innerHTML;
          if (currentsunday >= clickedon)
          {
              return jQuery("#day_"+currentsunday).parent().after('<div id="mytimeslot" class="panel-body"><center><ul class="list-group"><li class="list-group-item"><button type="button" class="btn btn-success" id="time1">9:00am - 12:00pm</button></li><li class="list-group-item"><button type="button" class="btn btn-success" id="time2">12:00pm - 3:00pm</button></li><li class="list-group-item"><button type="button" class="btn btn-success" id="time3">3:00pm - 6:00pm</button></li></ul></center></div>');
          }
      }
      return jQuery("#slotleft").parent().after('<div id="mytimeslot" class="panel-body"><center><ul class="list-group"><li class="list-group-item"><button type="button" class="btn btn-success" id="time1">9:00am - 12:00pm</button></li><li class="list-group-item"><button type="button" class="btn btn-success" id="time2">12:00pm - 3:00pm</button></li><li class="list-group-item"><button type="button" class="btn btn-success" id="time3">3:00pm - 6:00pm</button></li></ul></center></div>');
    },
    drawDays: function(year, month) {
      var currentMonth, day, dayBase, days, delay, draw, firstDayOfMonth, i, lastDayOfMonth, loopBase, monthNum, multiplier, thisRef, time, timeout, yearNum, _i, _len;
      thisRef = this;
      time = new Date(year, month);
      currentMonth = time.getMonth();
      monthNum = time.getMonth() + 1;
      yearNum = time.getFullYear();
      time.setDate(1);
      firstDayOfMonth = this.options.startFromSunday ? time.getDay() + 1 : time.getDay() || 7;
      lastDayOfMonth = this.getDaysInMonth(year, month);
      timeout = 0;
      if (this.options.monthChangeAnimation) {
        days = this.$element.find('[data-group="days"] .day');
        for (i = _i = 0, _len = days.length; _i < _len; i = ++_i) {
          day = days[i];
          delay = i * 0.01;
          this.applyTransition($(day), 'transform .5s ease ' + delay + 's');
          this.applyTransform($(day), 'rotateY(180deg)');
          this.applyBackfaceVisibility($(day));
          timeout = (delay + 0.1) * 1000;
        }
      }
      dayBase = 2;
      if (this.options.allRows) {
        loopBase = 42;
      } else {
        multiplier = Math.ceil((firstDayOfMonth - (dayBase - 1) + lastDayOfMonth) / 7);
        loopBase = multiplier * 7;
      }
      this.$element.find("[data-head-year]").html(time.getFullYear());
      this.$element.find("[data-head-month]").html(this.options.translateMonths[time.getMonth()]);
      draw = function() {
        var dayNum, setEvents;
        thisRef.$element.find('[data-group="days"]').empty();
        dayNum = dayBase - firstDayOfMonth;
        i = thisRef.options.startFromSunday ? 0 : 1;
        while (dayNum < loopBase - firstDayOfMonth + dayBase) {
          thisRef.drawDay(lastDayOfMonth, yearNum, monthNum, dayNum, i);
          dayNum = dayNum + 1;
          i = i + 1;
        }
        setEvents = function() {
          var _j, _len1;
          days = thisRef.$element.find('[data-group="days"] .day');
          for (i = _j = 0, _len1 = days.length; _j < _len1; i = ++_j) {
            day = days[i];
            thisRef.applyTransition($(day), 'transform .5s ease ' + (i * 0.01) + 's');
            thisRef.applyTransform($(day), 'rotateY(0deg)');
          }
          if (thisRef.options.onDayClick) {
            thisRef.$element.find('[data-group="days"] .day a').click(function() {
              return thisRef.options.onDayClick.call(this, thisRef.options.events);
            });
          }
          if (thisRef.options.onSlotClick) {
            thisRef.$element.find('[data-group="days"] .day a').click(function() {
                thisRef.$element.find('[data-group="slots"]').empty();
                thisRef.drawSlot(this, thisRef.options.events);
              return thisRef.options.onSlotClick.call(this, thisRef.options.events);
            });
          }
          if (thisRef.options.onDayHover) {
            thisRef.$element.find('[data-group="days"] .day a').hover(function() {
              return thisRef.options.onDayHover.call(this, thisRef.options.events);
            });
          }
          if (thisRef.options.onActiveDayClick) {
            thisRef.$element.find('[data-group="days"] .day.active a').click(function() {
              return thisRef.options.onActiveDayClick.call(this, thisRef.options.events);
            });
          }
          if (thisRef.options.onActiveDayHover) {
            return thisRef.$element.find('[data-group="days"] .day.active a').hover(function() {
              return thisRef.options.onActiveDayHover.call(this, thisRef.options.events);
            });
          }
        };
        return setTimeout(setEvents, 0);
      };
      setTimeout(draw, timeout);
      return currentMonth;
    }
  };
  $.fn.responsiveCalendar = function(option, params) {
    var init, options, publicFunc;
    options = $.extend({}, $.fn.responsiveCalendar.defaults, typeof option === 'object' && option);
    publicFunc = {
      next: 'next',
      prev: 'prev',
      edit: 'editDays',
      clear: 'clearDays',
      clearAll: 'clearAll',
      getYearMonth: 'getYearMonth',
      jump: 'jump',
      curr: 'curr'
    };
    init = function($this) {
      var data;
      options = $.metadata ? $.extend({}, options, $this.metadata()) : options;
      $this.data('calendar', (data = new Calendar($this, options)));
      if (options.onInit) {
        options.onInit.call(data);
      }
      return $this.find("[data-go]").click(function() {
        if ($(this).data("go") === "prev") {
          data.prev();
        }
        if ($(this).data("go") === "next") {
          return data.next();
        }
      });
    };
    return this.each(function() {
      var $this, data;
      $this = $(this);
      data = $this.data('calendar');
      if (!data) {
        init($this);
      } else if (typeof option === 'string') {
        if (publicFunc[option] != null) {
          data[publicFunc[option]](params);
        } else {
          data.setMonthYear(option);
        }
      } else if (typeof option === 'number') {
        data.jump(Math.abs(option) + 1);
      }
      return null;
    });
  };
  $.fn.responsiveCalendar.defaults = {
    translateMonths: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    events: {},
    time: void 0,
    allRows: true,
    startFromSunday: false,
    activateNonCurrentMonths: false,
    monthChangeAnimation: true,
    onInit: void 0,
    onDayClick: void 0,
    onSlotClick:void 0,
    onDayHover: void 0,
    onActiveDayClick: void 0,
    onActiveDayHover: void 0,
    onMonthChange: void 0
  };
  spy = $('[data-spy="responsive-calendar"]');
  if (spy.length) {
    opts = {};
    if ((spy.data('translate-months')) != null) {
      opts.translateMonths = spy.data('translate-months').split(',');
    }
    if ((spy.data('time')) != null) {
      opts.time = spy.data('time');
    }
    if ((spy.data('all-rows')) != null) {
      opts.allRows = spy.data('all-rows');
    }
    if ((spy.data('start-from-sunday')) != null) {
      opts.startFromSunday = spy.data('start-from-sunday');
    }
    if ((spy.data('activate-non-current-months')) != null) {
      opts.activateNonCurrentMonths = spy.data('activate-non-current-months');
    }
    if ((spy.data('month-change-animation')) != null) {
      opts.monthChangeAnimation = spy.data('month-change-animation');
    }
    return spy.responsiveCalendar(opts);
  }
})(jQuery);

}).call(this);</script>

</body>

</html>