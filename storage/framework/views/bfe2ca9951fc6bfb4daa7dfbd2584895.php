<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TGK-E Admin</title>

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/admin.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fonts.css')); ?>">
</head>

<body>

<!-- TOPBAR -->
<header class="topbar">
    <div class="left-group">
        <button id="toggleSidebar" class="menu-btn">
            <span></span><span></span><span></span>
        </button>

        <div class="logo">
            <img src="<?php echo e(asset('assets/images/tgk.png')); ?>">
            <span class="logo-name">TGK EVENTS</span>
        </div>
    </div>
</header>

<!-- Overlay -->
<div id="overlay"></div>

<aside id="sidebar" class="sidebar">

    <!-- TOP MENU -->
    <div class="sidebar-top">
        <ul class="sidebar-menu">
            <li><a href="<?php echo e(route('admin.index')); ?>" class="active">Home</a></li>
            <li><a href="<?php echo e(route('admin.fundraisers')); ?>">Fundraiser</a></li>
            <li><a href="<?php echo e(route('admin.programs.index')); ?>">Programmes</a></li>
            <li><a href="<?php echo e(route('admin.participations.index')); ?>">Participation</a></li>
        </ul>
    </div>

    <!-- LOGOUT BUTTON – MUST BE INSIDE SIDEBAR -->
    <div class="sidebar-bottom">
        <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="logout-btn">
                <img src="<?php echo e(asset('assets/icons/logout.png')); ?>" alt="Logout" class="logout-icon">
                Logout
            </button>
        </form>
    </div>
</aside>


<!-- =========================
     ADMIN BREADCRUMB HEADER
========================== -->
<div class="breadcrumb-wrapper admin-crumb">
    <div class="breadcrumb-inner">

        <div class="breadcrumb-title">HOME</div>

        <div class="breadcrumb-path">
            <span class="breadcrumb-current">WELCOME ADMIN</span>
        </div>

    </div>
</div>

<!-- =========================
     CENTER KEDAH FORWARD
========================== -->
<div class="admin-center-container">
    <img src="<?php echo e(asset('assets/images/tgk.png')); ?>" class="admin-watermark">
    <h1 class="admin-center-title">THE GREATER KEDAH<br>EVENTS</h1>
</div>

<!-- FOOTER -->
<div class="footer-bottom admin-footer">
    © All Rights Reserved TGK EVENTS
</div>
<button id="backToTop" class="back-to-top">
    <svg viewBox="0 0 24 24">
        <path d="M12 19V5M5 12l7-7 7 7" stroke="white" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<script>
/* Sidebar JS same as home */
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const toggleBtn = document.getElementById("toggleSidebar");

function openSidebar() {
    sidebar.classList.add("open");
    overlay.classList.add("show");
}
function closeSidebar() {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
}

toggleBtn.addEventListener("click", () => {
    sidebar.classList.contains("open") ? closeSidebar() : openSidebar();
});
overlay.addEventListener("click", closeSidebar);
</script>

</body>
</html><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/admin-index.blade.php ENDPATH**/ ?>