<?php
require_once __DIR__ . '/../src/bootstrap.php';


if (isset($_SESSION['admin_data'])) {
    unset($_SESSION['admin_data']);
}

// Optionally regenerate session id to reduce fixation risk
session_regenerate_id(true);

// Redirect to login
header('Location: /survey/admin/login.php');
exit;
