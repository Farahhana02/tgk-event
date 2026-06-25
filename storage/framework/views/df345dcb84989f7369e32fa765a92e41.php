
<?php $__env->startSection('title', 'Participation Info'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/admin-program-detail.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/admin-participations.css')); ?>">

<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title"><?php echo e(strtoupper($programme->title)); ?></div>

        <div class="breadcrumb-path">
            <a href="<?php echo e(route('admin.index')); ?>">
                <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
            </a>
            <span>/</span>

            <a href="<?php echo e(route('admin.participations.index')); ?>" class="breadcrumb-link">
                PARTICIPATION
            </a>
            <span>/</span>

            <a href="<?php echo e(route('admin.participations.info', $programme->id)); ?>"
   class="breadcrumb-current">
   INFO
</a>
        </div>
    </div>
</div>


<div class="pa-wrap pa-info-page">
    <div class="pa-grid-2">

        <a href="<?php echo e(route('admin.participations.participant_list', $programme->id)); ?>" class="pa-hub-card">
            <div class="pa-hub-title">Participant List</div>
            <div class="pa-hub-desc">
                View participant details.
            </div>
        </a>

        <a href="<?php echo e(route('admin.participations.form', $programme->id)); ?>" class="pa-hub-card">
            <div class="pa-hub-title">Participation Form</div>
            <div class="pa-hub-desc">
                Setup form.
            </div>
        </a>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin-template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/participations/info.blade.php ENDPATH**/ ?>