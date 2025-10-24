<?php
   function includeHeader() {
      echo '<title>kapasbeautyspa.com</title>';
      echo '<link rel="icon" type="image/png" href="images/logo.png">';
   }
   
   function includeHeaderAdmin() {
      echo '<title>kapasbeautyspa.com</title>';
      echo '<link rel="icon" type="image/png" href="../images/logo.png">';
   }

   function includeTitle() {
    echo '<title>kapasbeautyspa.com</title>';
    echo '<link rel="icon" type="image/png" href="../images/logo.png">';
 }

//    function formatDuration($minutes) {
//       // Calculate hours and remaining minutes
//       $hours = intval($minutes / 60);
//       $remainingMinutes = $minutes % 60;
      
//       // Initialize an empty string to hold the result
//       $result = "";
      
//       // Append hours to the result string if any
//       if ($hours > 0) {
//           $result .= $hours . " Jam";
//       }
      
//       // Append minutes to the result string if any
//       if ($remainingMinutes > 0) {
//           // Add a space and concatenate minutes if hours were also present
//           if ($hours > 0) {
//               $result .= " " . $remainingMinutes . " Min";
//           } else {
//               // Only minutes are present
//               $result = $remainingMinutes . " Min";
//           }
//       }
      
//       return $result;
//   }
   ?>