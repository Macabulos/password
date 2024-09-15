<?php
session_start();
include 'conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this is the correct path to autoload.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['register'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Check if email is already registered
        $stmt = $conn->prepare('SELECT * FROM tbl_user WHERE email_address = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo 'Email is already registered!';
        } else {
            // Generate a random 6-digit verification code
            $verification_code = rand(100000, 999999);
            
            // Insert new user with verification code
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO tbl_user (name, email_address, password, is_active, verification_code) VALUES (?, ?, ?, 0, ?)');
            $stmt->bind_param('ssss', $name, $email, $hashedPassword, $verification_code);

            if ($stmt->execute()) {
                // Send verification code via email
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
                    $mail->Subject = 'Your Verification Code';
                    $mail->Body    = "Your verification code is: <b>$verification_code</b>";

                    $mail->send();
                    echo "Check your email for the verification code.";
                } catch (Exception $e) {
                    echo "Mail Error: " . $mail->ErrorInfo;
                }

                // Redirect to verification page
                $_SESSION['email'] = $email;
                header('Location: verify.php');
                exit();
            } else {
                echo 'Error: Could not execute the query.';
            }
        }
        $stmt->close();
    } elseif (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Check if the email and password are correct
        $stmt = $conn->prepare('SELECT * FROM tbl_user WHERE email_address = ? AND is_active = 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            header('Location: home.php');
            exit();
        } else {
            echo 'Invalid email or password, or account not verified.';
        }
        $stmt->close();
    }
    $conn->close(); // Close the connection
}
