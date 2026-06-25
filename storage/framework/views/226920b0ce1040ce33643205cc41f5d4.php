
<?php $__env->startSection('title', 'Participant List'); ?>

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
            <a href="<?php echo e(route('admin.participations.index')); ?>" class="breadcrumb-link">PARTICIPATION</a>
            <span>/</span>
            <a href="<?php echo e(route('admin.participations.info', $programme->id)); ?>" class="breadcrumb-link">INFO</a>
            <span>/</span>
            <span class="breadcrumb-current">PARTICIPANT LIST</span>
        </div>
    </div>
</div>

<div class="pa-wrap">


<div class="pa-topbar pa-participant-page">
    <div class="pa-topbar-left">
        <a href="<?php echo e(route('admin.participations.export.print', $programme->id)); ?>?<?php echo e(request()->getQueryString()); ?>"
           target="_blank"
           class="pa-btn pa-btn-green">
            PRINT
        </a>

        <a href="<?php echo e(route('admin.participations.export.excel', $programme->id)); ?>?<?php echo e(request()->getQueryString()); ?>"
           class="pa-btn pa-btn-gold">
            EXCEL
        </a>

        <form id="filterForm"
              method="GET"
              action="<?php echo e(route('admin.participations.participant_list', $programme->id)); ?>"
              class="pa-filter-group">

            <select name="status" class="pa-filter" onchange="this.form.submit()">
                <option value="">ALL STATUS</option>
                <option value="pending" <?php echo e(request('status')=='pending' ? 'selected' : ''); ?>>PENDING</option>
                <option value="approved" <?php echo e(request('status')=='approved' ? 'selected' : ''); ?>>APPROVED</option>
            </select>

            <select name="agency" class="pa-filter" onchange="this.form.submit()">
                <option value="">ALL AGENCY</option>
                <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($agency); ?>" <?php echo e(request('agency')==$agency ? 'selected' : ''); ?>>
                        <?php echo e($agency); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">
        </form>
    </div>

    <form method="GET"
          action="<?php echo e(route('admin.participations.participant_list', $programme->id)); ?>"
          class="pa-searchbar">
        <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
        <input type="hidden" name="agency" value="<?php echo e(request('agency')); ?>">
        <input type="text"
               name="q"
               value="<?php echo e(request('q')); ?>"
               placeholder="SEARCH..."
               class="pa-search-input-2">
        <button type="submit" class="pa-search-btn-2"></button>
    </form>
</div>


<div class="pa-table-card">
    <div class="pa-table-scroll">
        <table class="pa-table-2">
            <thead>
                <tr>
                    <th rowspan="2">BIL</th>
                    <th rowspan="2">COMPANY</th>
                    <th rowspan="2">OFFICER</th>
                    <th rowspan="2">PHONE</th>
                    <th rowspan="2">PACKAGE</th>
                    <th rowspan="2">QTY</th>
                    <th rowspan="2">TOTAL</th>
                    <th colspan="3" class="pa-center">PARTICIPANTS</th>
                    <th rowspan="2">RECEIPT</th>
                    <th rowspan="2">FORM</th>
                    <th rowspan="2">STATUS</th>
                    <th rowspan="2" class="pa-center">ACTION</th>
                </tr>
                <tr>
                    <th class="pa-col-name">NAME</th>
                    <th class="pa-col-position">POSITION</th>
                    <th class="pa-col-table">TABLE NO</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($submissions->firstItem() + $i); ?></td>
                        <td><?php echo e($s->company_name); ?></td>
                        <td><?php echo e($s->officer_name); ?></td>
                        <td><?php echo e($s->phone_number); ?></td>
                        <td><?php echo e(optional(optional($s->programmePackage)->package)->name ?? '-'); ?></td>
                        <td class="js-qty"><?php echo e($s->quantity ?: '-'); ?></td>
                        <td class="js-total"><?php echo e($s->total_price > 0 ? 'RM ' . number_format($s->total_price, 2) : '-'); ?></td>
                        <td class="pa-col-name">
                            <?php $__currentLoopData = $s->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="pa-participant-row"><?php echo e($p->name); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>

                        <td class="pa-col-position">
                            <?php $__currentLoopData = $s->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="pa-participant-row"><?php echo e($p->position); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>

                        <td class="pa-col-table">
                            <?php $__currentLoopData = $s->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="pa-participant-row">
                                    <?php echo e($p->table_number ?? '-'); ?>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td class="pa-center">
                            <?php if($s->receipt_path): ?>
                                <a href="<?php echo e(asset('storage/'.$s->receipt_path)); ?>" target="_blank">VIEW</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="pa-center">
                        <?php if($s->supporting_document_path): ?>
                            <a href="<?php echo e(asset('storage/'.$s->supporting_document_path)); ?>"
                            target="_blank"
                            class="pa-form-view-btn">
                                VIEW
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>

                        <td>
                            <span class="pa-status-badge pa-status-<?php echo e($s->status); ?>">
                                <?php echo e(strtoupper($s->status)); ?>

                            </span>
                        </td>
                        <td class="pa-center">
                            <div class="pa-actions">
                                <a href="<?php echo e(route('admin.submissions.edit', $s->id)); ?>"
                                   class="pa-action-btn pa-edit">✎</a>

                                <form method="POST"
                                      action="<?php echo e(route('admin.submissions.delete', $s->id)); ?>"
                                      onsubmit="return confirm('Delete this submission?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="pa-action-btn pa-delete">×</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="13" class="pa-empty">No submissions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="pa-pagination">
    <?php echo e($submissions->links()); ?>

</div>

</div>
<style>
    .pa-participant-row {
        padding: 6px 0;
        border-bottom: 1px dashed #e5e7eb;
        line-height: 1.4;
    }

    .pa-participant-row:last-child {
        border-bottom: none;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.admin-template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/participations/participant-list.blade.php ENDPATH**/ ?>