<?php

// Load environment variables
require_once __DIR__ . '/env_loader.php';

// Database configuration from environment
$db_host = env('DB_HOST', 'localhost');
$db_name_only = env('DB_NAME', 'spa_db');
$user_name = env('DB_USER', 'root');
$user_password = env('DB_PASS', '');

// Create PDO connection string
$db_name = "mysql:host={$db_host};dbname={$db_name_only}";

$conn = new PDO($db_name, $user_name, $user_password);

if(isset($_SESSION['logout_success'])) {
    // Prepare JavaScript for showing the Sweet Alert
    echo "<script>
            window.addEventListener('load', function() {
                Swal.fire({
                    title: 'Berjaya!',
                    text: 'Anda sudah berjaya Log keluar',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        // Define a class for the SweetAlert popup
                        popup: 'my-custom-popup',
                        // Define a class for the SweetAlert title
                        title: 'my-custom-title',
                        // Define a class for the SweetAlert text
                        text: 'my-custom-text',
                        // Define a class for the SweetAlert confirm button
                        confirmButton: 'my-custom-confirm-button',
                        // Define a class for the SweetAlert cancel button
                        cancelButton: 'my-custom-cancel-button'
                    }
                 
                });
            });
          </script>";
    unset($_SESSION['logout_success']); // Unset the flag to prevent the alert from showing again on refresh
 }
?>

