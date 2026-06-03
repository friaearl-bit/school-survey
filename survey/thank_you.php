<?php
require_once __DIR__ . '/src/bootstrap.php';
redirectNoSubmission();
redirectNoRespondentData();

// Debug: Clear Session
if (isset($_GET['unset_student_data']) && $_GET['unset_student_data'] == '1') {
    clearSession();
}

?>
      
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Thank You</title>
    <link rel="stylesheet" href="/survey/assets/css/styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/survey/assets/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/survey/assets/favicon.ico" type="image/x-icon">
    <link href="/survey/assets/fonts/poppins/css/poppins.css" rel="stylesheet" >
    <script src="/survey/assets/js/lucide.min.js"></script>
    <!-- optional PNG for modern browsers -->
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="//survey/assets/images/SchoolLogo-32.png"> -->
</head>
<body>

    <!-- Splash Screen -->
    <div class="splash-screen" id="splashScreen">
        <img src="/survey/assets/images/SchoolLogo.png" alt="School Logo" class="splash-logo">
        <div class="splash-text">Submitting</div>
        <div class="loader-line"></div>
    </div>

    <!-- Background Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <!-- Debug: Clear Student Data Session -->
    <!-- <a href="?unset_student_data=1" style="position:fixed;top:10px;right:10px;z-index:9999;padding:6px 10px;font-size:12px">Clear session</a> -->


    <div class="main-wrapper">

        <div class="card thankyou-card">
            <img src="/survey/assets/images/SchoolLogo.png" alt="School Logo" style="width: 120px; margin-bottom: 12px;">
            <div class="academic-text">
                <span class="univ-name">Nabia University</span>
                <span class="univ-sub">Value academic and passion</span>
            </div>
            
            <?php
                if ($_SESSION['has_submitted_all']) {
                    echo '<h1 style="margin-top: 20px;">Survey Complete!</h1>';
                    echo '<p>You have submitted all targets for this survey.</p>';
                } else {
                    // Set name according to student_data, else Anonymous
                    $name = ((int)($_SESSION['student_data']['is_anonymous'] ?? 0) === 1) ? 'Anonymous' : ($_SESSION['student_data']['given_name'] . ' ' . $_SESSION['student_data']['surname']);
                    echo '<h1 style="margin-top: 20px;">Submission Complete!</h1>';
                    echo '<p><strong>Thanks <i>' . $name . '</i>, Your feedback helps us improve our classroom experience.</strong></p>';
                    unset($_SESSION['has_submitted']);
                    unset($_SESSION['errors']);
                    // debug();
                }
            ?>
            
            <?php if (empty($_SESSION['has_submitted_all'])): ?>
                <div class="button-container">
                    
                    <a class="btn-submit" href="survey.php" style="margin-top: 12px;"><i data-lucide="rotate-ccw" class="lucide-relative"></i>Take Another</a>
                    <a class="btn-back" href="index.php" style="margin-top: 12px;"><i data-lucide="house" class="lucide-relative"></i>Return to Home</a>
                </div>
            <?php else: ?>
                <div class="button-container">
                    <a class="btn-submit" href="index.php" style="margin-top: 12px;"><i data-lucide="house" class="lucide-relative"></i>Return to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
    <script src="/survey/assets/js/school.js"></script>
</body>
</html>