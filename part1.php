<?php 
  require('connection.php');
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User - Login and Register</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        #roleSelection, #loginForm { display: none; margin-top: 20px; }
        button { margin: 5px; }
        .popup-container {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .popup {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        .popup h2 { margin-top: 0; }
        .popup input, .popup button, .popup select {
            margin: 10px 0;
            width: 100%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h2>Apollo-Skies</h2>
        <nav>
            <a href="#">PROFILE</a>
            <a href="#">OFFERS</a>
            <a href="#">BOOKING</a>
        </nav>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            echo "<div class='user'>";
            echo $_SESSION['username'] . " - <a href='logout.php'>LOGOUT</a>";
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                echo " | <a href='admin_dashboard.php'>ADMIN PANEL</a>";
            }
            echo "</div>";
        } else {
            echo "
            <div class='sign-in-up'>
                <button type='button' onclick=\"popup('role-select-popup')\">LOGIN</button>
                <button type='button' onclick=\"popup('register-popup')\">REGISTER</button>
            </div>
            ";
        }
        ?> 
    </header>

    <!-- POPUP: Zgjedhja e Rol-it -->
    <div class="popup-container" id="role-select-popup">
        <div class="popup">
            <h2>
                <span>Login as:</span>
                <button type="reset" onclick="popup('role-select-popup')">X</button>
            </h2>
            <button onclick="openLoginWithRole('user')">As User</button>
            <button onclick="openLoginWithRole('admin')">As Admin</button>
        </div>
    </div>

    <!-- POPUP: Login -->
    <div class="popup-container" id="login-popup"> 
        <div class="popup">
            <form method="POST" action="login_register.php">
                <h2>
                    <span>USER LOGIN</span>
                    <button type="reset" onclick="popup('login-popup')">X</button>
                </h2>
                <input type="text" placeholder="E-mail or Username" name="email_username" required>
                <input type="password" placeholder="Password" name="password" required>
                <input type="hidden" id="loginRoleInput" name="login_role" value="" required>
                <button type="submit" class="login-btn" name="login">Login</button>
            </form>
            <div class="forgot-btn">
                <button type="button" onclick="forgotPopup()">Forgot Password?</button>
            </div>
        </div>
    </div>

    <!-- POPUP: Register -->
    <div class="popup-container" id="register-popup">
        <div class="register popup">
            <form method="POST" action="login_register.php">
                <h2>
                    <span>USER REGISTER</span>
                    <button type="reset" onclick="popup('register-popup')">X</button>
                </h2>
                <input type="text" placeholder="Full Name" name="fullname" required>
                <input type="text" placeholder="Username" name="username" required>
                <input type="email" placeholder="E-mail" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" class="register-btn" name="register">REGISTER</button>
            </form>
        </div>
    </div>

    <!-- POPUP: Forgot Password -->
    <div class="popup-container" id="forgot-popup"> 
        <div class="forgot popup">
            <form method="POST" action="forgotpassword.php">
                <h2>
                    <span>RESET PASSWORD</span>
                    <button type="reset" onclick="popup('forgot-popup')">X</button>
                </h2>
                <input type="text" placeholder="E-mail" name="email">
                <button type="submit" class="reset-btn" name="send-reset-link">SEND LINK</button>
            </form>
        </div>
    </div>

    <!-- Mesazhi Welcome -->
    <?php
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
        echo "<h1 style='text-align:center; margin-top: 200px'>WELCOME TO THIS WEBSITE - $_SESSION[username]</h1>";
        if($_SESSION['role'] == 'admin'){
            echo "<p style='text-align:center; color:red;'>You are logged in as <strong>ADMIN</strong></p>";
            echo "<div style='text-align:center; margin-top:20px;'>
                    <a href='admin_dashboard.php' style='padding:10px 20px; background:red; color:white; text-decoration:none; border-radius:5px;'>Go to Admin Dashboard</a>
                  </div>";
        } else {
            echo "<p style='text-align:center;'>You are logged in as a regular user</p>";
        }
    }
    ?>

    <!-- JavaScript -->
    <script>
        function popup(popup_name){
            const get_popup = document.getElementById(popup_name);
            if(get_popup.style.display === "flex"){
                get_popup.style.display = "none";
            } else {
                get_popup.style.display = "flex";
            }
        }

        function forgotPopup(){
            document.getElementById('login-popup').style.display = "none";
            document.getElementById('forgot-popup').style.display = "flex";
        }

        function openLoginWithRole(role) {
            document.getElementById('role-select-popup').style.display = 'none';
            document.getElementById('login-popup').style.display = 'flex';
            document.getElementById('loginRoleInput').value = role;
        }
    </script>
</body>
</html>
