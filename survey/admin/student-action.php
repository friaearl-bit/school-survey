<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/form_handler.php';

$db = new database();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit;
}

validateCsrf();

function redirectWithMessage(string $path, array $queryParams, bool $isSuccess, string $message): void
{
    $_SESSION[$isSuccess ? 'flash_success' : 'flash_error'] = $message;
    $queryParams['status'] = $isSuccess ? 'success' : 'error';
    header("Location: $path?" . http_build_query($queryParams));
    exit;
}


// Common Setup
$studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
$action    = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

// Get referrer and clean query params
$referrer      = $_SERVER['HTTP_REFERER'] ?? '/survey/admin/index.php';
$referrerParts = parse_url($referrer);
$queryParams   = [];
if (isset($referrerParts['query'])) {
    parse_str($referrerParts['query'], $queryParams);
}
unset($queryParams['status'], $queryParams['action'], $queryParams['id']); // Clean up old params

// Validate student ID
if ($studentId === false || $studentId <= 0) {
    redirectWithMessage($referrerParts['path'], $queryParams, false, 'Invalid student ID.');
}

try {
    switch ($action) {
        // ========== EDIT ACTION ==========
        case 'edit':
            [$studentData, $errors] = processStudentInfo($_POST);
            $studentData['student_id'] = $studentId;

            // Remove is_anonymous field as getStudentById() do not return this item
            unset($studentData['is_anonymous']);


            // Store validation errors only
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['updated_student_data'] = $studentData;
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            // Get current student
            $currentStudent = $db->getStudentById($studentId);
            if (!$currentStudent) {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Student not found.');
            }

            $arraysAreEqual = ($studentData == $currentStudent);

            // No changes? Redirect back
            if ($arraysAreEqual) {
                // unset($_SESSION['errors'], $_SESSION['updated_student_data']);
                $_SESSION['flash_info_student'] = 'No changes detected.';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            // Update only if changed
            if ($db->updateStudent($studentData)) {
                // unset($_SESSION['errors'], $_SESSION['updated_student_data']);
                redirectWithMessage($referrerParts['path'], $queryParams, true, 'Student #' . $studentId . ' updated successfully.');
            } else {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Failed to update student #' . $studentId . '.');
            }
            break;

        // ========== DELETE ACTION ==========
        case 'delete':
            // Check if student exists and is not already deleted
            $stmt = $pdo->prepare("SELECT deleted_at FROM student WHERE student_id = ?");
            $stmt->execute([$studentId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$student) {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Student not found.');
            }
            if ($student['deleted_at'] !== null) {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Student #' . $studentId . ' has already been deleted.');
            }
            if ($db->softDeleteStudent($studentId)) {
                redirectWithMessage($referrerParts['path'], $queryParams, true, 'Student #' . $studentId . ' deleted successfully.');
            } else {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Failed to delete student #' . $studentId . '.');
            }
            break;


        // ========== RESTORE ACTION ==========
        case 'restore':
            // Check if student exists and is deleted
            $stmt = $pdo->prepare("SELECT deleted_at FROM student WHERE student_id = ?");
            $stmt->execute([$studentId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student['deleted_at'] === null) {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Student #' . $studentId . ' is not deleted.');
            }
            if ($db->restoreStudent($studentId)) {
                redirectWithMessage($referrerParts['path'], $queryParams, true, 'Student #' . $studentId . ' restored successfully.');
            } else {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Failed to restore student #' . $studentId . '.');
            }
            break;


            if (!$student) {
                redirectWithMessage($referrerParts['path'], $queryParams, false, 'Student not found.');
            }
        // ========== INVALID ACTION ==========
        default:
            redirectWithMessage($referrerParts['path'], $queryParams, false, 'Invalid action.');
    }

    // Final redirect for all actions
    header("Location: " . $referrerParts['path'] . '?' . http_build_query($queryParams));
    exit;

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    redirectWithMessage($referrerParts['path'], $queryParams, false, 'Database error. Please try again.');
}

