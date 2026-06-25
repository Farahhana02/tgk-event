
<?php $__env->startSection('title', 'Participation'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/admin-participations.css')); ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">PARTICIPATION</div>
        <div class="breadcrumb-path">
            <a href="<?php echo e(route('admin.index')); ?>">
                <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <span class="breadcrumb-current">PARTICIPATION</span>
        </div>
    </div>
</div>

<div class="pa-wrap">


<div class="pa-topbar">
    <form method="GET" action="<?php echo e(route('admin.participations.index')); ?>" class="pa-searchbar">
        <input
            type="text"
            id="search"
            name="search"
            class="pa-search-input-2"
            placeholder="SEARCH..."
            value="<?php echo e(request('search')); ?>"
        >

<button type="submit" class="pa-search-btn-2" aria-label="Search">
    <img 
        src="<?php echo e(asset('assets/icons/search.png')); ?>" 
        alt="Search"
        class="pa-search-img"
    >
</button>

    </form>
</div>

    
    <div class="pa-table-card">
        <div class="pa-table-scroll">
            <table class="pa-table-2">
                <thead>
                    <tr>
                        <th class="pa-col-bil">BIL</th>
                        <th class="pa-col-programme">PROGRAMME</th>
                        <th class="pa-col-date">START DATE</th>
                        <th class="pa-col-date">END DATE</th>
                        <th class="pa-col-time">TIME</th>
                        <th class="pa-col-venue">VENUE</th>
                        <th class="pa-col-info">INFO</th>
                        <th class="pa-col-action">ACTION</th>
                    </tr>
                </thead>

                <tbody>
<?php $__empty_1 = true; $__currentLoopData = $programmes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        // ✅ FORMAT TIME IN 12-HOUR WITH AM/PM
        $stDisp = '-';
        $etDisp = '';

        if ($p->start_time) {
            try {
                $stDisp = \Carbon\Carbon::parse($p->start_time)->format('h:i A'); // 12-hour with AM/PM
            } catch (\Exception $e) {
                $stDisp = '-';
            }
        }

        if ($p->end_time) {
            try {
                $etDisp = \Carbon\Carbon::parse($p->end_time)->format('h:i A'); // 12-hour with AM/PM
            } catch (\Exception $e) {
                $etDisp = '';
            }
        }

        $timeDisp = $stDisp;
        if ($etDisp) $timeDisp .= ' - ' . $etDisp;
    ?>

    <tr>
        <td><?php echo e($programmes->firstItem() + $i); ?></td>
        <td class="pa-programme-name"><?php echo e(strtoupper($p->title)); ?></td>
        
        
        <td><?php echo e(optional($p->start_date)->format('d/m/Y') ?? '-'); ?></td>
        <td><?php echo e(optional($p->end_date)->format('d/m/Y') ?? '-'); ?></td>
        
        
        <td><?php echo e($timeDisp); ?></td>
        
        <td><?php echo e($p->venue ?? '-'); ?></td>

        <td class="pa-center">
            <a href="<?php echo e(route('admin.participations.info', $p->id)); ?>" class="pa-info-btn" title="Info">i</a>
        </td>

        <td class="pa-center">
            <div class="pa-actions">
                <?php
                    // Extract time properly for edit modal (keep as HH:mm for input type="time")
                    $startTimeVal = '';
                    $endTimeVal = '';
                    
                    if ($p->start_time) {
                        try {
                            $startTimeVal = \Carbon\Carbon::parse($p->start_time)->format('H:i');
                        } catch (\Exception $e) {
                            $startTimeVal = '';
                        }
                    }
                    
                    if ($p->end_time) {
                        try {
                            $endTimeVal = \Carbon\Carbon::parse($p->end_time)->format('H:i');
                        } catch (\Exception $e) {
                            $endTimeVal = '';
                        }
                    }
                ?>
                
                <button type="button" 
                        class="pa-action-btn pa-edit" 
                        onclick="openEditModal(<?php echo e($p->id); ?>, '<?php echo e(addslashes($p->title)); ?>', '<?php echo e(addslashes($p->venue ?? '')); ?>', '<?php echo e(optional($p->start_date)->format('Y-m-d')); ?>', '<?php echo e(optional($p->end_date)->format('Y-m-d')); ?>', '<?php echo e($startTimeVal); ?>', '<?php echo e($endTimeVal); ?>')" 
                        title="Edit">✎</button>

                <button type="button" 
                        class="pa-action-btn pa-delete" 
                        onclick="confirmDelete(<?php echo e($p->id); ?>)" 
                        title="Delete">🗑</button>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="8" class="pa-empty">
            <?php if(request('search')): ?>
                No participation programme found for
                <strong>"<?php echo e(request('search')); ?>"</strong>.
            <?php else: ?>
                No participation programmes available yet.
            <?php endif; ?>
        </td>
    </tr>
<?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="pa-pagination">
        <?php echo e($programmes->links()); ?>

    </div>

    
    <button type="button" class="pa-fab" onclick="openCreateModal()" title="Add Programme">+</button>

</div>


<div id="createModal" class="modal-overlay">
    <div class="modal-container">
        <button type="button" class="modal-close-btn" onclick="closeCreateModal()">×</button>
        
        <h2 class="modal-heading">ADD PROGRAMME</h2>
        
        <form method="POST" action="<?php echo e(route('admin.participations.store')); ?>" id="createForm">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label class="form-label">PROGRAMME TITLE <span class="required">*</span></label>
                <input type="text" name="title" class="form-input" placeholder="ENTER PROGRAMME TITLE" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">START DATE <span class="required">*</span></label>
                    <input type="date" name="start_date" class="form-input" placeholder="mm/dd/yyyy">
                </div>
                <div class="form-group">
                    <label class="form-label">END DATE <span class="required">*</span></label>
                    <input type="date" name="end_date" class="form-input" placeholder="mm/dd/yyyy">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">START TIME <span class="required">*</span></label>
                    <input type="time" name="start_time" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">END TIME <span class="required">*</span></label>
                    <input type="time" name="end_time" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">VENUE <span class="required">*</span></label>
                <input type="text" name="venue" class="form-input" placeholder="ENTER VENUE">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeCreateModal()">CANCEL</button>
                <button type="submit" class="btn-save">SAVE</button>
            </div>
        </form>
    </div>
</div>


<div id="editModal" class="modal-overlay">
    <div class="modal-container">
        <button type="button" class="modal-close-btn" onclick="closeEditModal()">×</button>
        
        <h2 class="modal-heading">EDIT PARTICIPATION PROGRAMME</h2>
        
        <form method="POST" action="" id="editForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="form-group">
                <label class="form-label">PROGRAMME TITLE <span class="required">*</span></label>
                <input type="text" name="title" id="edit_title" class="form-input" placeholder="ENTER PROGRAMME TITLE" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">START DATE</label>
                    <input type="date" name="start_date" id="edit_start_date" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">END DATE</label>
                    <input type="date" name="end_date" id="edit_end_date" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">START TIME</label>
                    <input type="time" name="start_time" id="edit_start_time" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">END TIME</label>
                    <input type="time" name="end_time" id="edit_end_time" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">VENUE</label>
                <input type="text" name="venue" id="edit_venue" class="form-input" placeholder="ENTER VENUE">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">CANCEL</button>
                <button type="submit" class="btn-save">UPDATE</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Show success message - CENTERED
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false,
        position: 'center'  // Changed from 'top-end' to 'center'
    });
<?php endif; ?>

// Create Modal
function openCreateModal() {
    document.getElementById('createModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('createForm').reset();
}

// Edit Modal
function openEditModal(id, title, venue, startDate, endDate, startTime, endTime) {
    console.log('Edit modal data:', {id, title, venue, startDate, endDate, startTime, endTime}); // Debug
    
    document.getElementById('edit_title').value = title || '';
    document.getElementById('edit_venue').value = venue || '';
    document.getElementById('edit_start_date').value = startDate || '';
    document.getElementById('edit_end_date').value = endDate || '';
    document.getElementById('edit_start_time').value = startTime || '';
    document.getElementById('edit_end_time').value = endTime || '';
    
    document.getElementById('editForm').action = '/admin/participations/' + id;
    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Delete Confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete this participation programme?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        position: 'center'  // Centered position
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/participations/' + id;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '<?php echo e(csrf_token()); ?>';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
        closeEditModal();
    }
});
</script>

<?php echo $__env->make('admin.layouts.admin-template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/participations/index.blade.php ENDPATH**/ ?>