<?php
include 'conn.php';
session_start();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login logic
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if ($email === '' || $password === '') {
            $msg = 'Please fill all the fields!';
        } else {
            // Prepare and execute the query
            $stmt = $conn->prepare('SELECT * FROM tbl_user WHERE email_address = ?');
            $stmt->bind_param('s', $email); // Bind the email parameter
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                if ($user['is_active'] == 1) {
                    $_SESSION['user_id'] = $user['id_user'];
                    header('Location: home.php');
                    exit();
                } else {
                    $msg = 'Please verify your email address before logging in.';
                }
            } else {
                $msg = 'Invalid email or password!';
            }
            $stmt->close();
        }
    } elseif (isset($_POST['register'])) {
        // Registration logic
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if ($name === '' || $email === '' || $password === '') {
            $msg = 'Please fill all the fields!';
        } else {
            // Check if the email is already registered
            $stmt = $conn->prepare('SELECT * FROM tbl_user WHERE email_address = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingUser = $result->fetch_assoc();

            if ($existingUser) {
                $msg = 'Email is already registered!';
            } else {
                // Hash the password and insert the new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO tbl_user (name, email_address, password, is_active) VALUES (?, ?, ?, 0)');
                $stmt->bind_param('sss', $name, $email, $hashedPassword);
                $stmt->execute();

                // Redirect to a page informing them to check their email
                $msg = 'Registration successful! Please verify your email address before logging in.';
                $stmt->close();
            }
        }
    }
    $conn->close(); // Close the connection
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="login_register.php" method="POST">
                <h1>Create Account</h1>
                <?php if ($msg && isset($_POST['register'])) echo '<p>' . htmlspecialchars($msg) . '</p>'; ?>
                <input type="text" name="name" placeholder="Name" required="" />
                <input type="email" name="email" placeholder="Email" required="" />
                <input type="password" name="password" placeholder="Password" required="" id="id_password" />
                <div class="g-recaptcha" data-sitekey="6LdNlh4qAAAAAIpeDXsMF1-mYqL7z1knWmAxGWRP"></div>
                <button class="btn" type="submit" name="register">Register</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="login_register.php" method="POST">
                <h1>Sign in</h1>
                <?php if ($msg && isset($_POST['login'])) echo '<p>' . htmlspecialchars($msg) . '</p>'; ?>
                <input type="email" name="email" placeholder="Email" required=""/>
                <input type="password" name="password" placeholder="Password" required="" id="password" />
                <label class="show-password-container">
                    <input class="check" type="checkbox" id="chk"> Show password
                </label>
                <div class="g-recaptcha" data-sitekey="6LdNlh4qAAAAAIpeDXsMF1-mYqL7z1knWmAxGWRP"></div>
                <a href="#">Forgot your password?</a>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
<script>
    const password = document.getElementById("password");
    const chk = document.getElementById("chk");
    chk.onchange = function() {
        password.type = chk.checked ? "text" : "password";
    };
</script>
    <script src="./js/script.js"></script>  
</body>
</html>