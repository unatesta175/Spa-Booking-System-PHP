<?php
// Load environment variables
require_once __DIR__ . '/../components/env_loader.php';

// Database configuration from environment
$server = env('DB_HOST', 'localhost');
$username = env('DB_USER', 'root');
$password = env('DB_PASS', '');
$database = env('DB_NAME', 'spa_db');

$connection = mysqli_connect($server, $username, $password);
$select_db = mysqli_select_db($connection, $database);
if(!$select_db)
{
	echo("connection terminated");
}
?>