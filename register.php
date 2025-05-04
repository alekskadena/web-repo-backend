<?php
// Debug: Shfaq gabime PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS konfigurimi
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin === 'http://localhost:5173') {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json"); // Siguro që kthen JSON

// Përgjigje për preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();
include "db.php"; // Baza e të dhënave

// Vetëm për POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Merr të dhënat e POST-it dhe bëj JSON decode
    
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Verifikimi që JSON është i vlefshëm
    if (!$data) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON input."]);
        exit();
    }

    // Merr të dhënat nga kërkesa dhe pastro
    $username = trim($data["username"] ?? '');
    $email = trim($data["email"] ?? '');
    $fullname = trim($data["fullname"] ?? '');
    $password = $data["password"] ?? '';
    $confirm_password = $data["confirm_password"] ?? '';

    // Validim i thjeshtë për të siguruar që të gjitha fushat janë të mbushura
    if ($username === '' || $email === '' || $fullname === '' || $password === '') {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    // Kontrollo që fjalëkalimet përputhen
    if ($password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit();
    }

    // Hasho fjalëkalimin për siguri
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cakto rolin bazuar në email (p.sh. @apollo.team është admin)
    $role_id = (strpos($email, '@apollo.team') !== false) ? 2 : 1;

    // Përgatit query për të regjistruar përdoruesin në bazën e të dhënave me prepared statement
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, role_id) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit();
    }

    // Bindi parametrat dhe ekzekuto query-n
    $stmt->bind_param("ssssi", $fullname, $email, $username, $hashed_password, $role_id);

    // Kontrollo nëse ekzekutimi ka pasur sukses
    if ($stmt->execute()) {
        // Ruaj të dhënat e përdoruesit në sesion
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role_id;

        // Redirekto përdoruesin në profilin përkatës
        $redirect = $role_id === 2 ? "admin.jsx" : "Profile.jsx";

        // Dërgo përgjigje JSON me status dhe informacionet e nevojshme për frontend
        echo json_encode([
            "status" => "success",
            "message" => "User registered successfully.",
            "redirect" => $redirect,
            "role" => $role_id // Kjo është ajo që frontend-i pret
        ]);
        exit();
    } else {
        // Dërgo përgjigje për gabimin nëse regjistrimi ka dështuar
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        exit();
    }

    $stmt->close();
    $conn->close();
    exit();
}
?> 
