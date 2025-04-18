<?php
  require 'vendor/autoload.php';
  require("connection.php");

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  function sendMail($email,$reset_token){
    $mail = new PHPMailer(true);

    try{
    $mail -> SMTPDebug = SMTP::DEBUG_SERVER;
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
    $mail -> Subject = 'Password Reset Link from Apollo-Skies';
    $mail -> Body = "We got a request from you to reset your password! <br>
      Click the link below: <br>
      <a href='http://localhost:8080/login_register/updatepassword.php?email=$email&reset_token=$reset_token'>Reset Password</a>";
    
    $mail ->send();
    return true;  
  } catch(Exception $e){
    return false;
    }
}
  

  if(isset($_POST['send-reset-link'])){
    $query="SELECT * FROM registered_users WHERE email= '$_POST[email]'";
    $result=mysqli_query($conn,$query);
    if($result){
        if(mysqli_num_rows($result)==1){
            $reset_token = bin2hex(random_bytes(16));
            date_default_timezone_set('Europe/Tirane');
            $date=date("Y-m-d");
            $query="UPDATE `registered_users` SET `resettoken`='$reset_token', `resettokenexpire`='$date' WHERE `email`='$_POST[email]'";
            if(mysqli_query($conn,$query) && sendMail($_POST['email'], $reset_token)){
                echo "
                  <script>
                    alert('Password Reset Link Sent to mail');
                    window.location.href='part1.php';
                  </script>
                ";
            }
            else{
                echo "
                  <script>
                    alert('Server Down! Try again later');
                    window.location.href='part1.php';
                  </script>
                ";
            }
       
        }
        else{
        echo "
          <script>
            alert('Email not found);
            window.location.href='part1.php';
          </script>
        ";
        }
    }
    else{
        echo "
          <script>
            alert('cannot run query');
            window.location.href='part1.php';
          </script>
        ";
    }
  }

?>
