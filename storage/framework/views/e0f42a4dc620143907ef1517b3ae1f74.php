<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Kedah Forward'); ?></title>

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/home.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fonts.css')); ?>">

    <?php echo $__env->yieldPushContent('styles'); ?> 
</head>

<body >

    
    <?php echo $__env->make('components.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div id="overlay"></div>

    
    <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('components.social', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main style="margin-top:70px;">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('components.backtotop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('components.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/layouts/app.blade.php ENDPATH**/ ?>