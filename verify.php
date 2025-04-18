<?php
  require("Connection.php");

  if(isset($_GET['email']) && isset($_GET['v_code'])){
    $email = $_GET['email'];
    $v_code = $_GET['v_code'];
    
    echo 'Email: ' . $_GET['email'];
    echo 'Verification Code: ' . $_GET['v_code'];

    $query=" SELECT * FROM `registered_users` WHERE `email`='$email' AND `verification_code`='$v_code'";
    $result=mysqli_query($conn, $query);  
    if($result){
      if(mysqli_num_rows($result)==1){
        $result_fetch=mysqli_fetch_assoc($result);
        if($result_fetch['is_verified']==0){
            $update_query = "UPDATE `registered_users` SET `is_verified` = 1 WHERE `email` = '$email' AND `verification_code` = '$v_code'";

           if(mysqli_query($conn,$update_query)){
             echo"
             <script>
                alert('Email verification successful');
                window.location.href='part1.php';
             </script>
            ";
           }
        }
        else{
            echo"
            <script>
                alert('Email already registered');
                window.location.href='part1.php';
            </script>
            ";
        }
      }
    }
    else{
        echo"
            <script>
                alert('Cannot run query');
                window.location.href='part1.php';
            </script>
            ";
    }
}



?>