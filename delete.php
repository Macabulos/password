<?php
include 'conn.php'; // Ensure this file sets up the $conn mysqli connection
session_start();

$msg = '';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Check that the user ID exists
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = (int)$_GET['id'];

    // Retrieve and sanitize input
    $stmt = $conn->prepare('SELECT * FROM tbl_user WHERE id_user = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    if (!$record) {
        exit('Record doesn\'t exist with that ID!');
    }

    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] === 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $conn->prepare('DELETE FROM tbl_user WHERE id_user = ?');
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $msg = 'You have deleted the record!';
            // Redirect to the dashboard page after deletion
            header('Location: user.php');
            exit();
        } else {
            // User clicked the "No" button, redirect them back to the dashboard page
            header('Location: user.php');
            exit();
        }
    }
} else {
    exit('No valid ID specified!');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Record</title>
    <link rel="stylesheet" href="./css/delete.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="content delete">
    <h2>Delete Record</h2>
    <?php if (!$msg): ?>
    <p>Are you sure you want to delete Record #<?= htmlspecialchars($record['id_user']) ?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?= htmlspecialchars($record['id_user']) ?>&confirm=yes" class="btn btn-danger">Yes</a>
        <a href="user.php" class="btn btn-secondary">No</a>
    </div>
    <?php else: ?>
    <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
</div>
</body>
</html>
