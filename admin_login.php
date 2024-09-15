<?php
include 'conn.php'; // Ensure this file sets up the $conn mysqli connection
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_login'])) {
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

        // Debugging output
        // var_dump($admin);

        // Verify the password
        if ($admin && password_verify($password, $admin['password'])) {
            // Password is correct
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/admin.css">
    <title>Login</title>
</head>
<body>
    <form action="" method="POST"> <!-- Form action set to empty to submit to the same page -->
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <div class="row border rounded-5 p-3 bg-white shadow box-area">
                <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #170483;">
                    <div class="featured-image mb-3">
                        <img src="./img/software-engineer.png" class="img-fluid" style="width: 250px; border-radius: 10px;">
                    </div>
                    <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">ADMIN LOGIN</p>
                    <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace;">Macabulos, John Lester your Personal web Developer</small>
                </div>
                <div class="col-md-6 right-box">
                    <div class="row align-items-center">
                        <div class="header-text mb-4">
                            <h2>SIGN IN</h2>
                            <p>We are happy to have you back.</p>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control form-control-lg bg-light fs-6" required placeholder="Email">
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" name="password" id="password" class="form-control form-control-lg bg-light fs-6" required placeholder="Password">
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="chk">
                                <label for="chk" class="form-check-label text-secondary"><small>Show Password</small></label>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" name="admin_login" class="btn btn-lg btn-primary w-100 fs-6">Sign In</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        const password = document.getElementById("password");
        const chk = document.getElementById("chk");
        chk.onchange = function() {
            password.type = chk.checked ? "text" : "password";
        };
    </script>
</body>
</html>
