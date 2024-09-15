<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'password_manager_db';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die('Could not connect to MySQL server: ' . mysqli_connect_error());
}

