<?php
session_start();
header('Content-Type: application/json');

// Check if the user has admin privileges
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
    echo json_encode(['error' => 'Access Denied']);
    http_response_code(403); // Access denied
    exit;
}

include('db.php');

$response = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Ensure ID is an integer

    // Check if the user exists before deleting (optional but good for security)
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE id = ?");
    if ($check === false) {
        $response['error'] = 'Database error during check.';
        echo json_encode($response);
        exit;
    }

    mysqli_stmt_bind_param($check, "i", $id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        // Proceed with deleting the user
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            $response['error'] = 'Database error during deletion.';
        } else {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                $response['message'] = 'User deleted successfully.';
            } else {
                $response['error'] = 'Error deleting user.';
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $response['error'] = 'User not found.';
    }

    mysqli_stmt_close($check);
} else {
    $response['error'] = 'User ID is missing.';
}

mysqli_close($conn);
echo json_encode($response);
?>
