// Program Detail JavaScript
let scheduleIndex = 0;
let vipIndex = 0;
let priceIndex = 0;

// Add schedule item
function addSchedule() {
    const container = document.getElementById('schedulesContainer');
    const index = container.children.length;
    
    const scheduleItem = document.createElement('div');
    scheduleItem.className = 'schedule-item';
    scheduleItem.innerHTML = `
        <input type="text" name="schedules[${index}][time]" class="form-control schedule-time" placeholder="XX:XX a.m/p.m">
        <input type="text" name="schedules[${index}][description]" class="form-control schedule-desc" placeholder="Description">
    `;
    container.appendChild(scheduleItem);
}

// Add VIP item
function addVip() {
    const container = document.getElementById('vipContainer');
    const index = container.children.length;
    
    const vipItem = document.createElement('div');
    vipItem.className = 'vip-item';
    vipItem.innerHTML = `
        <div class="vip-image-upload">
            <div class="vip-placeholder" id="vipPlaceholder${index}">
                <img src="/assets/icons/upload.png">
            </div>
            <input type="file" name="vip_list[${index}][image]" class="vip-file-input" accept="image/*" 
                   onchange="previewVipImage(this, ${index})">
        </div>
        <div class="vip-details">
            <input type="text" name="vip_list[${index}][name]" class="form-control" placeholder="NAME">
            <input type="text" name="vip_list[${index}][position]" class="form-control" placeholder="POSITION">
        </div>
        <button type="button" class="btn-add" onclick="addVip()">+</button>
    `;
    container.appendChild(vipItem);
}

// Preview VIP image
function previewVipImage(input, index) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const placeholder = document.getElementById('vipPlaceholder' + index);
            if (placeholder) {
                placeholder.innerHTML = `<img src="${e.target.result}" class="vip-preview">`;
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Add price item
function addPrice(type) {
    const containerId = type === 'participation' ? 'priceContainer' : 'packageContainer';
    const fieldName = type === 'participation' ? 'participation_prices' : 'sponsorship_packages';
    const container = document.getElementById(containerId);
    const index = container.children.length;
    
    const priceItem = document.createElement('div');
    priceItem.className = 'price-item';
    priceItem.innerHTML = `
        <input type="text" name="${fieldName}[${index}][description]" class="form-control price-desc" 
               placeholder="${type === 'participation' ? 'ex: Per person' : 'ex: Platinum'}">
        <input type="text" name="${fieldName}[${index}][amount]" class="form-control price-amount" placeholder="RM xxxx">
    `;
    container.appendChild(priceItem);
}

// Toggle form type
function toggleFormType(section, type) {
    const fileInput = document.getElementById(`${section}FileInput`);
    const linkInput = document.getElementById(`${section}LinkInput`);
    const formTypeInput = document.getElementById(`${section}FormType`);
    const buttons = document.querySelectorAll(`#${section}-section .toggle-btn`);
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (type === 'file') {
        fileInput.style.display = 'block';
        linkInput.style.display = 'none';
    } else {
        fileInput.style.display = 'none';
        linkInput.style.display = 'block';
    }
    
    formTypeInput.value = type;
}

// ========== VIP SECTION - COMPLETE FIX ==========

// Initialize VIP counter
window.vipCounter = 0;

// Set initial counter based on existing VIPs
document.addEventListener('DOMContentLoaded', function() {
    const vipItems = document.querySelectorAll('#vipContainer .vip-item');
    window.vipCounter = vipItems.length;
    console.log('Initial VIP counter:', window.vipCounter);
});

// ========== SAVE VIP FUNCTION - FIXED ==========
window.saveVip = function(event) {
    event.preventDefault();
    
    const formElement = document.getElementById('vipForm');
    if (!formElement) {
        console.error('VIP form not found');
        return;
    }
    
    const formData = new FormData(formElement);
    
    console.log('=== VIP SAVE DEBUG ===');
    console.log('Program ID:', window.programId);
    
    // Get all VIP items
    const vipItems = document.querySelectorAll('#vipContainer .vip-item');
    console.log('VIP items found:', vipItems.length);
    
    // Clear and rebuild VIP data to ensure proper indexing
    const vipKeys = Array.from(formData.keys()).filter(key => key.startsWith('vip_list'));
    vipKeys.forEach(key => formData.delete(key));
    
    // Re-add VIP data with proper indexing
    vipItems.forEach((item, index) => {
        const nameInput = item.querySelector('input[name$="[name]"]');
        const positionInput = item.querySelector('input[name$="[position]"]');
        const existingImageInput = item.querySelector('input[name$="[existing_image]"]');
        const fileInput = item.querySelector('input[type="file"]');
        
        if (!nameInput || !positionInput) {
            console.error(`Missing inputs for VIP ${index}`);
            return;
        }
        
        const name = nameInput.value.trim();
        const position = positionInput.value.trim();
        
        if (!name || !position) {
            console.warn(`VIP ${index} has empty name or position`);
            return;
        }
        
        console.log(`VIP ${index}:`, {
            name: name,
            position: position,
            has_existing: existingImageInput ? existingImageInput.value : 'none',
            has_new_file: fileInput && fileInput.files.length > 0
        });
        
        // Add data with proper indexing
        formData.append(`vip_list[${index}][name]`, name);
        formData.append(`vip_list[${index}][position]`, position);
        
        // Add existing image if available
        if (existingImageInput && existingImageInput.value) {
            formData.append(`vip_list[${index}][existing_image]`, existingImageInput.value);
        }
        
        // Add new image file if selected
        if (fileInput && fileInput.files.length > 0) {
            formData.append(`vip_list[${index}][image]`, fileInput.files[0]);
        }
    });
    
    // Validate that we have at least one VIP
    const hasVipData = Array.from(formData.keys()).some(key => key.startsWith('vip_list'));
    if (!hasVipData) {
        Swal.fire({
            icon: 'warning',
            title: 'No VIP Data',
            text: 'Please add at least one VIP with name and position',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Show what we're sending
    console.log('FormData contents:');
    for (let pair of formData.entries()) {
        if (pair[1] instanceof File) {
            console.log(pair[0], '= [FILE]', pair[1].name);
        } else {
            console.log(pair[0], '=', pair[1]);
        }
    }
    
    // Show loading
    Swal.fire({
        title: 'Saving VIP...',
        text: 'Please wait while we update the VIP section',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Send request
    fetch(`/admin/programs/${window.programId}/vip`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'VIP section updated successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save VIP section',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== ADD VIP FUNCTION - FIXED ==========
window.addVip = function() {
    const container = document.getElementById('vipContainer');
    if (!container) {
        console.error('VIP container not found');
        return;
    }
    
    const newVip = document.createElement('div');
    newVip.className = 'vip-item';
    newVip.setAttribute('data-index', window.vipCounter);
    newVip.innerHTML = `
        <div class="vip-image-upload">
            <div class="vip-placeholder" id="vipPlaceholder${window.vipCounter}">
                <img src="/assets/icons/upload.png" alt="Upload">
                <span>Upload Image</span>
            </div>
            <input type="file" 
                   name="vip_list[${window.vipCounter}][image]" 
                   class="vip-file-input" 
                   accept="image/*" 
                   onchange="previewVipImage(this, ${window.vipCounter})">
        </div>
        <div class="vip-details">
            <input type="text" 
                   name="vip_list[${window.vipCounter}][name]" 
                   class="form-control" 
                   placeholder="NAME" 
                   required>
            <input type="text" 
                   name="vip_list[${window.vipCounter}][position]" 
                   class="form-control" 
                   placeholder="POSITION" 
                   required>
        </div>
        <button type="button" 
                class="btn-remove" 
                onclick="removeVip(this)" 
                title="Remove VIP">×</button>
    `;
    container.appendChild(newVip);
    window.vipCounter++;
    
    console.log('Added VIP item, new counter:', window.vipCounter);
}

// ========== REMOVE VIP FUNCTION - FIXED ==========
window.removeVip = function(button) {
    const item = button.closest('.vip-item');
    const container = document.getElementById('vipContainer');
    
    if (!container || !item) {
        console.error('Cannot find container or item');
        return;
    }
    
    // Don't allow removing if it's the last item
    if (container.children.length <= 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Remove',
            text: 'At least one VIP entry is required',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Confirm deletion
    Swal.fire({
        title: 'Remove VIP?',
        text: 'Are you sure you want to remove this VIP entry?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            item.remove();
            
            // Re-index remaining items
            const vipItems = container.querySelectorAll('.vip-item');
            vipItems.forEach((item, newIndex) => {
                item.setAttribute('data-index', newIndex);
                
                // Update field names
                const nameInput = item.querySelector('input[placeholder="NAME"]');
                const positionInput = item.querySelector('input[placeholder="POSITION"]');
                const fileInput = item.querySelector('input[type="file"]');
                const existingImageInput = item.querySelector('input[type="hidden"]');
                
                if (nameInput) nameInput.name = `vip_list[${newIndex}][name]`;
                if (positionInput) positionInput.name = `vip_list[${newIndex}][position]`;
                if (fileInput) fileInput.name = `vip_list[${newIndex}][image]`;
                if (existingImageInput) existingImageInput.name = `vip_list[${newIndex}][existing_image]`;
                
                // Update placeholder ID
                const placeholder = item.querySelector('.vip-placeholder');
                if (placeholder) placeholder.id = `vipPlaceholder${newIndex}`;
                
                // Update preview ID
                const preview = item.querySelector('.vip-preview');
                if (preview) preview.id = `vipPreview${newIndex}`;
                
                // Update onchange handler
                if (fileInput) {
                    fileInput.setAttribute('onchange', `previewVipImage(this, ${newIndex})`);
                }
            });
            
            Swal.fire({
                icon: 'success',
                title: 'Removed!',
                text: 'VIP entry removed',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// ========== PREVIEW VIP IMAGE FUNCTION - FIXED ==========
window.previewVipImage = function(input, index) {
    console.log('Preview VIP image called for index:', index);
    
    if (!input.files || input.files.length === 0) {
        console.log('No file selected');
        return;
    }
    
    const file = input.files[0];
    console.log('File selected:', file.name, file.size, 'bytes');
    
    // Validate file size (10MB)
    if (file.size > 10 * 1024 * 1024) {
        Swal.fire({
            icon: 'warning',
            title: 'File Too Large',
            text: 'Image size must be less than 10MB',
            confirmButtonColor: '#0d5c3c'
        });
        input.value = '';
        return;
    }
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid File Type',
            text: 'Please select an image file',
            confirmButtonColor: '#0d5c3c'
        });
        input.value = '';
        return;
    }
    
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const placeholder = document.getElementById('vipPlaceholder' + index);
        const preview = document.getElementById('vipPreview' + index);
        
        console.log('Reader loaded, placeholder:', placeholder, 'preview:', preview);
        
        if (preview) {
            // Update existing preview
            preview.src = e.target.result;
            console.log('Updated existing preview');
        } else if (placeholder) {
            // Create new preview, replacing placeholder
            placeholder.outerHTML = `<img src="${e.target.result}" class="vip-preview" id="vipPreview${index}">`;
            console.log('Created new preview');
        } else {
            console.error('Neither placeholder nor preview found for index:', index);
        }
    };
    
    reader.onerror = function(error) {
        console.error('FileReader error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to read image file',
            confirmButtonColor: '#dc3545'
        });
    };
    
    reader.readAsDataURL(file);
}

// ========== DEBUGGING HELPER ==========
window.debugVipForm = function() {
    console.log('=== VIP FORM DEBUG ===');
    
    const form = document.getElementById('vipForm');
    console.log('Form exists:', !!form);
    
    const container = document.getElementById('vipContainer');
    console.log('Container exists:', !!container);
    
    if (container) {
        const items = container.querySelectorAll('.vip-item');
        console.log('VIP items count:', items.length);
        
        items.forEach((item, index) => {
            const nameInput = item.querySelector('input[name$="[name]"]');
            const positionInput = item.querySelector('input[name$="[position]"]');
            const fileInput = item.querySelector('input[type="file"]');
            const existingImageInput = item.querySelector('input[name$="[existing_image]"]');
            
            console.log(`VIP ${index}:`, {
                name: nameInput ? nameInput.value : 'NO INPUT',
                position: positionInput ? positionInput.value : 'NO INPUT',
                fileInputExists: !!fileInput,
                hasFile: fileInput && fileInput.files.length > 0,
                existingImage: existingImageInput ? existingImageInput.value : 'NO INPUT'
            });
        });
    }
    
    console.log('Current vipCounter:', window.vipCounter);
    console.log('=== END DEBUG ===');
}

// Log when script is loaded
console.log('VIP section JavaScript loaded successfully');
// Save Sponsorship
function saveSponsorship() {
    const form = document.getElementById('sponsorshipForm');
    const formData = new FormData(form);
    
    // Collect packages
    const packages = [];
    document.querySelectorAll('#packageContainer .price-item').forEach(item => {
        packages.push({
            description: item.querySelector('.price-desc').value,
            amount: item.querySelector('.price-amount').value
        });
    });
    formData.append('sponsorship_packages', JSON.stringify(packages));
    
    fetch(`/admin/programs/${programId}/sponsorship`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        }
    });
}

// Preview programme images
function previewProgrammeImages(input) {
    const placeholder = document.querySelector('.programme-image-placeholder');
    
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgItem = document.createElement('div');
                imgItem.className = 'programme-img-item';
                imgItem.innerHTML = `
                    <img src="${e.target.result}">
                    <button type="button" class="delete-img-btn" onclick="this.parentElement.remove()">×</button>
                `;
                placeholder.appendChild(imgItem);
            }
            reader.readAsDataURL(file);
        });
    }
}

// Delete programme image
function deleteProgrammeImage(index) {
    Swal.fire({
        title: 'Delete Image?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/programs/${programId}/programme-image`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ image_index: index })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                }
            });
        }
    });
}

// Save Programme
function saveProgramme() {
    const form = document.getElementById('programmeForm');
    const formData = new FormData(form);
    
    fetch(`/admin/programs/${programId}/programme`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        }
    });
}

// Preview program (will open public view when implemented)
function previewProgram() {
    window.open(`/programs/${programId}`, '_blank');
}

// Add paragraph helper
function addParagraph(fieldName) {
    const textarea = document.querySelector(`textarea[name="${fieldName}"]`);
    textarea.value += '\n\n';
    textarea.focus();
}

// Event listener for add image button
document.addEventListener('DOMContentLoaded', function() {
    const addImageBtn = document.querySelector('.add-image-btn');
    const imageInput = document.getElementById('programmeImageInput');
    
    if (addImageBtn && imageInput) {
        addImageBtn.addEventListener('click', function() {
            imageInput.click();
        });
    }
});