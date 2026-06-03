<!-- <main class="main-wrapper"> -->
<main class="admin-main">
<header class="admin-header">
    <!-- Brand -->
    <a href="/survey/admin/" class="admin-brand">    
        <img src="/survey/admin/assets/images/SchoolLogo.png" alt="School Logo" class="admin-brand-logo">
        <div class="admin-brand-text">
            <div class="admin-brand-name">Nabia</div>
            <div class="admin-brand-sub">Admin Panel</div>
        </div>
    </a>

    <form class="admin-search" method="GET" action="">
        <i data-lucide="search" class="admin-search-icon"></i>
        <input type="submit" style="display: none" />
        <input
            type="text"
            name="search"
            placeholder="Search..."
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
            accesskey="k"
        >
    </form>
            <!-- onkeyup="this.form.submit()" -->

    <!-- Actions -->
    <div style="display: flex; align-items: center; gap: 16px;">
        <div class="notification-icon">
            <!-- <i class="fas fa-bell"></i> -->
                <i data-lucide="bell"></i>
            <a href="?page=notifications">
                <span class="notification-badge">3</span>
            </a>
        </div>

        <div class="admin-dropdown">
            <div class="admin-dropdown-toggle">
                <!-- <img src="/survey/admin/assets/images/avatar.png" alt="Admin" class="admin-avatar"> -->
                <img src="/survey/admin/assets/images/placeholder-pic-businessman.jpg" alt="Admin" class="admin-avatar">
                <span class="admin-name"><?= $_SESSION['admin_data']['first_name'] ?></span>
                <!-- <i class="fas fa-chevron-down" style="font-size: 0.8rem; color: var(--text-muted);"></i> -->
                <i data-lucide="chevron-down" style="font-size: 0.8rem; color: var(--text-muted);"></i>
            </div>
            <div class="admin-dropdown-menu">
                <!-- <a href="#"><i data-lucide="user"></i> Profile</a> -->
                <a href="?action=edit&adminId=<?= $_SESSION['admin_data']['admin_id'] ?>" data-modal-admin-profile><i data-lucide="user" class="lucide-relative"></i> Profile</a>
                <a href="?page=settings"><i data-lucide="cog" class="lucide-relative"></i> Settings</a>
                <a href="logout.php"><i data-lucide="log-out" class="lucide-relative"></i> Logout</a>
            </div>
        </div>

        <button class="burger-menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
