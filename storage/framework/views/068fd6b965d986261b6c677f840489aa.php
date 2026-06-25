<?php
    $visiblePrograms = App\Models\Program::where('is_visible', true)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
?>

<footer class="footer">

    <div class="footer-col">
        <h4>Kedah State Executive Council, Industry & Investment, Higher Education and Science, Technology & Innovation</h4>
        <p>Address:<br>
           Aras 5 (Zon A), Blok E, Wisma Darul Aman,<br>
           05503 Alor Setar, Kedah Darul Aman</p>
        <p>Phone:<br>
        En. Mu'ti Invest Kedah - 012-4678587<br>
        <br>En. Iqbal Pejabat Exco - 017-4101239<br> </p>
        <p>Email:<br>
        majlisapresiasi@kedah.gov.my<br> </p>
    </div>

    <?php if($visiblePrograms->count() > 0): ?>
    <div class="footer-col" style = " text-transform:uppercase;">
        <h4>Programmes</h4>
        <?php $__currentLoopData = $visiblePrograms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <p>
            <a href="<?php echo e(route('programs.show', $program->id)); ?>">
                <?php echo e($program->title); ?>

            </a>
        </p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="footer-col">
        <h4>
            <a href="<?php echo e(url('/fundraisers')); ?>" class="fundraiser-link" style = " text-transform:uppercase;">Fundraising</a>
        </h4>
    </div>

    <div class="footer-logo">
        <img src="<?php echo e(asset('assets/images/tgk-footer.png')); ?>">
    </div>
</footer>

<div class="footer-bottom">
    © All Rights Reserved TGK EVENTS
</div><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/components/footer.blade.php ENDPATH**/ ?>