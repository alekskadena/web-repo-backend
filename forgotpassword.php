<?php
require 'vendor/autoload.php';
require("db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bangtanyeon777@gmail.com'; // useri yt
        $mail->Password = 'xlvcjfzwnsjceqil'; // password aplikacioni yt
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('bangtanyeon777@gmail.com', 'Apollo-Skies');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link from Apollo-Skies';
        $mail->Body = "We got a request from you to reset your password! <br>
                       Click the link below: <br>
                       <a href='http://localhost:8080/Apollo-SKIES/web-repo-backend/updatepassword.html'>Reset Password</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

header('Content-Type: application/json');

// Marrim të dhënat nga kërkesa JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email'])) {
    $email = $data['email'];

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows == 1) {
        if (sendMail($email)) {
            echo json_encode(["message" => "Email sent successfully. Please check your inbox."]);
        } else {
            echo json_encode(["message" => "There was an error sending the email."]);
        }
    } else {
        echo json_encode(["message" => "Email address does not exist."]);
    }
} else {
    echo json_encode(["message" => "Email is required."]);
}
?>
