<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/database.php';
require_once __DIR__ . '/src/form_handler.php';

redirectNoRespondentData();
redirectCompleted();
setLanguage();

$db        = new database();
$pdo       = $db->connect();

$targets   = $pdo->query("SELECT target_id, subject, instructor FROM target")->fetchAll();
$questions = $pdo->query("SELECT question_id, category, category_ph, text, text_ph FROM question ORDER BY category, question_id;")->fetchAll();

// debug();

if (isset($_SESSION['lang']) && $_SESSION['lang'] === 'ph') {
    $clang = 'category_ph';
    $qlang = 'text_ph';
} else {
    $clang = 'category';
    $qlang = 'text';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();
    checkAndUpdateExistingStudent($db);

    try {
        $result = processSurvey($pdo, $_POST);
        $_SESSION['has_submitted']       = $result['has_submitted'];
        $_SESSION['submitted_targets'][] = $result['submitted_targets'];

        redirectCompleted();

        header('Location: thank_you.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred. Please try again." . $e;
        header('Location: survey.php');
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Classroom Survey</title>
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
      <div class="splash-text">Loading Survey</div>
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

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="card errors"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>


        <form id="surveyForm" action="<?= e($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">


           <div class="card">
             <nav style="float:right;">
                <a href="?lang=en" style="font-size: 16px; font-weight: normal; margin-left:8px; text-decoration:none;">English</a>
                <span style="margin:0 8px;color:#999;">|</span>
                <a href="?lang=ph" style="font-size: 16px; font-weight: normal; margin-left:0; text-decoration:none;">Filipino</a>
            </nav>

            <h1>Class Selection</h1>
            <div class="form-row">
              <div class="form-group">
                <label for="target">Choose a subject and teacher:</label>
                <select name="target" id="subject" tabindex="1" required>
                  <?php foreach ($targets as $t): ?>

                        <option value="<?= e((int)$t['target_id']) ?>"
                            <?= in_array((int)$t['target_id'], $_SESSION['submitted_targets']) ? ' disabled' : ''; ?>>
                            <?=  e($t['subject']) . ' — ' . e($t['instructor']) ?>
                        </option>

                    <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
 

            <!-- Survey Questions Section -->

             <div class="card">

            <?php

            $currentCategory = '';
            foreach ($questions as $question):
                $qid = 'q' . e($question['question_id']);
                $random = rand(3, 5);

                if ($question[$clang] !== $currentCategory):  // Check if category changed
                    echo '<h2>' . e($question[$clang]) . '</h2>';
                    $currentCategory = $question[$clang];

                endif; ?>

                <fieldset tabindex="1" class="starRating">
                    <p><?php echo e($question[$qlang]); ?></p>

                    <?php for ($i = 5; $i >= 1; $i--): $inputId = $qid . '_' . $i; ?>

                        <!-- qid=group name | i=value | inputId=unique id -->

                        <input
                            type="radio"
                            id       ="<?php echo $inputId; //qid; ?>"
                            name     ="<?php echo $qid; ?>"
                            value    ="<?php echo $i; ?>"
                            required

                            <?= (($_ENV['ANSWER_SURVEY'] ?? null) === '1' && $i === $random) ? 'checked' : '' ?>>
                        <label for="<?php echo $inputId; ?>">★</label>

                        <?php endfor; ?>

                </fieldset>

            <?php endforeach; ?>

            </div>

<!-- 
         <div class="card">
            <h1>Teaching Skills</h1>
            <fieldset class="starRating">
              <p>The teacher explains lessons through clear and understandable teaching methods.</p>
              <input type="radio" id="star5ts1" name="ts1" value="5" required><label for="star5ts1">★</label>
              <input type="radio" id="star4ts1" name="ts1" value="4"><label for="star4ts1">★</label>
              <input type="radio" id="star3ts1" name="ts1" value="3"><label for="star3ts1">★</label>
              <input type="radio" id="star2ts1" name="ts1" value="2"><label for="star2ts1">★</label>
              <input type="radio" id="star1ts1" name="ts1" value="1"><label for="star1ts1">★</label>
            </fieldset>
-->

      <!-- Feedback Card -->
      <div class="card">
        <label for="feedback"><strong>Send suggestion for improvement:</strong></label>
        <textarea id="feedback" name="feedback" placeholder="Write your feedback here..." required></textarea>

        <div class="button-container">
          <a href="student_info.php" class="btn-back">Back</a>
          <button type="submit" class="btn-submit">Submit Survey</button>
        </div>
      </div>

    </form>
  </div>

<script src="/survey/assets/js/school.js"></script>
</body>
</html>