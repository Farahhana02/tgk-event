// /assets/js/fundraiser.js - UPDATED WITH FLATPICKR DATE PICKER

// CSRF Token setup
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Store flatpickr instances
let startDatePicker, endDatePicker, editStartDatePicker, editEndDatePicker;

// Format date from YYYY-MM-DD to dd/mm/yyyy for display
function formatDateToDisplay(dateString) {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    } catch (e) {
        return dateString;
    }
}

// Validate file size (max 15MB)
function validateFileSize(file, maxSizeMB = 15) {
    if (!file) return { valid: true };
    
    const maxSizeBytes = maxSizeMB * 1024 * 1024;
    if (file.size > maxSizeBytes) {
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        return {
            valid: false,
            message: `File size must be less than ${maxSizeMB}MB. Your file is ${fileSizeMB}MB`
        };
    }
    return { valid: true };
}

// Validate file type
function validateFileType(file) {
    if (!file) return { valid: true };
    
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type.toLowerCase())) {
        return {
            valid: false,
            message: 'Only JPG, PNG, GIF, and WEBP images are allowed.'
        };
    }
    return { valid: true };
}

// Image Preview Functions
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        const typeValidation = validateFileType(file);
        if (!typeValidation.valid) {
            showError(typeValidation.message);
            input.value = '';
            preview.innerHTML = '';
            return;
        }
        
        // Validate file size
        const sizeValidation = validateFileSize(file, 15);
        if (!sizeValidation.valid) {
            showError(sizeValidation.message);
            input.value = '';
            preview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; margin-top: 10px; border-radius: 5px;">`;
        }
        reader.onerror = function() {
            preview.innerHTML = '<p style="color: red;">Error loading image preview</p>';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '';
    }
}

// MODAL FUNCTIONS
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
    document.querySelector('#addForm').reset();
    document.getElementById('addImagePreview').innerHTML = '';
    
    // Clear date pickers
    if (startDatePicker) startDatePicker.clear();
    if (endDatePicker) endDatePicker.clear();
    
    // Focus on first input
    setTimeout(() => {
        document.querySelector('#addForm input[name="programme_name"]')?.focus();
    }, 100);
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function openEditModal(id) {
    showLoading('Loading fundraiser data...');
    
    fetch(`/admin/fundraisers/${id}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 404) {
                throw new Error('Fundraiser not found');
            }
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        Swal.close();
        if (data.success) {
            const fundraiser = data.fundraiser;
            
            document.getElementById('editFundraiserId').value = fundraiser.id;
            document.getElementById('editProgrammeName').value = fundraiser.programme_name;
            
// Set date values using flatpickr - convert from Y-m-d to Date object
if (editStartDatePicker && fundraiser.start_date) {
    const parts = fundraiser.start_date.split('-');
    const dateObj = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
    editStartDatePicker.setDate(dateObj, true);
}
if (editEndDatePicker && fundraiser.end_date) {
    const parts = fundraiser.end_date.split('-');
    const dateObj = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
    editEndDatePicker.setDate(dateObj, true);
}
            
            document.getElementById('editTargetAmount').value = parseFloat(fundraiser.target_amount || 0).toFixed(2);
            document.getElementById('editDescription').value = fundraiser.description || '';
            
            // Show current image if exists
            const editImagePreview = document.getElementById('editImagePreview');
            if (editImagePreview) {
                if (fundraiser.image_path) {
                    const imageUrl = fundraiser.image_url || `/storage/${fundraiser.image_path}`;
                    editImagePreview.innerHTML = `
                        <p><small>Current Image:</small></p>
                        <img src="${imageUrl}" alt="Current Image" 
                             onerror="this.style.display='none'" 
                             style="max-width: 100%; max-height: 200px; margin-top: 10px; border-radius: 5px;">
                        <p style="font-size: 12px; color: #666; margin-top: 5px;">Upload new image to replace current one</p>
                    `;
                } else {
                    editImagePreview.innerHTML = '<p style="color: #666;">No image uploaded</p>';
                }
            }
            
            // Show current donation form
            const currentFormWrapper = document.getElementById('currentFormWrapper');
            const currentFormLink = document.getElementById('currentFormLink');

            if (fundraiser.form_path) {
                currentFormLink.href = `/storage/${fundraiser.form_path}`;
                currentFormWrapper.style.display = 'block';
            } else {
                currentFormWrapper.style.display = 'none';
            }

            // Clear file input
            const editImageInput = document.getElementById('editImage');
            if (editImageInput) editImageInput.value = '';
            
            document.getElementById('editModal').style.display = 'flex';
        } else {
            showError(data.message || 'Failed to load fundraiser data.');
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Edit Error:', error);
        showError(error.message || 'Failed to load fundraiser data.');
    });
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function showInfoModal(id) {
    showLoading('Loading information...');
    
    fetch(`/admin/fundraisers/${id}/info`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        Swal.close();
        if (data.success) {
            const fundraiser = data.fundraiser;
            
            // Show image
            const infoImage = document.getElementById('infoImage');
            if (infoImage) {
                if (fundraiser.image_path) {
                    const imageUrl = fundraiser.image_url || `/storage/${fundraiser.image_path}`;
                    infoImage.src = imageUrl;
                    infoImage.style.display = 'block';
                    infoImage.onerror = function() {
                        this.style.display = 'none';
                    }
                } else {
                    infoImage.style.display = 'none';
                }
            }
            
            document.getElementById('infoProgrammeName').textContent = fundraiser.programme_name || 'N/A';
            document.getElementById('infoStartDate').textContent = formatDateToDisplay(fundraiser.start_date);
            document.getElementById('infoEndDate').textContent = formatDateToDisplay(fundraiser.end_date);
            document.getElementById('infoTargetAmount').textContent = 'RM ' + parseFloat(fundraiser.target_amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            document.getElementById('infoProgress').textContent = (fundraiser.progress || 0) + ' %';
            document.getElementById('infoStatus').textContent = fundraiser.status || 'active';
            document.getElementById('infoDescription').textContent = fundraiser.description || 'No description';
            
            document.getElementById('infoModal').style.display = 'flex';
        } else {
            showError('Failed to load information.');
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Info Error:', error);
        showError('Failed to load information.');
    });
}

function closeInfoModal() {
    document.getElementById('infoModal').style.display = 'none';
}

// DELETE FUNCTION
function confirmDelete(id, programmeName) {
    Swal.fire({
        title: 'Are you sure?',
        html: `<strong>"${programmeName}"</strong> will be permanently deleted.<br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        width: '400px'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteFundraiser(id);
        }
    });
}

function deleteFundraiser(id) {
    showLoading('Deleting fundraiser...');
    
    fetch(`/admin/fundraisers/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the row from table
            const row = document.getElementById(`row-${id}`);
            if (row) {
                row.remove();
            }
            
            // Re-number the BIL column
            renumberTableRows();
            
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message || 'Fundraiser has been deleted successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            showError(data.message || 'Failed to delete fundraiser.');
        }
    })
    .catch(error => {
        console.error('Delete Error:', error);
        showError(error.message || 'Failed to delete fundraiser. Please try again.');
    });
}

// ADD FUNDRAISER
document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Add form submitted');
    
    const startDate = document.getElementById('startDateHidden').value;
    const endDate = document.getElementById('endDateHidden').value;
    
    // Date validation
    if (!startDate) {
        showError('Please select a start date.');
        return;
    }
    
    if (!endDate) {
        showError('Please select an end date.');
        return;
    }
    
    // Check end date is after start date
    if (new Date(endDate) <= new Date(startDate)) {
        showError('End date must be after start date.');
        return;
    }
    
    // File validation
    const imageInput = document.getElementById('addImage');
    if (imageInput && imageInput.files.length > 0) {
        const file = imageInput.files[0];
        
        // Validate file type
        const typeValidation = validateFileType(file);
        if (!typeValidation.valid) {
            showError(typeValidation.message);
            return;
        }
        
        // Validate file size
        const sizeValidation = validateFileSize(file, 15);
        if (!sizeValidation.valid) {
            showError(sizeValidation.message);
            return;
        }
    }
    
    showLoading('Creating fundraiser...');
    
    const formData = new FormData(this);
    
    // Debug: Log form data
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ', pair[1]);
    }
    
    fetch('/admin/fundraisers', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || `Server error: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            closeAddModal();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Fundraiser created successfully.',
                timer: 2000,
                showConfirmButton: false,
                width: '400px'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to create fundraiser.');
        }
    })
    .catch(error => {
        console.error('Add Error:', error);
        showError(error.message || 'Failed to create fundraiser. Please try again.');
    });
});

// UPDATE FUNDRAISER
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Edit form submitted');
    
    const startDate = document.getElementById('editStartDateHidden').value;
    const endDate = document.getElementById('editEndDateHidden').value;
    
    // Date validation
    if (!startDate) {
        showError('Please select a start date.');
        return;
    }
    
    if (!endDate) {
        showError('Please select an end date.');
        return;
    }
    
    // Check end date is after start date
    if (new Date(endDate) <= new Date(startDate)) {
        showError('End date must be after start date.');
        return;
    }
    
    // File validation
    const imageInput = document.getElementById('editImage');
    if (imageInput && imageInput.files.length > 0) {
        const file = imageInput.files[0];
        
        // Validate file type
        const typeValidation = validateFileType(file);
        if (!typeValidation.valid) {
            showError(typeValidation.message);
            return;
        }
        
        // Validate file size
        const sizeValidation = validateFileSize(file, 15);
        if (!sizeValidation.valid) {
            showError(sizeValidation.message);
            return;
        }
    }
    
    showLoading('Updating fundraiser...');
    
    const id = document.getElementById('editFundraiserId').value;
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    // Debug: Log form data
    console.log('Edit FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ', pair[1]);
    }
    
    fetch(`/admin/fundraisers/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || `Server error: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Update Response:', data);
        if (data.success) {
            closeEditModal();
            
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message || 'Fundraiser updated successfully.',
                timer: 2000,
                showConfirmButton: false,
                width: '400px'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to update fundraiser.');
        }
    })
    .catch(error => {
        console.error('Update Error:', error);
        showError(error.message || 'Failed to update fundraiser. Please try again.');
    });
});

// HELPER FUNCTIONS
function showLoading(message = 'Please wait...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        },
        width: '300px'
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#3085d6',
        width: '400px'
    });
}

function renumberTableRows() {
    const rows = document.querySelectorAll('#fundraisersTableBody tr');
    rows.forEach((row, index) => {
        const bilCell = row.querySelector('td:first-child');
        if (bilCell) {
            bilCell.textContent = index + 1;
        }
    });
}

function searchFundraisers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#fundraisersTableBody tr');
    
    rows.forEach(row => {
        const programmeName = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
        if (programmeName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('Fundraiser JS loaded');
    
    // Initialize Flatpickr for Add Form
    const startDateDisplay = document.getElementById('startDateDisplay');
    const startDateHidden = document.getElementById('startDateHidden');
    
    if (startDateDisplay) {
        startDatePicker = flatpickr(startDateDisplay, {
            dateFormat: "d/m/Y",
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Convert to YYYY-MM-DD for database
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    startDateHidden.value = `${year}-${month}-${day}`;
                    
                    // Update end date minimum
                    if (endDatePicker) {
                        endDatePicker.set('minDate', selectedDates[0]);
                    }
                }
            }
        });
    }
    
    const endDateDisplay = document.getElementById('endDateDisplay');
    const endDateHidden = document.getElementById('endDateHidden');
    
    if (endDateDisplay) {
        endDatePicker = flatpickr(endDateDisplay, {
            dateFormat: "d/m/Y",
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Convert to YYYY-MM-DD for database
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    endDateHidden.value = `${year}-${month}-${day}`;
                }
            }
        });
    }
    
    // Initialize Flatpickr for Edit Form
    const editStartDateDisplay = document.getElementById('editStartDateDisplay');
    const editStartDateHidden = document.getElementById('editStartDateHidden');
    
    if (editStartDateDisplay) {
        editStartDatePicker = flatpickr(editStartDateDisplay, {
            dateFormat: "d/m/Y",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Convert to YYYY-MM-DD for database
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    editStartDateHidden.value = `${year}-${month}-${day}`;
                    
                    // Update end date minimum
                    if (editEndDatePicker) {
                        editEndDatePicker.set('minDate', selectedDates[0]);
                    }
                }
            }
        });
    }
    
    const editEndDateDisplay = document.getElementById('editEndDateDisplay');
    const editEndDateHidden = document.getElementById('editEndDateHidden');
    
    if (editEndDateDisplay) {
        editEndDatePicker = flatpickr(editEndDateDisplay, {
            dateFormat: "d/m/Y",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Convert to YYYY-MM-DD for database
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    editEndDateHidden.value = `${year}-${month}-${day}`;
                }
            }
        });
    }
    
    // Press Enter to search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchFundraisers();
            }
        });
    }
    
    // Image preview for add modal
    const addImageInput = document.getElementById('addImage');
    if (addImageInput) {
        addImageInput.addEventListener('change', function() {
            previewImage(this, 'addImagePreview');
        });
    }
    
    // Image preview for edit modal
    const editImageInput = document.getElementById('editImage');
    if (editImageInput) {
        editImageInput.addEventListener('change', function() {
            previewImage(this, 'editImagePreview');
        });
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddModal();
            closeEditModal();
            closeInfoModal();
        }
    });
    document.querySelector('[name="form_file"]').addEventListener('change', function () {
    if (this.files[0] && this.files[0].size > 10 * 1024 * 1024) {
        alert('File size must not exceed 10MB');
        this.value = '';
    }
});
function deleteProgrammeForm() {
    const fundraiserId = document.getElementById('editFundraiserId').value;

    Swal.fire({
        title: 'Delete donation form?',
        text: 'This will permanently remove the current form.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/admin/fundraisers/${fundraiserId}/form`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Deleted', data.message, 'success');
                document.getElementById('currentFormWrapper').style.display = 'none';
            } else {
                Swal.fire('Error', 'Failed to delete form', 'error');
            }
        });
    });
}

});