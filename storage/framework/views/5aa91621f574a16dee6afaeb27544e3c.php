
<?php $__env->startSection('title', 'Submission Success'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/public-participation.css')); ?>">

<div class="pp-wrap">
    <div class="pp-card" style="text-align:center;">
        <h2 style="margin:0 0 10px 0; font-weight:900; color:#111827;">Submission Successful</h2>

        <div class="pp-note" style="font-size:14px;">
            YOUR SUBMISSION HAS BEEN SUCCESSFULLY RECORDED.<br>
            SECRETARIAT WILL BE IN TOUCH SOON.
        </div>

        <div class="pp-actions" style="justify-content:center; margin-top:18px;">
            <a class="pp-btn pp-btn-primary" href="<?php echo e(route('participation.public.form', $programme->public_token)); ?>">
                NEW SUBMISSION
            </a>

            <a class="pp-btn pp-btn-outline" href="/">
                HOME
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/participations/success.blade.php ENDPATH**/ ?>