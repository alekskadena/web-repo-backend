<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Verifikoni nëse të dhënat janë të vlefshme
    if (empty($username) || empty($email) || empty($role)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    // Përditësoni përdoruesin në databazë
    $stmt = mysqli_prepare($conn, "UPDATE users SET username = ?, email = ?, role_id = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssii', $username, $email, $role, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
