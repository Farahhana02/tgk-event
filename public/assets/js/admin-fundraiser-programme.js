// fundraiser-detail.js

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const fundraiserId = window.fundraiserId || new URLSearchParams(window.location.search).get('id') || 
                     window.location.pathname.split('/').pop();

// Validate receipt file (max 30MB)
function validateReceipt(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 30 * 1024 * 1024; // 30MB in bytes
        
        if (file.size > maxSize) {
            showError(`File size must be less than 30MB. Your file is ${(file.size / (1024 * 1024)).toFixed(2)}MB`);
            input.value = '';
            return false;
        }
        
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type.toLowerCase())) {
            showError('Only PDF, JPG, and PNG files are allowed.');
            input.value = '';
            return false;
        }
    }
    return true;
}

// Modal Functions
function openAddDonorModal() {
    document.getElementById('addDonorModal').style.display = 'flex';
    document.body.classList.add('modal-open');
    document.getElementById('addDonorForm').reset();
}

function closeAddDonorModal() {
    document.getElementById('addDonorModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

function openEditDonorModal(donorId) {
    showLoading('Loading donor data...');

    fetch(`/admin/donors/${donorId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        Swal.close();

        if (!data.success) {
            showError(data.message || 'Failed to load donor data.');
            return;
        }

        const donor = data.donation;

        // ✅ BASIC FIELDS (FIXED)
        document.getElementById('editDonorId').value = donor.id;
        document.getElementById('editDonorName').value = donor.donor_name;
        document.getElementById('editDonorEmail').value = donor.email;
        document.getElementById('editDonorPhone').value = donor.phone;
        document.getElementById('editDonorAmount').value = donor.amount_pledge;
        document.getElementById('editDonorNotes').value = donor.notes ?? '';
        document.getElementById('editDonorStatus').value = donor.status;

        // ✅ RECEIPT PREVIEW
        const currentReceipt = document.getElementById('currentReceipt');
        if (currentReceipt) {
            if (donor.receipt_file) {
                currentReceipt.innerHTML = `
                    <p style="font-size:12px;margin-top:6px;">
                        <strong>Current Receipt:</strong>
                        <a href="/storage/${donor.receipt_file}" target="_blank">View</a>
                    </p>
                `;
            } else {
                currentReceipt.innerHTML = '';
            }
        }

        // ✅ SUBMITTED FORM PREVIEW
        const currentForm = document.getElementById('currentSubmittedForm');
        if (currentForm) {
            if (donor.submitted_form_path) {
                currentForm.innerHTML = `
                    <p style="font-size:12px;margin-top:6px;">
                        <strong>Current Form:</strong>
                        <a href="/storage/${donor.submitted_form_path}" target="_blank">View</a>
                    </p>
                `;
            } else {
                currentForm.innerHTML = '';
            }
        }

        document.getElementById('editDonorModal').style.display = 'flex';
        document.body.classList.add('modal-open');
    })
    .catch(err => {
        Swal.close();
        console.error(err);
        showError('Failed to load donor data.');
    });
}


function closeEditDonorModal() {
    document.getElementById('editDonorModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

// Delete Donor
function confirmDeleteDonor(donorId, donorName) {
    Swal.fire({
        title: 'Are you sure?',
        html: `<strong>"${donorName}"</strong> will be permanently deleted.<br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            deleteDonor(donorId);
        }
    });
}

function deleteDonor(donorId) {
    showLoading('Deleting donor...');
    
    fetch(`/admin/donors/${donorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove row from table
            const row = document.getElementById(`donor-row-${donorId}`);
            if (row) row.remove();
            
            // Renumber rows
            renumberDonorRows();
            
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message || 'Donor deleted successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            showError(data.message || 'Failed to delete donor.');
        }
    })
    .catch(error => {
        console.error('Delete Error:', error);
        showError('Failed to delete donor.');
    });
}

// Add Donor Form Submit
document.getElementById('addDonorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate receipt if uploaded
    const receiptInput = document.getElementById('addReceipt');
    if (receiptInput.files.length > 0) {
        if (!validateReceipt(receiptInput)) return;
    }
    
    showLoading('Adding donor...');
    
    const formData = new FormData(this);
    
    fetch('/admin/donors', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddDonorModal();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Donor added successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            showError(data.message || 'Failed to add donor.');
        }
    })
    .catch(error => {
        console.error('Add Error:', error);
        showError('Failed to add donor.');
    });
});

// Edit Donor Form Submit
document.getElementById('editDonorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate receipt if uploaded
    const receiptInput = document.getElementById('editReceipt');
    if (receiptInput.files.length > 0) {
        if (!validateReceipt(receiptInput)) return;
    }
    
    showLoading('Updating donor...');
    
    const donorId = document.getElementById('editDonorId').value;
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    fetch(`/admin/donors/${donorId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditDonorModal();
            
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message || 'Donor updated successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            showError(data.message || 'Failed to update donor.');
        }
    })
    .catch(error => {
        console.error('Update Error:', error);
        showError('Failed to update donor.');
    });
});

// Helper Functions
function showLoading(message = 'Please wait...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#3085d6'
    });
}

function renumberDonorRows() {
    const rows = document.querySelectorAll('#donorsTableBody tr');
    rows.forEach((row, index) => {
        const bilCell = row.querySelector('td:first-child');
        if (bilCell) {
            bilCell.textContent = index + 1;
        }
    });
}
// ============================================
// EXPORT FUNCTIONS
// ============================================

// Export to Print
function exportToPrint() {
    showLoading('Preparing print view...');
    
    // Open print page in new window
    const printUrl = `/admin/fundraisers/${fundraiserId}/export/print`;
    window.open(printUrl, '_blank');
    
    setTimeout(() => {
        Swal.close();
    }, 1000);
}

// Export to Excel
function exportToExcel() {
    showLoading('Generating Excel file...');
    
    fetch(`/admin/fundraisers/${fundraiserId}/export/excel`, {
        method: 'GET',
        headers: {
            'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Export failed');
        }
        return response.blob();
    })
    .then(blob => {
        Swal.close();
        
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        
        // Generate filename with programme name and date
        const date = new Date().toISOString().split('T')[0];
        a.download = `donors_${fundraiserId}_${date}.xlsx`;
        
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Excel file downloaded successfully.',
            timer: 2000,
            showConfirmButton: false
        });
    })
    .catch(error => {
        console.error('Export Error:', error);
        Swal.close();
        showError('Failed to export to Excel. Please try again.');
    });
}