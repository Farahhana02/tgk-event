
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'TGK Events Admin'); ?></title>

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/admin.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fonts.css')); ?>">
    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(asset('assets/icons/favicon.ico')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/icons/favicon.ico')); ?>" type="image/x-icon">
    
    <!-- If you don't have a favicon yet, create a simple one -->
    <?php if(!file_exists(public_path('assets/icons/favicon.ico'))): ?>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💰</text></svg>">
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>

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

<div id="overlay"></div>

<aside id="sidebar" class="sidebar">

    <div class="sidebar-top">
        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo e(route('admin.index')); ?>" 
                   class="<?php echo e(request()->routeIs('admin.index') ? 'active' : ''); ?>">
                    Home
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.fundraisers')); ?>" 
                   class="<?php echo e(request()->routeIs('admin.fundraisers*') ? 'active' : ''); ?>">
                    Fundraising
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.programs.index')); ?>" 
                   class="<?php echo e(request()->routeIs('admin.programs*') ? 'active' : ''); ?>">
                    Programmes
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.participations.index')); ?>" 
                   class="<?php echo e(request()->routeIs('admin.participations*') ? 'active' : ''); ?>">
                    Participation
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-bottom">
        <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="logout-btn">
                <img src="<?php echo e(asset('assets/icons/logout.png')); ?>" class="logout-icon">
                Logout
            </button>
        </form>
    </div>

</aside>

<div class="breadcrumb-wrapper admin-crumb">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title"><?php echo $__env->yieldContent('page_title'); ?></div>
        <div class="breadcrumb-path">
            <span class="breadcrumb-current"><?php echo $__env->yieldContent('breadcrumb'); ?></span>
        </div>
    </div>
</div>

<div class="page-container">

<?php if(session('success')): ?>
    <meta name="success-message" content="<?php echo e(session('success')); ?>">
<?php endif; ?>
<?php if(session('error')): ?>
    <meta name="error-message" content="<?php echo e(session('error')); ?>">
<?php endif; ?>
<?php if(session('info')): ?>
    <meta name="info-message" content="<?php echo e(session('info')); ?>">
<?php endif; ?>


    <?php echo $__env->yieldContent('content'); ?>
</div>

<div class="footer-bottom admin-footer">
    © All Rights Reserved TGK EVENTS
</div>

<button id="backToTop" class="back-to-top">
    <svg viewBox="0 0 24 24">
        <path d="M12 19V5M5 12l7-7 7 7" stroke="white" stroke-width="3" fill="none"
              stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<script>
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

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/layouts/admin-template.blade.php ENDPATH**/ ?>