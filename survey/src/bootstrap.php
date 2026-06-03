<?php
declare(strict_types=1);
$env = parse_ini_file(__DIR__ . '/../.env');
foreach ($env as $key => $value) {
    $_ENV[$key] = $value;
}

// Sessions
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(86400);
    session_start();
}

$_SESSION['last_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['student_data'])) {
    $_SESSION['student_data'] = [
        'is_anonymous'   => 0,
        'section_id'     => '',
        'surname'        => '',
        'given_name'     => '',
        'middle_name'    => '',
        'email'          => '',
        'student_number' => '',
    ];
}

if (!isset($_SESSION['admin_data'])) {
    $_SESSION['admin_data'] = [
        'admin_id'       => '',
        'role'           => '',
        'username'       => '',
        'first_name'     => '',
        'last_name'      => '',
    ];
}

if (!isset($_SESSION['errors']))              { $_SESSION['errors']              = [];    }
if (!isset($_SESSION['is_existing']))         { $_SESSION['is_existing']         = false; }
if (!isset($_SESSION['has_submitted']))       { $_SESSION['has_submitted']       = false; }
if (!isset($_SESSION['has_submitted_all']))   { $_SESSION['has_submitted_all']   = false; }
if (!isset($_SESSION['submitted_targets']))   { $_SESSION['submitted_targets']   = [];    }
if (!isset($_SESSION['stored_student_data'])) { $_SESSION['stored_student_data'] = [];    }

function clearSession() {
    unset($_SESSION['student_data']);
    unset($_SESSION['submitted_targets']);
    unset($_SESSION['has_submitted']);
    unset($_SESSION['has_submitted_all']);
}


// CSRF token
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }

function validateCsrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        $_SESSION['errors'] = ['Invalid CSRF token.'];
        header('Location: /survey/index.php');
        exit();
    }
}


// Helpers
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function redirectNoAdminData(): void {
    $id = $_SESSION['admin_data']['admin_id'];
    if (!(is_int($id) || (is_string($id) && ctype_digit($id)))) {
        header('Location: /survey/admin/login.php');
        http_response_code(302);
        exit;
    }
}

function redirectNoRespondentData(): void {
    $sd = $_SESSION['student_data'] ?? null;
    if ($sd === null) { header('Location: /survey/student_info.php'); exit; }

    $isAnonymous = (int)($sd['is_anonymous'] ?? 0);

    if ($isAnonymous === 1) {
        $missing = trim((string)($sd['section_id'] ?? '')) === '';
    } else {
        $required = ['section_id','surname','given_name','email','student_number'];
        $missing = false;
        foreach ($required as $k) {
            if (trim((string)($sd[$k] ?? '')) === '') { $missing = true; break; }
        }
    }

    if ($missing) { header('Location: /survey/student_info.php'); exit; }
}

function redirectNoSubmission(): void {
    if (empty($_SESSION['has_submitted']) && empty($_SESSION['has_submitted_all'])) {
        header('Location: /survey/index.php');
        exit();
    }
}

function redirectCompleted(): void {
    if (empty($_SESSION['submitted_targets']) || !is_array($_SESSION['submitted_targets'])) {
        $_SESSION['has_submitted_all'] = false;
        return;
    }

    $required = ['1','2','3','4','5'];
    $submitted = array_map('strval', $_SESSION['submitted_targets']);
    $all = empty(array_diff($required, $submitted));

    $_SESSION['has_submitted_all'] = $all;

    if ($all) {
        header('Location: /survey/thank_you.php');
        exit();
    }
}

function checkAndUpdateExistingStudent(Database $db): void {
    // If not anonymous and if not existing student
    if ($_SESSION['student_data']['is_anonymous'] !== 1 && empty($_SESSION['is_existing'])) { // Prevent repeated database interaction
        $existingStudent = $db->findStudent($_SESSION['student_data']);

        // if existing, populate submitted targets and check if submitted all for redirection
        if ($existingStudent !== null) {
            $_SESSION['student_data']      = $_SESSION['stored_student_data'] = $existingStudent;
            $_SESSION['is_existing']       = 1;
            $_SESSION['submitted_targets'] = $db->getSubmittedTargetsByStudentId($_SESSION['student_data']['student_id']);

            redirectCompleted();
        }
    }
}

// Show Splash Screen
function splashScreen(): void {
    $key = 'splash_shown_' . md5($_SERVER['REQUEST_URI']); // unique per page
    if (empty($_SESSION[$key])) {
        // <!-- Background Orbs -->
        echo "<div class=\"bg-orb bg-orb-1\"></div>";
        echo "<div class=\"bg-orb bg-orb-2\"></div>";
        
        // <!-- Splash Screen -->
        echo "<div class=\"splash-screen\">";
        echo "    <img src=\"assets/images/SchoolLogo.png\" alt=\"School Logo\" class=\"splash-logo\">";
        echo "    <div class=\"splash-text\">Nabia University</div>";
        echo "    <div class=\"loader-line\"></div>";
        echo "</div>";
        $_SESSION[$key] = true;
    }
}



// Set default language
function setLanguage() {
    $lang = 'en';

    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
        $_SESSION['lang'] = $lang;
    } elseif (isset($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
    }
}


// Debug: Show all superglobal variable data with types
function debug() {
    echo '<div style="background:#f4f4f4; border:1px solid #ccc; padding:10px; margin-top:20px;">';

    $printWithTypes = function($var, $indent = '') use (&$printWithTypes) {
        if (is_array($var)) {
            $output = "Array\n$indent(\n";
            foreach ($var as $key => $value) {
                $output .= $indent . "    [$key] => ";
                if (is_array($value)) {
                    $output .= $printWithTypes($value, $indent . '    ');
                } else {
                    $output .= gettype($value) . ': ' . var_export($value, true) . "\n";
                }
            }
            $output .= $indent . ")\n";
            return $output;
        }
        return gettype($var) . ': ' . var_export($var, true) . "\n";
    };

    echo '<h3>$_GET Data:</h3>';
    echo '<pre>' . $printWithTypes($_GET) . '</pre>';

    echo '<h3>$_POST Data:</h3>';
    echo '<pre>' . $printWithTypes($_POST) . '</pre>';

    echo '<h3>$_SESSION Data:</h3>';
    if (session_status() === PHP_SESSION_ACTIVE) {
        echo '<pre>' . $printWithTypes($_SESSION) . '</pre>';
    } else {
        echo '<em>Session not started. Call session_start() first.</em>';
    }

    echo '</div>';
}