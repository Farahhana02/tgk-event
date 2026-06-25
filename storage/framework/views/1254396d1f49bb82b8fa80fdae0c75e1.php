

<?php $__env->startSection('title', 'Fundraising'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="/assets/css/fundraisers.css">

<!-- Breadcrumb -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">FUNDRAISING</div>
        <div class="breadcrumb-path">
            <a href="/">
                <img src="/assets/icons/Home.png" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <span class="breadcrumb-current">FUNDRAISING</span>
        </div>
    </div>
</div>

<div class="hero-section">
    <div class="hero-overlay">
        <div class="hero-content">

            <div class="hero-text-wrapper">
                <h1 class="hero-title">Fundraising</h1>
                <p class="hero-subtitle">
                    Turning collective support into long-term impact for Kedah's next generation.<br>
                    #EmpoweringLives
                </p>
            </div>

        </div>
    </div>
</div>

<!-- Active Programme Section -->
<div class="programme-section">
    <div class="container">
        <h2 class="section-title">Active Programme</h2>
        
        <div class="programme-grid">
            
            <?php $__empty_1 = true; $__currentLoopData = $fundraisers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fundraiser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <!-- Programme Card -->
                <a href="<?php echo e(route('fundraiser.detail', $fundraiser->id)); ?>" class="programme-card">
                    <div class="programme-image">
                        <?php if($fundraiser->image_path): ?>
                            <img src="<?php echo e(asset('storage/' . $fundraiser->image_path)); ?>" 
                                 alt="<?php echo e($fundraiser->programme_name); ?>"
                                 onerror="this.onerror=null; this.parentElement.classList.add('no-image');">
                        <?php else: ?>
                            <div class="no-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="programme-info">
                        <h3 class="programme-name"><?php echo e(strtoupper($fundraiser->programme_name)); ?></h3>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="no-programmes">
                    <p>No active fundraising at the moment. Please check back later!</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Back to Top Button -->
<div class="back-to-top">
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="back-to-top-btn">
        <i class="fas fa-chevron-up"></i>
    </button>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/fundraisers.blade.php ENDPATH**/ ?>