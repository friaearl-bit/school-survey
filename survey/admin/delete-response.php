<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/database.php';


$db       = new database();
$pdo      = $db->connect();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validateCsrf();
    $responseId = (int)$_POST['response_id'] ?? null;
    

    if ($db->softDeleteResponse($responseId)) {
        $_SESSION['flash_success_delete_response'] = 'response deleted successfully.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}