<?php
include 'conn.php'; // Ensure this file sets up the $conn mysqli connection
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email === '' || $password === '') {
        $error = 'Please fill all the fields!';
    } else {
        // Fetch the admin from the database
        $stmt = $conn->prepare('SELECT * FROM tbl_admin WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        // Check if the admin exists and verify the password
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/side.css">
    <link rel="stylesheet" href="./css/table.css">
    <link rel="stylesheet" href="./css/admin_dash.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="sidebar open">
        <div class="logo-details">
            <i class='bx bxl-s-plus-plus icon'></i>
            <div class="logo_name">ADMIN</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="dashboard.php" class="nav-link nav-home">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="user.php" class="nav-link nav-user">
                    <i class='bx bx-user'></i>
                    <span class="links_name">User</span>
                </a>
                <span class="tooltip">User</span>
            </li>
            <li>
                <a href="settings.php">
                    <i class='bx bx-cog'></i>
                    <span class="links_name">Setting</span>
                </a>
                <span class="tooltip">Setting</span>
            </li>
            <li class="profile">
                <div class="profile-details">
                    <img src="./img/received_1165837761442678.jpeg" alt="profileImg">
                    <div class="name_job">
                        <div class="name">Macabulos!</div>
                    </div>
                </div>
                <a href="admin_logout.php"  id="logout-btn"><i class='bx bx-log-out' id="log_out"></i></a>
            </li>
        </ul>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>ADMIN LOGOUT</h2>
            <br>
            <p>Are you sure you want to log out?</p>
            <button class="confirm-btn" id="confirm-logout">Yes</button>
            <button class="cancel-btn">Cancel</button>
        </div>
    </div>
    <script src="./js/admin.js"></script>
</body>
</html>
