<?php
session_start();
include 'apollodb.php';


$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "Both fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['fullname'] = $row['fullname'];
                header("Location: index.html");
                exit();
            } else {
                $message = "Invalid password!";
            }
        } else {
            $message = "No user found with that email.";
        }
    }
}

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50));
        $sql = "UPDATE users SET reset_token='$token' WHERE email='$email'";
        $conn->query($sql);

        // Send email with reset link
        $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";
        mail($email, "Reset Password", "Click this link to reset the password: $reset_link");
        echo "Link is sent in your email address.";
    } else {
        echo "User does not exist!";
    }

    $conn->close();
?>

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <p style="color:red;"><?php echo $message; ?></p>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <form action="forgot_password.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Dërgo Linkun e Rikuperimit</button>
    </form>
</body>
</html>

