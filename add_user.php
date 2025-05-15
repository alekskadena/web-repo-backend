
<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "db.php"; 

    $username = $_POST['username'];
    $email = $_POST['email'];
    $role_id = $_POST['role'];

    //shtimi i perdoruesit n dbapolllo
    $insert_sql = "INSERT INTO users (username, email, role_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $role_id);

    if (mysqli_stmt_execute($stmt)) {
        $user_id = mysqli_insert_id($conn);  // ky kusht mundeson qe te marri at id e krijuar dhe shikon nese jan plotsu t dhenat

        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $user_id,
                'username' => $username,
                'email' => $email,
                'role' => $role_id == 1 ? 'User' : 'Admin'
            ]
        ]);
    } else {
        echo json_encode(['success' => false]);
    }

    mysqli_close($conn);
}
?>