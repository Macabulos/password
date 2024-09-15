<?php
session_start();
include "conn.php";

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $code = trim($_POST['verification_code']);

    // Check if the code matches
    $stmt = $conn->prepare('SELECT * FROM tbl_user WHERE email_address = ? AND verification_code = ?');
    $stmt->bind_param('ss', $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Activate the user account
        $update_stmt = $conn->prepare('UPDATE tbl_user SET is_active = 1 WHERE email_address = ?');
        $update_stmt->bind_param('s', $email);
        if ($update_stmt->execute()) {
            $msg = "Your account has been verified successfully!";
            unset($_SESSION['email']); // Clear session data

            header('Location: login.php');
            exit();
        } else {
            $msg = "Error activating account. Please try again.";
        }
    } else {
        $msg = "Invalid verification code.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Enter Verification Code</title>
    <link rel="stylesheet" href="./css/verify.css">
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header text-center">
                <h3>Enter Verification Code</h3>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="verification_code">Verification Code</label>
                        <input type="text" class="form-control" name="verification_code" id="verification_code" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Verify</button>
                </form>
                <p><?php echo htmlspecialchars($msg); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
