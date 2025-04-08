<?php
function sidebar(){
    echo "<div class='sidebar'>";

    echo "<div class='logo'>";
    echo "    <i class='fa-solid fa-table-cells'></i>";
    echo "    <span class='logo-text'>LyricLedger</span>";
    echo "</div>";

    echo "<div class='menu'>";

    echo "    <div class='menu-item active'>";
    echo "        <i class='fa-solid fa-chart-line'></i>";
    echo "        <span>Dashboard</span>";
    echo "    </div>";

    echo "    <div class='menu-item'>";
    echo "        <i class='fa-solid fa-music'></i>";
    echo "        <span>Tracks</span>";
    echo "    </div>";

    echo "    <div class='menu-item'>";
    echo "        <i class='fa-solid fa-file-signature'></i>";
    echo "        <span>Registrations</span>";
    echo "        <div class='badge'>3</div>";
    echo "    </div>";

    echo "    <div class='menu-item'>";
    echo "        <i class='fa-solid fa-user'></i>";
    echo "        <span>Artists</span>";
    echo "    </div>";

    echo "    <div class='menu-item'>";
    echo "        <i class='fa-solid fa-compact-disc'></i>";
    echo "        <span>Albums</span>";
    echo "    </div>";

    echo "    <div class='menu-item'>";
    echo "        <i class='fa-solid fa-building'></i>";
    echo "        <span>Publishers</span>";
    echo "    </div>";

    echo "    <div class='menu-item'>";
    echo "        <i class='fa-solid fa-file-lines'></i>";
    echo "        <span>Reports</span>";
    echo "    </div>";

    echo "</div>";

    echo "<div class='user'>";
    echo "    <div class='user-img'>";
    echo "        <img src='/api/placeholder/36/36' alt='User'>";
    echo "    </div>";
    echo "    <div class='user-info'>";
    echo "        <div class='user-name'>Ann Smith</div>";
    echo "        <div class='user-role'>Administrator</div>";
    echo "    </div>";
    echo "</div>";

    echo "<div class='logout'>";
    echo "    <i class='fa-solid fa-arrow-right-from-bracket'></i>";
    echo "    <span>Log Out</span>";
    echo "</div>";

    echo "</div>";
}
?>