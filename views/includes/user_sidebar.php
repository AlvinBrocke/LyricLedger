<?php
function userSidebar() {
    // Get current page for active state
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="logo">
        <i class="fa-solid fa-table-cells"></i>
        <span class="logo-text">LyricLedger</span>
    </div>

    <div class="menu">
        <a href="/LyricLedger/views/dashboard/user/home.php" class="menu-item <?= $current_page === 'user.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a href="/LyricLedger/views/upload.php" class="menu-item <?= $current_page === 'upload.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-upload"></i>
            <span>Upload Music</span>
        </a>

        <a href="/LyricLedger/views/my-tracks.php" class="menu-item <?= $current_page === 'my-tracks.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-music"></i>
            <span>My Tracks</span>
        </a>

        <a href="/LyricLedger/views/royalties.php" class="menu-item <?= $current_page === 'royalties.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-file-signature"></i>
            <span>My Royalties</span>
        </a>

        <a href="/LyricLedger/views/profile.php" class="menu-item <?= $current_page === 'profile.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Profile</span>
        </a>
    </div>

    <div class="user">
        <div class="user-img">
            <img src="/LyricLedger/assets/media/images/default-avatar.png" alt="User">
        </div>
        <div class="user-info">
            <div class="user-name"><?= $_SESSION['full_name'] ?? 'Guest' ?></div>
            <div class="user-role"><?= ucfirst($_SESSION['role'] ?? 'User') ?></div>
        </div>
    </div>

    <form action="/LyricLedger/auth/logout.php" method="POST">
        <button type="submit" class="logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Log Out</span>
        </button>
    </form>
</div>
<?php
}
?> 