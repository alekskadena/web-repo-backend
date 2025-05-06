<?php
session_start();
include "db.php";

// Kontrollo nëse përdoruesi ka rolin 'Admin' (role_id == 2)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Access Denied']);
    exit();
}

// Marrja e të dhënave të përdoruesve nga baza e të dhënave
$sql = "SELECT id, username, email, role_id FROM users";
$result = mysqli_query($conn, $sql);

$users = []; // Përdorim një array për të ruajtur të dhënat
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Përdorim role_id për të përcaktuar rolin (1 = User, 2 = Admin)
        $role = ($row['role_id'] == 2) ? 'Admin' : 'User';
        
        // Shto përdoruesin në array
        $users[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email'],
            'role' => $role
        ];
    }
} else {
    $users = null;  // Nëse nuk ka përdorues
}

mysqli_close($conn); // Mbyll lidhjen me databazën

// Ruaj të dhënat e përdoruesve në sesion për t'i përdorur në dashboard.html
header("Content-Type: application/json");
echo json_encode(['users' => $users]);
?>
