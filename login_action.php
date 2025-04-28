<?php
session_start();
include('db_connection.php'); // Lidhja me bazën e të dhënave

$username = $_POST['username'];
$password = $_POST['password'];

// Kërkoni përdoruesin në bazën e të dhënave
$query = "SELECT * FROM users WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();

$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Përdoruesi është i verifikuar, ruajmë të dhënat në sesion
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username']; // Ruajmë emrin e përdoruesit
    $_SESSION['email'] = $user['email']; // Ruajmë emailin në sesion
    $_SESSION['role'] = 'user'; // Default roli është 'user'

    // Kontrollo email-in për të identifikuar nëse është admin
    if (strpos($user['email'], '@tpa.apollo.team') !== false) {
        $_SESSION['role'] = 'admin'; // Nëse emaili përfundon me '@tpa.apollo.team', e bëjmë admin
    }

    // Ridrejtoje përdoruesin në faqen e profilit
    header('Location: profile.php');
    exit();
} else {
    // Gabim në hyrje, kthejmë një mesazh të gabuar në formatin JSON
    echo json_encode(['status' => 'error', 'message' => 'Username or password is incorrect.']);
    exit();
}
?>
