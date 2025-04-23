<?php
require 'vendor/autoload.php';
require("db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $reset_token) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bangtanyeon777@gmail.com';
        $mail->Password = 'xlvcjfzwnsjceqil';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('bangtanyeon@gmail.com', 'Apollo-Skies');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link from Apollo-Skies';
        $mail->Body = "We got a request from you to reset your password! <br>
Click the link below: <br>
<a href='http://localhost:8080/web-repo-backend/forgot_password.php?email=$email&reset_token=$reset_token'>Reset Password</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = $_POST["email"];
    $reset_token = bin2hex(random_bytes(32));
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows == 1) {
        $conn->query("UPDATE users SET reset_token='$reset_token' WHERE email='$email'");

        if (sendMail($email, $reset_token)) {
            echo "Email is sent successfully";
        } else {
            echo "There was an error while sending the email.";
        }
    } else {
        echo "Email address doesn't exist.";
    }
}
?>
<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <button type="submit">Send a reset password link</button>
</form>

<?php

if (isset($_POST['send-reset-link'])) {
    $email = $_POST['email'];
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $reset_token = bin2hex(random_bytes(16));
            date_default_timezone_set('Europe/Tirane');
            $date = date("Y-m-d");

            $query = "UPDATE users SET resettoken='$reset_token', resettokenexpire='$date' WHERE email='$email'";
            if (mysqli_query($conn, $query) && sendMail($email, $reset_token)) {
                echo "
                    <script>
                        alert('Password Reset Link Sent to mail');
                        window.location.href='part1.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Server Down! Try again later');
                        window.location.href='part1.php';
                    </script>
                ";
            }
        } else {
            echo "
                <script>
                    alert('Email not found');
                    window.location.href='part1.php';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Cannot run query');
                window.location.href='part1.php';
            </script>
        ";
    }
}
?>