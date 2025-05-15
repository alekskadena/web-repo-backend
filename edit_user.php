<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        //shikojme nese roli eshte admin or not
        echo '
        <form action="update_passenger.php" method="POST">
            <input type="hidden" name="id" value="' . $user['id'] . '">
            
            <label for="username">Username:</label>
            <input type="text" name="username" value="' . $user['username'] . '" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="' . $user['email'] . '" required><br><br>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="1" ' . ($user['role_id'] == 1 ? 'selected' : '') . '>User</option>
                <option value="2" ' . ($user['role_id'] == 2 ? 'selected' : '') . '>Admin</option>
            </select><br><br>

            <input type="submit" value="Update User">
        </form>';
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'User not found!'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No user ID provided!'
    ]);
}
?>
