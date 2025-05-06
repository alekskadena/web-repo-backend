
<?php
// Kontrollo nëse formulari është dërguar dhe shto përdoruesin në DB
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "db.php"; // Lidhja me DB

    $username = $_POST['username'];
    $email = $_POST['email'];
    $role_id = $_POST['role'];

    // Shto përdoruesin në DB
    $insert_sql = "INSERT INTO users (username, email, role_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $role_id);

    if (mysqli_stmt_execute($stmt)) {
        $user_id = mysqli_insert_id($conn);  // Merr ID-në e përdoruesit të sapo krijuar

        // Përgjigje pozitive me të dhënat e përdoruesit të shtuar
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
        // Përgjigje në rast të gabimit
        echo json_encode(['success' => false]);
    }

    mysqli_close($conn);
}
?>