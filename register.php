<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this is the correct path to autoload.php

if (isset($_POST['password-reset-token']) && isset($_POST['email'])) {
    include "conn.php";

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if email already exists
    $result = mysqli_query($conn, "SELECT * FROM tbl_user WHERE email_address='$email'");
    $row = mysqli_num_rows($result);

    if ($row < 1) {
        $token = md5($email) . rand(10, 9999);

        // Insert new user with the generated token
        $stmt = $conn->prepare("INSERT INTO tbl_user (name, email_address, password, is_active, verification_token) VALUES (?, ?, ?, 0, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Use password hashing
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $token);
        $stmt->execute();

        // Create verification link
        $link = "<a href='http://localhost/email-verification/verify-email.php?key=$email&token=$token'>Click and Verify Email</a>";

        // Configure PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = "utf-8";
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'lorem.ipsum.sample.email@gmail.com'; // Replace with your email address
            $mail->Password = 'novtycchbrhfyddx'; // Replace with your email password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('johnlestermacabulos@gmail.com', 'John Lester Macabulos'); // Replace with your email address and name
            $mail->addAddress($email);
            $mail->addReplyTo('johnlestermacabulos@gmail.com', 'John Lester Macabulos');

            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body    = "Please click the link below to verify your email address: <br><br>
                              <a href='http://yourdomain.com/verify.php?token=$token'>Verify Email</a>";

            $mail->send();
            echo "Check your email box and click on the verification link.";
        } catch (Exception $e) {
            echo "Mail Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "You have already registered with us. Check your email box and verify your email.";
    }
}

