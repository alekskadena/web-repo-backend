<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    http_response_code(204);
    exit;
}

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bangtanyeon777@gmail.com';
        $mail->Password = 'xlvcjfzwnsjceqil'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('bangtanyeon777@gmail.com', 'Apollo-Skies');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link from Apollo-Skies';

        $resetLink = "http://localhost:5173/updatepassword?token=" . $token;
        $mail->Body = "We got a request to reset your password!<br><br>
                       Click the link below:<br>
                       <a href='$resetLink'>Reset Password</a><br><br>
                       If you didn’t request this, please ignore this email.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (isset($data['email'])) {
    $email = trim($conn->real_escape_string($data['email']));

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check && $check->num_rows == 1) {
        $token = bin2hex(random_bytes(16));

        $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_token_expire=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email=?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        if (sendMail($email, $token)) {
            echo json_encode(["message" => "Reset link sent to your inbox! Please check your inbox."]);
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
