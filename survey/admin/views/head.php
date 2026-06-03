<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="/survey/admin/assets/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/survey/admin/assets/images/favicon.ico" type="image/x-icon">
    <!-- <link rel="stylesheet" href="css/dashboard.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <link rel="stylesheet" href="/survey/admin/assets/css/styles.css">
    <link rel="stylesheet" href="/survey/admin/assets/css/components.css">
    <link rel="stylesheet" href="/survey/admin/assets/css/charts.css">
    <script src="/survey/admin/assets/js/lucide.min.js"></script>
    <script src="/survey/admin/assets/js/chart.umd.min.js"></script>
</head>
<body>

<?php 
// include __DIR__ . '/../src/bootstrap.php';
// splashScreen();


// Show splash screen once only per page
$key = 'splash_shown_' . md5($_SERVER['REQUEST_URI']); // unique per page
if (empty($_SESSION[$key])) {
    // <!-- Background Orbs -->
    echo "<div class=\"bg-orb bg-orb-1\"></div>";
    echo "<div class=\"bg-orb bg-orb-2\"></div>";
    
    // <!-- Splash Screen -->
    echo "<div class=\"splash-screen\">";
    echo "    <img src=\"/survey/admin/assets/images/SchoolLogo.png\" alt=\"School Logo\" class=\"splash-logo\">";
    echo "    <div class=\"splash-text\">Nabia University</div>";
    echo "    <div class=\"loader-line\"></div>";
    echo "</div>";
    $_SESSION[$key] = true;
}

?>

    <!-- Main Wrapper -->
    <div class="admin-layout">