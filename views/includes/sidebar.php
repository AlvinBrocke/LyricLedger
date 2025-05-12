<?php
function sidebar() {
    // Get current page for active state
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="logo">
        <i class="fa-solid fa-table-cells"></i>
        <span class="logo-text">LyricLedger</span>
    </div>

    <div class="menu">
        <a href="../admin/home.php" class="menu-item <?= $current_page === 'home.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a href="../admin/content.php" class="menu-item <?= $current_page === 'content.php' ? 'active' : '' ?>">
           <i class="fa-solid fa-music"></i>
            <span>Contents</span>
        </a>

        <a href="../admin/royalties.php" class="menu-item <?= $current_page === 'royalties.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-file-signature"></i>
            <span>Royalties</span>
            <div class="badge">3</div>
        </a>

        <a href="../admin/users.php" class="menu-item <?= $current_page === 'users.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Users</span>
        </a>

        <a href="../admin/violations.php" class="menu-item <?= $current_page === 'violations.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-compact-disc"></i>
            <span>Violations</span>
        </a>
    </div>

    <div class="sidebar-bottom">
        <div class="user">
            <div class="user-img">
                <img src="../../../assets/media/images/1b2e314e767a957a44ed8f992c6d9098.jpg" alt="User">
            </div>
            <div class="user-info">
                <div class="user-name"><?= $_SESSION['full_name'] ?? 'Guest' ?></div>
                <div class="user-role">Administrator</div>
            </div>
        </div>

        <form action="../../auth/logout.php" method="POST">
            <button type="submit" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</div>
<?php
}
?>
