
<?php $__env->startSection('title', 'Edit Submission'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/admin-participation-submission.css')); ?>">

<div class="container-fluid">
<div class="ps-wrap">

    
    <h1 class="ps-title">Edit Submission</h1>

    
    <a href="<?php echo e(route('admin.participations.participant_list', $submission->programme_id)); ?>"
       class="ps-btn-add"
       style="background:#6B7280; margin:10px 0 20px; display:inline-block;">
        ← Back to Participant List
    </a>

    
    <div class="ps-info">
        <p><strong>Programme:</strong> <?php echo e($submission->programme->title); ?></p>
        <p><strong>Company / Agency:</strong> <?php echo e($submission->company_name); ?></p>
        <p><strong>Package:</strong> <?php echo e(optional($submission->package)->name ?? '-'); ?></p>

        
        <form method="POST"
              action="<?php echo e(route('admin.submissions.status.update', $submission->id)); ?>"
              style="margin-top:12px;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <label style="font-weight:600;">Status</label>

            <div style="display:flex; gap:10px; align-items:center;">
                <select name="status" class="form-control" style="max-width:220px;">
                    <option value="pending"
                        <?php echo e($submission->status === 'pending' ? 'selected' : ''); ?>>
                        Pending
                    </option>

                    <option value="approved"
                        <?php echo e($submission->status === 'approved' ? 'selected' : ''); ?>>
                        Approved
                    </option>
                </select>

                <button type="submit" class="ps-btn-add">
                    Update Status
                </button>
            </div>
        </form>
    </div>

    
    <div class="ps-section-header">
        <h4>Participants</h4>

        
        <?php if($submission->status !== 'approved'): ?>
            <button type="button" class="ps-btn-add" onclick="addRow()">+ Add Participant</button>
        <?php endif; ?>
    </div>

    
    <form method="POST"
          action="<?php echo e(route('admin.submissions.participants.update', $submission->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <table class="ps-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Table No</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody id="participant-table">
                <?php $__currentLoopData = $submission->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i + 1); ?></td>

                    
                    <td>
                        <input type="hidden"
                            name="participants[<?php echo e($i); ?>][id]"
                            value="<?php echo e($p->id); ?>">

                        <input type="text"
                            name="participants[<?php echo e($i); ?>][name]"
                            value="<?php echo e($p->name); ?>"
                            <?php echo e($submission->status === 'approved' ? 'readonly' : ''); ?>

                            required>
                    </td>

                    
                    <td>
                        <input type="text"
                            name="participants[<?php echo e($i); ?>][position]"
                            value="<?php echo e($p->position); ?>"
                            <?php echo e($submission->status === 'approved' ? 'readonly' : ''); ?>

                            placeholder="e.g. Officer">
                    </td>

                    
                    <td>
                        <input type="text"
                            name="participants[<?php echo e($i); ?>][table_number]"
                            value="<?php echo e($p->table_number); ?>"
                            placeholder="-"
                            <?php echo e($submission->status === 'approved' ? 'readonly' : ''); ?>

                            style="width:90px; text-align:center;">
                    </td>

                    
                    <td>
                        <?php if($submission->status !== 'approved'): ?>
                            <button type="button"
                                    class="ps-btn-delete"
                                    onclick="deleteParticipant(<?php echo e($p->id); ?>, this)">
                                Delete
                            </button>
                        <?php else: ?>
                            <span style="color:#9CA3AF;">Locked</span>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        <?php if($submission->status !== 'approved'): ?>
            <div style="margin-top:16px;">
                <button type="submit" class="ps-btn-add">
                    Save Changes
                </button>
            </div>
        <?php endif; ?>
    </form>

</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* =====================================================
   RE-INDEX TABLE
===================================================== */
function reindexTable() {
    document.querySelectorAll('#participant-table tr').forEach((row, index) => {
        row.querySelector('td:first-child').innerText = index + 1;

        row.querySelectorAll('input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
        });
    });
}

/* =====================================================
   ADD ROW
===================================================== */
function addRow() {
    const table = document.getElementById('participant-table');
    const index = table.querySelectorAll('tr').length;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${index + 1}</td>
        <td><input type="text" name="participants[${index}][name]" required></td>
        <td><input type="text" name="participants[${index}][position]" placeholder="e.g. Officer"></td>
        <td>
            <button type="button" class="ps-btn-delete" onclick="removeRow(this)">
                Delete
            </button>
        </td>
    `;
    table.appendChild(row);
}

/* =====================================================
   REMOVE UNSAVED ROW
===================================================== */
function removeRow(btn) {
    btn.closest('tr').remove();
    reindexTable();
}

/* =====================================================
   DELETE PARTICIPANT (AJAX + SWEETALERT)
===================================================== */
function deleteParticipant(id, btn) {

    Swal.fire({
        title: 'Delete participant?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete'
    }).then((result) => {

        if (!result.isConfirmed) return;

        fetch(`/admin/participants/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                btn.closest('tr').remove();
                reindexTable();

                Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });
    });
}
</script>



<?php if(session('success')): ?>
<script>
    const msg = "<?php echo e(session('success')); ?>";

    let icon = 'success';
    let title = 'Success';
    let color = '#00542A';

    // 🔥 Detect pending status
    if (msg.toLowerCase().includes('pending')) {
        icon = 'warning';
        title = 'Pending';
        color = '#C08329';
    }

    Swal.fire({
        icon: icon,
        title: title,
        text: msg,
        confirmButtonColor: color,
        timer: 2000,
        showConfirmButton: false
    });
</script>
<?php endif; ?>

<?php if(session('info')): ?>
<script>
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: "<?php echo e(session('info')); ?>",
        confirmButtonColor: '#6B7280'
    });
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin-template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/participations/submission-edit.blade.php ENDPATH**/ ?>