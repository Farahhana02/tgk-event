(function() {
    'use strict';

    // Global state
    const state = {
        editingPackageId: null,
        editingPaymentId: null,
        programmeId: null,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',
    };

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        state.programmeId = getProgrammeIdFromUrl();
        setupEventListeners();
        setupOverrideIndicators();
        
        // Check for session messages
        checkSessionMessages();
    });

    /* =====================================================
       HELPER FUNCTIONS
    ===================================================== */
    function getProgrammeIdFromUrl() {
        const match = window.location.pathname.match(/\/admin\/participations\/(\d+)/);
        return match ? match[1] : null;
    }

    function showToast(message, type = 'success') {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        } else {
            alert(message);
        }
    }

    function showConfirmation(message, title = 'Are you sure?') {
        return Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00542A',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'Cancel'
        });
    }

    function checkSessionMessages() {
        // Check for session data stored in meta tags
        const successMessage = document.querySelector('meta[name="success-message"]');
        const errorMessage = document.querySelector('meta[name="error-message"]');
        const infoMessage = document.querySelector('meta[name="info-message"]');
        
        if (successMessage && window.Swal) {
            showToast(successMessage.content, 'success');
        }
        
        if (errorMessage && window.Swal) {
            showToast(errorMessage.content, 'error');
        }
        
        if (infoMessage && window.Swal) {
            showToast(infoMessage.content, 'info');
        }
    }

    /* =====================================================
       EVENT LISTENERS
    ===================================================== */
    function setupEventListeners() {
        // Package type change
        const packageTypeSelect = document.getElementById('package_type');
        if (packageTypeSelect) {
            packageTypeSelect.addEventListener('change', toggleMultiPersonField);
        }

        // Copy link button
        const copyBtn = document.querySelector('[data-copy-link]');
        if (copyBtn) {
            copyBtn.addEventListener('click', copyPublicLink);
        }

        // Form active warning
        const formActiveSelect = document.querySelector('select[name="is_active"]');
        if (formActiveSelect) {
            formActiveSelect.addEventListener('change', warnFormDeactivation);
        }
    }

    function setupOverrideIndicators() {
        // Add override badges to existing items
        document.querySelectorAll('.paf-item').forEach(item => {
            const overrideData = item.dataset.override === 'true';
            if (overrideData) {
                const badge = document.createElement('span');
                badge.className = 'override-badge';
                badge.textContent = 'OVERRIDE';
                badge.style.cssText = `
                    background: #C08329;
                    color: white;
                    padding: 2px 8px;
                    border-radius: 4px;
                    font-size: 11px;
                    margin-left: 8px;
                `;
                
                const title = item.querySelector('.paf-item-title');
                if (title) {
                    title.appendChild(badge);
                }
            }
        });
    }

    /* =====================================================
       PACKAGE FUNCTIONS
    ===================================================== */
    window.fillPackageFromExisting = function(select) {
        const option = select.options[select.selectedIndex];
        if (!option.value) return;

        // Extract data attributes
        const packageData = {
            name: option.dataset.name || '',
            type: option.dataset.type || '',
            price: option.dataset.price || '',
            people: option.dataset.people || '',
            description: option.dataset.description || '',
        };

        // Fill form
        document.getElementById('package_label').value = packageData.name;
        document.getElementById('package_type').value = packageData.type;
        document.getElementById('package_price').value = packageData.price;
        document.getElementById('package_description').value = packageData.description;
        
        if (packageData.people && packageData.people !== 'null') {
            document.getElementById('people_per_package').value = packageData.people;
        }
        
        toggleMultiPersonField();
        
        // Show override explanation
        if (window.Swal) {
            Swal.fire({
                icon: 'info',
                title: 'Master Package Loaded',
                html: `
                    <div style="text-align:left; font-size:14px;">
                        <p><strong>${packageData.name}</strong> loaded from master library.</p>
                        <p class="text-gray-600 mt-2">You can:</p>
                        <ul class="text-sm mt-1">
                            <li>Save as-is (use master defaults)</li>
                            <li>Change values (creates programme-specific override)</li>
                        </ul>
                        <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded">
                            <small class="text-yellow-700">
                                <strong>Note:</strong> Only changed values will be saved as overrides.
                            </small>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Got it'
            });
        }
        
        select.selectedIndex = 0;
    };

    window.toggleMultiPersonField = function() {
        const type = document.getElementById('package_type').value;
        const field = document.getElementById('multiPersonField');
        const input = document.getElementById('people_per_package');
        
        if (type === 'multi_person') {
            field.style.display = 'block';
            if (input) input.required = true;
        } else {
            field.style.display = 'none';
            if (input) {
                input.required = false;
                input.value = '';
            }
        }
    };

    window.editPackage = function(id, name, type, price, description, people, isOverride = false) {
        state.editingPackageId = id;
        
        // Fill form
        document.getElementById('package_label').value = name;
        document.getElementById('package_type').value = type;
        document.getElementById('package_price').value = price;
        document.getElementById('package_description').value = description || '';
        
        if (people && people !== 'null') {
            document.getElementById('people_per_package').value = people;
        }
        
        toggleMultiPersonField();
        
        // Update form action
        const form = document.getElementById('packageForm');
        if (form) {
            form.action = `/admin/participations/${state.programmeId}/packages/${id}/update`;
            
            // Add method override if not exists
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }
            
            // Update button
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.textContent = 'UPDATE PACKAGE';
                btn.classList.add('paf-btn-orange');
                btn.classList.remove('paf-btn-green');
            }
            
            // Show override status
            if (isOverride) {
                showToast('Editing package with programme-specific overrides', 'info');
            }
        }
        
        // Scroll to form
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    window.clearPackageForm = function() {
        state.editingPackageId = null;
        
        // Clear form
        document.getElementById('package_label').value = '';
        document.getElementById('package_type').value = '';
        document.getElementById('package_price').value = '';
        document.getElementById('package_description').value = '';
        document.getElementById('people_per_package').value = '';
        
        toggleMultiPersonField();
        
        // Reset form
        const form = document.getElementById('packageForm');
        if (form) {
            form.action = `/admin/participations/${state.programmeId}/packages/new`;
            
            // Remove method override
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            
            // Reset button
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.textContent = 'SAVE PACKAGE';
                btn.classList.add('paf-btn-green');
                btn.classList.remove('paf-btn-orange');
            }
        }
    };

    /* =====================================================
       PAYMENT METHOD FUNCTIONS
    ===================================================== */
    window.fillPaymentFromExisting = function(select) {
        const option = select.options[select.selectedIndex];
        if (!option.value) return;

        // Fill form
        document.getElementById('payment_bank').value = option.dataset.bank || '';
        document.getElementById('payment_account_name').value = option.dataset.accountName || '';
        document.getElementById('payment_account_number').value = option.dataset.accountNumber || '';
        
        // Show explanation
        if (window.Swal) {
            Swal.fire({
                icon: 'info',
                title: 'Master Payment Method Loaded',
                html: `
                    <div style="text-align:left; font-size:14px;">
                        <p>Payment method loaded from master library.</p>
                        <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded">
                            <small class="text-blue-700">
                                <strong>Note:</strong> Changes will create programme-specific overrides.
                            </small>
                        </div>
                    </div>
                `,
                timer: 2000,
                showConfirmButton: false
            });
        }
        
        select.selectedIndex = 0;
    };

    window.editPayment = function(id, bank, accountName, accountNumber, isOverride = false) {
        state.editingPaymentId = id;
        
        // Fill form
        document.getElementById('payment_bank').value = bank;
        document.getElementById('payment_account_name').value = accountName;
        document.getElementById('payment_account_number').value = accountNumber;
        
        // Update form action
        const form = document.getElementById('paymentForm');
        if (form) {
            form.action = `/admin/participations/${state.programmeId}/payment-methods/${id}/update`;
            
            // Add method override
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }
            
            // Update button
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.textContent = 'UPDATE PAYMENT METHOD';
                btn.classList.add('paf-btn-orange');
                btn.classList.remove('paf-btn-green');
            }
            
            // Show override status
            if (isOverride) {
                showToast('Editing payment method with programme-specific overrides', 'info');
            }
        }
        
        // Scroll to form
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    window.clearPaymentForm = function() {
        state.editingPaymentId = null;
        
        // Clear form
        document.getElementById('payment_bank').value = '';
        document.getElementById('payment_account_name').value = '';
        document.getElementById('payment_account_number').value = '';
        
        // Reset form
        const form = document.getElementById('paymentForm');
        if (form) {
            form.action = `/admin/participations/${state.programmeId}/payment-methods/new`;
            
            // Remove method override
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            
            // Reset button
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.textContent = 'SAVE PAYMENT METHOD';
                btn.classList.add('paf-btn-green');
                btn.classList.remove('paf-btn-orange');
            }
        }
    };

    /* =====================================================
       UTILITY FUNCTIONS
    ===================================================== */
    async function copyPublicLink() {
        const input = document.querySelector('[data-public-link]');
        if (!input || !input.value) {
            showToast('Please generate the public link first.', 'warning');
            return;
        }

        try {
            await navigator.clipboard.writeText(input.value);
            showToast('Public link copied to clipboard!', 'success');
            
            // Visual feedback
            const btn = document.querySelector('[data-copy-link]');
            if (btn) {
                const originalText = btn.textContent;
                btn.textContent = 'COPIED!';
                btn.style.background = '#00542A';
                
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '';
                }, 2000);
            }
        } catch (err) {
            // Fallback
            input.select();
            document.execCommand('copy');
            showToast('Link copied!', 'success');
        }
    }

    function warnFormDeactivation(event) {
        if (event.target.value === '0') {
            event.preventDefault();
            
            showConfirmation('Deactivating will LOCK all package prices permanently. Continue?', 'Lock Programme?')
                .then((result) => {
                    if (result.isConfirmed) {
                        event.target.value = '0';
                        // Submit the form
                        event.target.closest('form').submit();
                    } else {
                        event.target.value = '1';
                    }
                });
        }
    }

    /* =====================================================
       DELETE CONFIRMATION FUNCTIONS
    ===================================================== */
    window.confirmPackageDelete = function(form, packageName) {
        Swal.fire({
            title: 'Delete Package?',
            html: `Are you sure you want to delete <strong>${packageName}</strong> from this programme?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    };

    window.confirmPaymentDelete = function(form, bankName) {
        Swal.fire({
            title: 'Delete Payment Method?',
            html: `Are you sure you want to delete <strong>${bankName}</strong> from this programme?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    };

    /* =====================================================
       AJAX HANDLERS FOR OVERRIDE UPDATES
    ===================================================== */
    window.updatePackagePriceOverride = function(programmePackageId, currentPrice, masterPrice) {
        Swal.fire({
            title: 'Update Package Price',
            html: `
                <div style="text-align:left;">
                    <p class="text-sm text-gray-600">Master price: RM ${parseFloat(masterPrice).toFixed(2)}</p>
                    <input type="number" id="priceInput" class="swal2-input" 
                           value="${currentPrice}" step="0.01" min="0" placeholder="Enter new price">
                    <div class="mt-2 text-xs text-gray-500">
                        Leave empty to use master price (RM ${parseFloat(masterPrice).toFixed(2)})
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const input = document.getElementById('priceInput');
                const value = input.value.trim();
                
                if (value === '') {
                    return { price: null, use_master: true };
                }
                
                const numValue = parseFloat(value);
                if (isNaN(numValue) || numValue < 0) {
                    Swal.showValidationMessage('Please enter a valid price');
                    return false;
                }
                
                return { price: numValue, use_master: false };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const data = result.value;
                const url = `/admin/participations/${state.programmeId}/packages/${programmePackageId}/override-price`;
                
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': state.csrfToken,
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Price updated successfully!', 'success');
                        // Reload page or update UI
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Update failed', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error updating price', 'error');
                });
            }
        });
    };

    // Make functions globally available
    window.state = state;
    window.showToast = showToast;
    window.showConfirmation = showConfirmation;
})();