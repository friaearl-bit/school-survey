<?php

// For pages of different files
// function is_active($p) { return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) === basename($p) ? 'active' : ''; }

function is_active($identifier, $activeClass = 'active') {
    $id = ltrim($identifier, '?');

    // 1) If identifier contains '=' treat as key=value
    if (strpos($id, '=') !== false) {
        parse_str($id, $pairs);
        foreach ($pairs as $k => $v) {
            if (isset($_GET[$k]) && ($_GET[$k] == $v || $v === '')) {
                return $activeClass;
            }
        }
        return '';
    }

    // 2) If identifier matches a GET key (e.g., "?tables" -> "tables")
    if (isset($_GET[$id])) { return $activeClass; }

    // 3) treat index.php as active only when no query params at all
    if ($id === 'index.php' && empty($_GET)) { return $activeClass; }

    return '';
}
?>


<aside class="admin-sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <!-- <img src="SchoolLogo.png" alt="Nabia University" class="sidebar-logo-img"> -->
        <img src="/survey/admin/assets/images/SchoolLogo.png" alt="School Logo" class="sidebar-logo-img">
        <span class="sidebar-logo-text">Nabia Portal</span>
    </div>

    <!-- Profile -->
    <div class="sidebar-profile">
        <!-- <img src="admin-avatar.jpg" alt="Admin" class="sidebar-avatar"> -->
        <img src="/survey/admin/assets/images/placeholder-pic-businessman.jpg" alt="Admin" class="sidebar-avatar">
        <div class="sidebar-profile-info">
            <span class="sidebar-profile-name"><?php echo $_SESSION['admin_data']['first_name'] . " " . $_SESSION['admin_data']['last_name']; ?></span>
            <span class="sidebar-profile-role"><?= ($_SESSION['admin_data']['role'] === 'superadmin') ? 'Super Admin' : 'Admin' ?></span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="index.php" class="<?= is_active('index.php'); ?>">
                    <i data-lucide="home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
<!-- 
            <li>
                <a href="#" class="<?php // echo is_active('dashboard-surveys.php'); ?>">
                    <i data-lucide="file-text"></i>
                    <span>Surveys</span>
                </a>
            </li>
 -->
            <li>
                <a href="?page=tables" class="<?= is_active('?page=tables'); ?>">
                <!-- <a href="/admin/dashboard-tables" class="sidebar-nav-link"> -->
                    <i data-lucide="table"></i>
                    <span>Tables</span>
                </a>
            </li>
            <li>
                <a href="?page=responses" class="<?= is_active('?page=responses'); ?>">
                    <i data-lucide="clipboard-list"></i>
                    <span>Responses</span>
                </a>
            </li>
            <li>
                <a href="?page=students" class="<?= is_active('?page=students'); ?>">
                    <i data-lucide="users"></i>
                    <span>Students</span>
                </a>
            </li>
            <li>
                <a href="?page=sections" class="<?= is_active('?page=sections'); ?>">
                    <i data-lucide="layout-template"></i>
                    <span>Sections</span>
                </a>
            </li>
<!-- 
            <li>
                <a href="#" class="<?php // echo is_active('dashboard-targets.php'); ?>">
                    <i data-lucide="target"></i>
                    <span>Targets</span>
                </a>
            </li>
 -->
            <li>
                <a href="?page=feedbacks" class="<?= is_active('?page=feedbacks'); ?>">
                    <i data-lucide="message-square-text"></i>
                    <span>Feedbacks</span>
                </a>
            </li>
<!-- 
            <li>
                <a href="#" class="<?php // echo is_active('dashboard-analytics.php'); ?>">
                    <i data-lucide="bar-chart-3"></i>
                    <span>Analytics</span>
                </a>
            </li>
 -->
            <li>
                <a href="?page=deleted" class="<?= is_active('?page=deleted'); ?>">
                    <i data-lucide="trash-2"></i>
                    <span>Deleted</span>
                </a>
            </li>
            <li>
                <a href="?page=settings" class="<?= is_active('?page=highest_rating_question'); ?>">
                    <i data-lucide="settings"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>