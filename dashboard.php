<?php
include 'conn.php'; // Ensure this file sets up the $conn mysqli connection
include 'admin_dashboard.php'; // Assuming this file is properly included and used

// Query the database to get the number of admin and user records
$adminCountQuery = "SELECT COUNT(*) as count FROM tbl_admin";
$userCountQuery = "SELECT COUNT(*) as count FROM tbl_user";

// Execute the queries
$adminCountResult = $conn->query($adminCountQuery);
$userCountResult = $conn->query($userCountQuery);

// Fetch the results
$adminCount = $adminCountResult ? $adminCountResult->fetch_assoc()['count'] : 0;
$userCount = $userCountResult ? $userCountResult->fetch_assoc()['count'] : 0;
?>

<div class="form"> 
    <h1 class="main">Dashboard</h1>
</div>

<div class="container">
    <div class="card">
        <h2>Admin</h2>
        <h2><?php echo htmlspecialchars($adminCount); ?></h2>
    </div>

    <div class="card">
        <h2>Users</h2>
        <h2><?php echo htmlspecialchars($userCount); ?></h2>
    </div>
</div>
