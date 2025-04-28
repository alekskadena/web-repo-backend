<?php
session_start();
include "db.php"; 

if (isset($_SESSION["role"]) && $_SESSION["role"] === 2) {
    echo json_encode(["status" => "success", "role" => "admin"]);
} else {
    echo json_encode(["status" => "error", "role" => "user"]);
}
?>
