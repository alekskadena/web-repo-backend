
<?php
include('db.php'); // Lidhja me bazën e të dhënave
$response = [];

// Merr të gjithë përdoruesit nga tabela 'users'
$query = "SELECT id, username, email, role_id FROM users";
$result = mysqli_query($conn, $query);

if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $role = ($row['role_id'] == 2) ? 'Admin' : 'User'; // Përcakto rolin
        $users[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email'],
            'role' => $role
        ];
    }
    $response['users'] = $users;
} else {
    $response['error'] = 'Error fetching users from database.';
}
echo json_encode($response); // Kthe të dhënat në format JSON
?>