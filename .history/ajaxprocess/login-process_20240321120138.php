<?php
session_start();

include '../components/connect.php'; // Adjust the path as needed

if (isset($_POST['submit'])) {

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = ($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `clients` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['login_success'] = true; // Set login success flag
      header('location:../index.php'); // Adjust the path as needed
      exit;
   } else {
      $message[] = 'Emel atau kata laluan tidak sepadan!';
   }
}
?>