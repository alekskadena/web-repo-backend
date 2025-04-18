<?php

require 'vendor/autoload.php';
require('connection.php');
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email,$v_code){ 
  $mail = new PHPMailer(true);
  try{
    $mail -> SMTPDebug = SMTP::DEBUG_OFF; // ndryshe nga me pare, OFF për përdorim real
    $mail -> isSMTP();
    $mail -> Host = 'smtp.gmail.com';
    $mail -> SMTPAuth = true;
    $mail -> Username = 'bangtanyeon777@gmail.com';
    $mail -> Password = 'xlvcjfzwnsjceqil';
    $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail -> Port = 587;

    $mail-> setFrom ('bangtanyeon777@gmail.com', 'Apollo-Skies');
    $mail -> addAddress($email);

    $mail -> isHTML(true);
    $mail -> Subject = 'Email Verification from Apollo-Skies';
    $mail -> Body = "Thanks for registration!
      Click the link below to verify the email address
      <a href='http://localhost:8080/login_register/verify.php?email=$email&v_code=$v_code'>Verify</a>";
    
    $mail ->send();
    return true;  
  } catch(Exception $e){
    echo "Mailer Error: {$mail->ErrorInfo}";
    return false;
  }
}

# ========== LOGIN ==========
if(isset($_POST['login'])){
    $email_username= $_POST['email_username'];
    $password = $_POST['password'];
    $login_role = $_POST['login_role'];

    // SHTUAM KLLAPAT per t’u siguruar që OR/AND janë të sakta
    $query="SELECT * FROM `registered_users` WHERE (`email`='$email_username' OR `username` = '$email_username') AND `role`='$login_role'";
    $result = mysqli_query($conn,$query);

    if($result){
        if(mysqli_num_rows($result)==1){
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['is_verified']==1){
                if(password_verify($password, $result_fetch['password'])){
                    $_SESSION['logged_in']=true;
                    $_SESSION['username']=$result_fetch['username'];
                    $_SESSION['role'] = $result_fetch['role'];

                    // Redirektim sipas rolit
                    if ($result_fetch['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: user_dashboard.php");
                    }
                    exit();
                } else {
                    echo "
                        <script>
                            alert('Incorrect Password');
                            window.location.href='part1.php';
                        </script>
                    ";
                }
            } else {
                echo "
                    <script>
                        alert('Email Not Verified');
                        window.location.href='part1.php';
                    </script>
                ";
            }
        } else {
            echo "
                <script>
                    alert('Email or Username Not Registered');
                    window.location.href='part1.php';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Cannot Run Query');
                window.location.href='part1.php';
            </script>
        ";
    }
}

# ========== REGISTER ==========
if(isset($_POST['register'])){
    $user_exist_query="SELECT * FROM `registered_users` WHERE `username` = '$_POST[username]' OR `email`= '$_POST[email]'";
    $result = mysqli_query($conn,$user_exist_query);

    if($result){
        if(mysqli_num_rows($result)>0){
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['username']==$_POST['username']){
                echo"
                <script>
                    alert('$result_fetch[username] - Username already taken');
                    window.location.href='part1.php';
                </script>
                ";
            } else {
                echo"
                <script>
                    alert('$result_fetch[email] - E-mail already taken');
                    window.location.href='part1.php';
                </script>
                ";
            }
        } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $v_code = bin2hex(random_bytes(16));

            // Roli default është 'user'
            $query = "INSERT INTO registered_users(full_name, username, email, `password`, `verification_code`, `is_verified`, `role`) VALUES ('$_POST[fullname]', '$_POST[username]', '$_POST[email]', '$password', '$v_code', '0', 'user')";

            if(mysqli_query($conn,$query) && sendMail($_POST['email'], $v_code)){
                echo"
                <script>
                    alert('Registration Successful. Check your email to verify.');
                    window.location.href='part1.php';
                </script>
                ";
            } else {
                echo"
                <script>
                    alert('Server Down');
                    window.location.href='part1.php';
                </script>
                ";
            }   
        }
    } else {
        echo"
        <script>
            alert('Cannot Run Query');
            window.location.href='part1.php';
        </script>
        ";
    }
}
?>
