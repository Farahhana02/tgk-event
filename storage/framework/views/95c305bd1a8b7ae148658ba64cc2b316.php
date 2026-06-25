

<?php $__env->startSection('title', $fundraiser->programme_name); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="/assets/css/fundraiser-detail.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Breadcrumb -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">FUNDRAISING</div>

        <div class="breadcrumb-path">
            <a href="/">
                <img src="/assets/icons/Home.png" class="breadcrumb-home-icon">
            </a>
            <span>/</span>

            <a href="/fundraisers" class="breadcrumb-link">Fundraising</a>
            <span>/</span>

            <span class="breadcrumb-current"><?php echo e(strtoupper($fundraiser->programme_name)); ?></span>
        </div>
    </div>
</div>

<!-- Hero Section -->
<div class="hero-section" style="background-image: url('<?php echo e(asset('storage/' . $fundraiser->image_path)); ?>')">
    <div class="hero-overlay">
        <div class="hero-content">
            <div class="hero-text-wrapper">
                <h1 class="hero-title"><?php echo e($fundraiser->programme_name); ?></h1>
            </div>
        </div>
    </div>
</div>

<!-- Detail Section -->
<div class="detail-section">
    <div class="container">
        <div class="detail-grid">

            <!-- LEFT COLUMN -->
            <div class="detail-main">

                <!-- Programme Info Card -->
                <div class="info-card">
                    <h2 class="card-title">Programme Information</h2>

                    <div class="info-row">
                        <span class="info-label">Programme Name</span>
                        <span class="info-value"><?php echo e($fundraiser->programme_name); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Description</span>
                        <p class="info-text" style="white-space: pre-wrap;"><?php echo nl2br(e($fundraiser->description)); ?></p>
                    </div>
                </div>

                <!-- Progress Card -->
                <div class="progress-card">
                    <h2 class="card-title">Donation Progress</h2>

                    <div class="progress-stats">
                        <div class="stat-item">
                            <span class="stat-value">RM <?php echo e(number_format($fundraiser->total_raised, 2)); ?></span>
                            <span class="stat-label">Collected</span>
                        </div>

                        <div class="stat-divider"></div>

                        <div class="stat-item">
                            <span class="stat-value">RM <?php echo e(number_format($fundraiser->target_amount, 2)); ?></span>
                            <span class="stat-label">Target</span>
                        </div>
                    </div>

                    <div class="progress-bar-wrapper">
                        <?php
                            $percent = $fundraiser->target_amount > 0
                                ? min(100, ($fundraiser->total_raised / $fundraiser->target_amount) * 100)
                                : 0;
                        ?>

                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo e($percent); ?>%;">
                                <span class="progress-text"><?php echo e(number_format($percent, 2)); ?>%</span>
                            </div>
                        </div>
                    </div>

                    <p class="progress-message">
                        <?php echo e($percent >= 100 ? 'Target achieved! Thank you for your support.' : 'Your contribution would make a difference'); ?>

                    </p>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="detail-sidebar">

                <!-- DONATE BUTTON CARD (form moved to new page) -->
                <div class="donation-card">
                    <h2 class="donation-title">Support This Programme</h2>
                    <p class="donation-subtitle">Your contribution helps us create meaningful impact.</p>

                    <a href="<?php echo e(route('fundraiser.donate.form', $fundraiser->id)); ?>"
                       class="donate-btn"
                       style="width: 80%; text-align:center;">
                        DONATE NOW
                    </a>
                </div>

                <!-- Supporters Card -->
                <div class="supporters-card">
                    <i class="fas fa-users supporters-icon"></i>
                    <div class="supporters-info">
                        <span class="supporters-count"><?php echo e($fundraiser->donors->count()); ?></span>
                        <span class="supporters-label">Total Donaters</span>
                    </div>
                </div>

            </div>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/fundraiser-detail.blade.php ENDPATH**/ ?>