
<?php $__env->startSection('title', 'Programmes'); ?>
<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="/assets/css/admin-fundraiser-index.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- ====================== BREADCRUMB ======================= -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">PROGRAMMES</div>
        <div class="breadcrumb-path">
            <a href="<?php echo e(route('admin.index')); ?>">
                <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <span class="breadcrumb-current">PROGRAMMES</span>
        </div>
    </div>
</div>

<!-- ====================== PAGE CONTENT ======================= -->
<div class="fundraiser-page">
    <!-- SEARCH BAR -->
    <div class="search-area">
        <form method="GET" action="<?php echo e(route('admin.programs.index')); ?>" class="search-area">
    <input
        type="text"
        name="search"
        value="<?php echo e($search ?? ''); ?>"
        placeholder="SEARCH..."
        class="search-input"
        aria-label="Search programme"
    >
    <button type="submit" class="search-btn">
        <img src="/assets/icons/Search.png" class="search-icon">
    </button>
</form>
    </div>

    <!-- TABLE WRAPPER -->
    <div class="fundraiser-table-wrapper">
        <table class="fundraiser-table">
            <thead>
                <tr>
                    <th>BIL</th>
                    <th>PROGRAMME</th>
                    <th>DATE</th>
                    <th>TIME</th>
                    <th>VENUE</th>
                    <th>THEME</th>
                    <th>DISPLAY</th>
                    <th>INFO</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="programsTableBody">
                <?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr id="row-<?php echo e($program->id); ?>">
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e(strtoupper($program->title)); ?></td>
                    <td><?php echo e($program->event_date ? \Carbon\Carbon::parse($program->event_date)->format('d/m/Y') : 'XXX'); ?></td>
                    <td><?php echo e($program->event_time ? \Carbon\Carbon::parse($program->event_time)->format('h:i A') : 'XXX'); ?></td>
                    <td><?php echo e(strtoupper($program->location ?? 'XXX')); ?></td>
                    <td><?php echo e(strtoupper($program->theme ?? 'XXX')); ?></td>
                    <td>
                        <label class="toggle-switch">
                            <input type="checkbox" 
                                   onchange="toggleDisplay(<?php echo e($program->id); ?>)" 
                                   <?php echo e($program->is_visible ? 'checked' : ''); ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" class="icon-btn info-btn" title="View Full Details">
                            <img src="/assets/icons/info.png" alt="Info">
                        </a>
                    </td>
                    <td class="action-buttons">
                        <button class="icon-btn edit-btn" onclick="openEditModal(<?php echo e($program->id); ?>)" title="Edit Basic Info">
                            <img src="/assets/icons/update.png" alt="Edit">
                        </button>
                        <button class="icon-btn delete-btn" onclick="confirmDelete(<?php echo e($program->id); ?>, '<?php echo e(addslashes($program->title)); ?>')" title="Delete">
                            <img src="/assets/icons/delete.png" alt="Delete">
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px;">No programmes found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<!-- PAGINATION -->
<div class="pa-pagination">
    <?php echo e($programs->links()); ?>

</div>

    <!-- ADD BUTTON -->
    <button class="add-button" onclick="openAddModal()" title="Add Programme">+</button>
</div>

<!-- ====================== ADD MODAL ======================= -->
<div id="addModal" class="modal-overlay" style="display: none;">
    <div class="modal-box modal-large">
        <button class="modal-close-btn" onclick="closeAddModal()" title="Close">&times;</button>
        <h2 class="modal-title">ADD PROGRAMME</h2>
        <div class="modal-content">
            <form id="addForm" onsubmit="handleAddSubmit(event)">
                <?php echo csrf_field(); ?>
                <div class="form-row">
                    <div class="form-group">
                        <label>PROGRAMME TITLE <span style="color: red;">*</span></label>
                        <input type="text" 
                               name="title" 
                               class="modal-input" 
                               placeholder="Enter programme title" 
                               required 
                               style="text-transform: uppercase;"
                               maxlength="255">
                    </div>
                </div>

                <div class="form-row two-columns">
                    <div class="form-group">
                        <label>DATE <span style="color: red;">*</span></label>
                        <input type="text" 
                               name="event_date" 
                               class="modal-input date-picker" 
                               placeholder="DD/MM/YYYY"
                               required>
                    </div>
                    <div class="form-group">
                        <label>TIME <span style="color: red;">*</span></label>
                        <input type="time" 
                               name="event_time" 
                               class="modal-input"
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>VENUE <span style="color: red;">*</span></label>
                        <input type="text" 
                               name="location" 
                               class="modal-input" 
                               placeholder="Enter venue"
                               required
                               maxlength="255">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>THEME <span style="color: red;">*</span></label>
                        <input type="text" 
                               name="theme" 
                               class="modal-input" 
                               placeholder="Enter programme theme"
                               required
                               maxlength="255">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeAddModal()">CANCEL</button>
            <button type="submit" class="save-btn" form="addForm">SAVE</button>
        </div>
    </div>
</div>

<!-- ====================== EDIT MODAL ======================= -->
<div id="editModal" class="modal-overlay" style="display: none;">
    <div class="modal-box modal-large">
        <button class="modal-close-btn" onclick="closeEditModal()" title="Close">&times;</button>
        <h2 class="modal-title">EDIT PROGRAMME</h2>
        <div class="modal-content">
            <form id="editForm" onsubmit="handleEditSubmit(event)">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editProgramId" name="program_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>PROGRAMME TITLE <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="editTitle"
                               name="title" 
                               class="modal-input" 
                               placeholder="Enter programme title" 
                               required 
                               style="text-transform: uppercase;"
                               maxlength="255">
                    </div>
                </div>

                <div class="form-row two-columns">
                    <div class="form-group">
                        <label>DATE <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="editDate"
                               name="event_date" 
                               class="modal-input date-picker-edit" 
                               placeholder="DD/MM/YYYY"
                               required>
                    </div>
                    <div class="form-group">
                        <label>TIME <span style="color: red;">*</span></label>
                        <input type="time" 
                               id="editTime"
                               name="event_time" 
                               class="modal-input"
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>VENUE <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="editLocation"
                               name="location" 
                               class="modal-input" 
                               placeholder="Enter venue location"
                               required
                               maxlength="255">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>THEME <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="editTheme"
                               name="theme" 
                               class="modal-input" 
                               placeholder="Enter programme theme"
                               required
                               maxlength="255">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeEditModal()">CANCEL</button>
            <button type="submit" class="save-btn" form="editForm">UPDATE</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Initialize datepickers
document.addEventListener('DOMContentLoaded', function() {
    flatpickr('.date-picker', {
        dateFormat: 'd/m/Y',
        allowInput: true
    });
});


// Open add modal
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
    document.getElementById('addForm').reset();
    
    // Reinitialize datepicker for add modal
    setTimeout(() => {
        flatpickr('.date-picker', {
            dateFormat: 'd/m/Y',
            allowInput: true
        });
    }, 100);
}

// Close add modal
function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('addForm').reset();
}

// Add form submission with validation
function handleAddSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    // Validate all required fields
    const title = formData.get('title').trim();
    const date = formData.get('event_date').trim();
    const time = formData.get('event_time').trim();
    const location = formData.get('location').trim();
    const theme = formData.get('theme').trim();
    
    if (!title || !date || !time || !location || !theme) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Fields',
            text: 'Please fill in all required fields',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: 'Creating Programme...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('<?php echo e(route("admin.programs.store")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Programme created successfully',
                confirmButtonColor: '#0d5c3c'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to create programme',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to create programme. Please try again.',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Open edit modal with program data
function openEditModal(id) {
    // Show loading
    Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Fetch program data
    fetch(`/admin/programs/${id}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        Swal.close();
        
        if (data.success && data.program) {
            const program = data.program;
            
            // Populate form fields
            document.getElementById('editProgramId').value = program.id;
            document.getElementById('editTitle').value = program.title || '';
            document.getElementById('editLocation').value = program.location || '';
            document.getElementById('editTheme').value = program.theme || '';
            
            // Handle date - try multiple formats
            if (program.event_date) {
                document.getElementById('editDate').value = program.event_date_formatted || program.event_date;
            }
            
            // Handle time - try multiple formats
            if (program.event_time) {
                document.getElementById('editTime').value = program.event_time_formatted || program.event_time;
            }
            
            // Show modal
            document.getElementById('editModal').style.display = 'flex';
            
            // Initialize datepicker for edit modal
            setTimeout(() => {
                flatpickr('.date-picker-edit', {
                    dateFormat: 'd/m/Y',
                    allowInput: true,
                    defaultDate: program.event_date_formatted || program.event_date
                });
            }, 100);
        } else {
            throw new Error(data.message || 'Invalid response format');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to load programme data. Please check console for details.',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Close edit modal
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editForm').reset();
}

// Alternative: Use POST with _method field (Laravel's expected way)
// Edit form submission - Simplified version
function handleEditSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const programId = document.getElementById('editProgramId').value;
    
    console.log('Submitting edit for program ID:', programId);
    
    // Show loading
    Swal.fire({
        title: 'Updating Programme...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Use POST method with _method=PUT (Laravel's standard approach)
    const url = `/admin/programs/${programId}`;
    
    fetch(url, {
        method: 'POST', // Use POST, not PUT
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(async response => {
        const text = await response.text();
        console.log('Raw response:', text);
        
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('JSON parse error:', e);
            throw new Error('Server returned invalid JSON');
        }
        
        if (!response.ok) {
            throw new Error(data.message || `HTTP ${response.status}`);
        }
        
        return data;
    })
    .then(data => {
        console.log('Parsed response:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message || 'Programme updated successfully',
                confirmButtonColor: '#0d5c3c'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to update programme');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to update programme',
            confirmButtonColor: '#dc3545'
        });
    });
    
    return false;
}
// Toggle display status
function toggleDisplay(id) {
    const checkbox = event.target;
    const isVisible = checkbox.checked ? 1 : 0;

    fetch(`/admin/programs/${id}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            is_visible: isVisible
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message || 'Display status updated successfully',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            throw new Error(data.message || 'Failed to update display status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to update display status',
            confirmButtonColor: '#dc3545'
        });
        checkbox.checked = !checkbox.checked; // revert toggle
    });
}

// Delete confirmation with improved messaging
function confirmDelete(id, title) {
    Swal.fire({
        title: 'DELETE PROGRAMME?',
        html: `Are you sure you want to delete<br><strong>${title}</strong>?<br><small>This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'YES, DELETE',
        cancelButtonText: 'CANCEL',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            deleteProgram(id);
        }
    });
}

// Delete program with loading state
function deleteProgram(id) {
    Swal.fire({
        title: 'Deleting...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/programs/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message || 'Programme has been deleted successfully',
                confirmButtonColor: '#0d5c3c'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to delete programme');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to delete programme',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Close modal on outside click
window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === addModal) {
        closeAddModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddModal();
        closeEditModal();
    }
});
</script>

<style>
    .fundraiser-table td:nth-child(2) { 
    width: 100px; 
    font-weight: 600;
    text-align: center;
    color: #222;
    padding: 12px;
}
    .fundraiser-table td:nth-child(3) { 
    width: 220px; 
    font-weight: 500;
    text-align: center;
    padding-left: 20px;
}
/* Toggle Switch Styles */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #0d5c3c;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

/* Modal Improvements */
.modal-large {
    max-width: 600px;
    width: 90%;
}

.modal-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 28px;
    color: #666;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 30px;
    height: 30px;
    transition: color 0.2s;
}

.modal-close-btn:hover {
    color: #333;
}

.form-row {
    margin-bottom: 20px;
}

.form-row.two-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 12px;
}

.modal-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.modal-input:focus {
    outline: none;
    border-color: #0d5c3c;
}

/* Icon Button Hover Effects */
.icon-btn {
    transition: transform 0.2s, opacity 0.2s;
}

.icon-btn:hover {
    transform: scale(1.1);
    opacity: 0.8;
}

/* Table Row Hover */
.fundraiser-table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Add Button Hover */
.add-button {
    transition: transform 0.3s, box-shadow 0.3s;
}

.add-button:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(13, 92, 60, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .form-row.two-columns {
        grid-template-columns: 1fr;
    }
    
    .modal-large {
        width: 95%;
    }
}
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.admin-template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/programs/index.blade.php ENDPATH**/ ?>