<?php
include 'conn.php'; // Ensure this file sets up the $conn mysqli connection
include 'admin_dashboard.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Database Backup</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Update with your CSS path -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <h1>Settings - Database Backup</h1>
        <form method="post" action="">
            <button type="submit" name="backup" class="btn btn-primary">Backup Database</button>
        </form>
    </div>
</body>
</html>