<?php
session_start();
if ($_SESSION['role'] == 'admin') {
    header('Location: admin.php');
} else {
    header('Location: profile.php');
}
?>
