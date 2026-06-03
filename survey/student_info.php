<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/database.php';
require_once __DIR__ . '/src/form_handler.php';

redirectCompleted();

$db       = new database();
$pdo      = $db->connect();

// Fetch sections
$sections = $pdo->query("SELECT section_id, name FROM section ORDER BY name")->fetchAll();


// Load sticky state and errors from session
$studentData = $_SESSION['student_data'];
$errors      = $_SESSION['errors'];

// debug();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validateCsrf();
    [$studentData, $errors] = processStudentInfo($_POST);
    $_SESSION['student_data'] = $studentData;

    if ($studentData !== $_SESSION['stored_student_data']) {
        $_SESSION['submitted_targets'] = [];
        $_SESSION['is_existing'] = 0;
        checkAndUpdateExistingStudent($db);
    }

    if (!empty($errors)) {
// store errors+respondent_data and redirect back
        $_SESSION['errors'] = $errors;
        $_SESSION['student_data'] = $_SESSION['stored_student_data'] = $studentData;
        header('Location: student_info.php', true, 303);
        exit();
    }

// Validation success / Persist respondent data
    $_SESSION['student_data'] = $_SESSION['stored_student_data'] = $studentData;
    unset($_SESSION['errors']);
    // debug();

// Redirect to survey (GET)
    header('Location: survey.php', true, 303);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Information</title>
    <link rel="stylesheet" href="/survey/assets/css/styles.css" />
    <!-- <link rel="stylesheet" href="styles.css" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/survey/assets/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/survey/assets/favicon.ico" type="image/x-icon">
    <link href="/survey/assets/fonts/poppins/css/poppins.css" rel="stylesheet" >
</head>
<body>

    <!-- Splash Screen -->
    <div class="splash-screen" id="splashScreen">
        <img src="/survey/assets/images/SchoolLogo.png" alt="School Logo" class="splash-logo">
        <div class="splash-text">Loading Portal</div>
        <div class="loader-line"></div>
    </div>

    <!-- Background Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <div class="main-wrapper">
        <a href="index.php" class="academic-header">
            <img src="/survey/assets/images/SchoolLogo.png" alt="School Logo" class="academic-logo">
            <div class="academic-text">
                <span class="univ-name">Nabia University</span>
                <span class="univ-sub">Value academic and passion</span>
            </div>
        </a>
        
        <form id="studentInfoForm" method="POST" action="<?= e($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <div class="card">
                <h1>Student Information</h1>

                <?php if (!empty($errors)): ?>
                    <div class="card flash-card error"><ul><?php foreach($errors as $er): ?><li><p><?= e($er) ?></p></li><?php endforeach; ?></ul></div>
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" id="surname" name="surname" value="<?= e($studentData['surname'] ?? '') ?>" placeholder="e.g. Dela Cruz" tabindex="1" required>
                    </div>
                    <div class="form-group">
                        <label for="gName">Given Name</label>
                        <input type="text" id="gName" name="given_name" value="<?= e($studentData['given_name'] ?? '') ?>" placeholder="e.g. Juan" tabindex="1" required>
                    </div>
                    <div class="form-group">
                        <label for="mName">Middle Name</label>
                        <input type="text" id="mName" name="middle_name" value="<?= e($studentData['middle_name'] ?? '') ?>" placeholder="e.g. Soria" tabindex="1">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group flex-2">
                        <label for="email">Enter Email</label>
                        <input type="email" id="email" name="email" value="<?= e($studentData['email'] ?? '') ?>" placeholder="email@school.edu" tabindex="1" required>
                    </div>
                    <div class="form-group flex-1">
                        <label for="dtoday">Select Date</label>
                        <input type="date" id="dtoday" name="dateToday" value="<?= date("Y-m-d") ?>" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="studentNumber">Student Number</label>
                        <input type="text" id="studentNumber" name="student_number" value="<?= e($studentData['student_number'] ?? '') ?>" placeholder="2024-00000-MN-0" tabindex="1" required>
                    </div>

                    <div class="form-group flex-1">
                        <label for="section">Select Section</label>
                        <select name="section_id" id="section" tabindex="1" required>
                            <option value="" disabled selected>Select your section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= $section['section_id'] ?? '' ?>"
                                    <?= (isset($studentData['section_id']) && $section['section_id'] == $studentData['section_id']) ? 'selected' : '' ?>>
                                    <?= e($section['name'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="footer-actions">
                    <div class="checkbox-group">
                        <input type="checkbox" id="anonymous" name="is_anonymous" value="1" <?= ((int)($studentData['is_anonymous'] ?? 0) === 1) ? 'checked' : '' ?> tabindex="1">
                        <label for="anonymous">Keep it anonymous</label>
                    </div>
                    <button type="submit" id="homePageButton" class="btn-submit" tabindex="1">Proceed to Survey</button>
                </div>
            </div>
        </form>
    </div>
  <script src="/survey/assets/js/school.js"></script>
    <script>
        function isAnonymous() {
            const checkbox = document.getElementById('anonymous');
            const fields = ['surname','gName','mName','email','studentNumber'];

            function setDisabled(disabled) {
                fields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.disabled = disabled;
                });
            }

            setDisabled(<?= $studentData['is_anonymous'] ? 'true' : 'false' ?>);

            if (checkbox) {
                checkbox.addEventListener('change', () => setDisabled(checkbox.checked));
            }
            evaluateLiveCards();
        }
        isAnonymous();

    </script>
</body>
</html>