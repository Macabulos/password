<?php
include 'conn.php'; // Ensure this file sets up the $conn mysqli connection
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM tbl_user WHERE id_user = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        exit('User not found');
    }
} else {
    exit('No ID specified');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $verification_code = isset($_POST['verification_code']) ? trim($_POST['verification_code']) : '';
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;

    if ($name && $email) {
        if ($password) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE tbl_user SET name = ?, email_address = ?, password = ?, verification_code = ?, is_active = ? WHERE id_user = ?');
            $stmt->bind_param('ssssii', $name, $email, $password, $verification_code, $is_active, $user_id);
        } else {
            $stmt = $conn->prepare('UPDATE tbl_user SET name = ?, email_address = ?, verification_code = ?, is_active = ? WHERE id_user = ?');
            $stmt->bind_param('sssii', $name, $email, $verification_code, $is_active, $user_id);
        }
        $stmt->execute();
        header('Location: user.php');
        exit();
    } else {
        $error = 'Please fill in all required fields!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/update.css">
</head>
<body>
<div class="container mt-5">
    <h2>Update User</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="update.php?id=<?= htmlspecialchars($user['id_user']) ?>" method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email_address']) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password (leave blank if you don't want to change it)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="verification_code">Account Verify code</label>
            <input type="text" name="verification_code" id="verification_code" class="form-control" value="<?= htmlspecialchars($user['verification_code']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>
