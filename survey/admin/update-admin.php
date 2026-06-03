<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/database.php';


$db       = new database();
$pdo      = $db->connect();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminData = [];
    $adminId   = filter_input(INPUT_POST, 'admin_id', FILTER_VALIDATE_INT);
    $fname     = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname     = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);

    // Validate required fields
    if ($adminId === false || $adminId <= 0) {
        $_SESSION['errors'] = 'Invalid admin ID.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $adminData['id']         = $adminId;
    $adminData['first_name'] = $fname;
    $adminData['last_name']  = $lname;

     // Get current admin
    $currentAdmin = $db->getAdminById($adminId);
    if (!$currentAdmin) {
        redirectWithMessage($referrerParts['path'], $queryParams, false, 'Admin not found.');
    }

    $arraysAreEqual = ($adminData == $currentAdmin);

    // No changes? Redirect back
    if ($arraysAreEqual) {
        $_SESSION['flash_info_admin'] = 'No changes detected.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Uncomment for required last name and first name
    // if (empty($fname) || empty($lname)) {
    //     $_SESSION['errors'] = 'First name and last name are required.';
    //     header("Location: " . $_SERVER['HTTP_REFERER']);
    //     exit;
    // }



    if ($db->updateAdminProfile($adminData)) {
        $_SESSION['admin_data']['first_name'] = $adminData['first_name'];
        $_SESSION['admin_data']['last_name']  = $adminData['last_name'];
        $_SESSION['flash_success_admin'] = 'Admin profile updated successfully.';
        session_regenerate_id(true);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        $_SESSION['flash_error'] = 'Failed to update admin profile.';
        error_log("Admin update failed for ID: $adminId");
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}