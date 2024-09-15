<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute the query to get user details
$stmt = $conn->prepare('SELECT * FROM tbl_user WHERE id_user = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$encryption_key = 'your-encryption-key'; // Replace with your actual encryption key
$iv = '1234567891011121'; // Replace with your actual IV, ensure it's 16 bytes for AES-256-CBC

// Function to decrypt passwords
function decryptPassword($encryptedPassword, $encryption_key, $iv) {
    return openssl_decrypt($encryptedPassword, 'aes-256-cbc', $encryption_key, 0, $iv);
}

// Decrypt the password (ensure the password is encrypted before storing)
$decryptedPassword = decryptPassword($user['password'], $encryption_key, $iv);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Home - Password Manager</title>
        <link rel="stylesheet" href="./css/nav.css" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body>
        <nav class="navtop">
            <div>
                <h1>Password Manager</h1>
                <a href="logout.php" id="logout-btn"><i class="fas fa-sign-out-alt" id="log_out"></i>Log Out</a>  
            </div>
        </nav>
        <div class="content">
            <h2>Home</h2>
            <p>Welcome to your profile page!</p>
        </div>
        <div class="content read">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Account Verify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($user['id_user']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email_address']) ?></td>
                        <td><?= $user['is_active'] ? 'DECRYPTED' : 'DECRYPTED' ?></td>
                        <td><?= $user['is_active'] ? 'Verified' : 'Not Verified' ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="user">User LogOut</h2>
            <p>Are you sure you want to log out?</p>
            <button class="confirm-btn" id="confirm-logout">Yes</button>
            <button class="cancel-btn">Cancel</button>
        </div>
    </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script src="./js/logout.js"></script>
    </body>
</html>
