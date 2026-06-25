

<?php $__env->startSection('title', 'Donate to ' . $fundraiser->programme_name); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="/assets/css/fundraiser-detail.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Breadcrumb -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">DONATION</div>

        <div class="breadcrumb-path">
            <a href="/">
                <img src="/assets/icons/Home.png" class="breadcrumb-home-icon">
            </a>
            <span>/</span>

            <a href="/fundraisers" class="breadcrumb-link">Fundraisers</a>
            <span>/</span>

            <a href="<?php echo e(route('fundraiser.detail', $fundraiser->id)); ?>" class="breadcrumb-link">
                <?php echo e(strtoupper($fundraiser->programme_name)); ?>

            </a>
            <span>/</span>

            <span class="breadcrumb-current">DONATE</span>
        </div>
    </div>
</div>

<!-- Donation Form Section -->
<div class="detail-section">
    <div class="container" style="max-width: 800px;">
        <div class="donation-card" style="margin: 0 auto;">

            <h2 class="donation-title">Make a Donation</h2>
            <p class="donation-subtitle">
                Your support helps us create meaningful impact for
                <strong><?php echo e(strtoupper($fundraiser->programme_name)); ?></strong>.
            </p>

            <form id="donationForm"
                  action="<?php echo e(route('fundraiser.donate', $fundraiser->id)); ?>"
                  method="POST"
                  enctype="multipart/form-data"
                  class="donation-form">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text"
                           name="name"
                           class="form-input uppercase-input"
                           required
                           pattern="[A-Za-z\s]+"
                           title="Only letters are allowed">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           class="form-input"
                           required
                           pattern="[A-Za-z0-9@.]+" 
                           title="Only letters, numbers, @ and . are allowed">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text"
                           name="phone"
                           class="form-input uppercase-input"
                           required
                           pattern="[0-9]+"
                           maxlength="12"
                           title="Only numbers are allowed (max 12 digits)">
                </div>

                <div class="form-group">
                    <label>Donation Amount (RM)</label>
                    <input type="number"
                           name="amount"
                           class="form-input uppercase-input"
                           required
                           min="1">
                </div>
                
                <?php if($fundraiser->form_path): ?>

                    
                    <div class="form-group">
                        <label class="form-label">
                            Donation Form (Hardcopy)
                        </label>

                       <a href="<?php echo e(asset('storage/'.$fundraiser->form_path)); ?>"
                            target="_blank"
                            class="btn btn-outline-success btn-download-form">
                                Download Donation Form
                            </a>
                        <p class="text-muted" style="font-size:13px; margin-top:6px;">
                            Please download the form above, fill it manually, then upload the completed form below.
                        </p>
                    </div>

                    
                    <div class="form-group">
                        <label class="form-label">
                            Upload Completed Donation Form <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                            name="submitted_form"
                            class="form-input-file uppercase-input"
                            accept=".pdf,.jpg,.jpeg,.png"
                            required>

                        <p class="file-note">
                            Accepted formats: PDF, JPG, PNG (Max 10MB)
                        </p>
                    </div>

                <?php endif; ?>

                <!-- ⭐ NEW PAYMENT METHOD SECTION -->
                <div class="form-group">
                    <label>Payment Method</label>

                    <div class="payment-info-box" style="
                        background:#f8f9fa;
                        padding:15px;
                        border-left:4px solid var(--green, #2d5f3f);
                        border-radius:6px;
                        font-size:14px;
                        line-height:1.6;
                    ">
                        <p style="margin:0 0 8px 0; font-weight:600;">Please make payment via online bank transfer:</p>

                        <p style="margin:4px 0;">
                            <strong>Account Number:</strong> 02011010080008<br>
                            <strong>Bank Name:</strong> Bank Islam Malaysia Berhad<br>
                            <strong>Account Holder:</strong> Bendahari Negeri Kedah
                        </p>

                        <p style="margin:10px 0 0 0; font-size:13px; color:#555;">
                            Kindly upload your payment receipt below to complete the donation process.
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label>Upload Receipt</label>
                    <input type="file"
                           name="receipt"
                           class="form-input-file uppercase-input"
                           accept="image/*,application/pdf"
                           required>
                    <p class="file-note">Accepted formats: JPG, PNG, PDF (max 30MB)</p>
                </div>

                <button type="submit" class="donate-btn">
                    Donate Now
                </button>
            </form>

            <div class="donation-note">
                <p>Your donation will directly support this programme and is subject to admin approval.</p>
            </div>
        </div>
    </div>
</div>


<!-- Back to Top Button (optional, keep consistent UI) -->
<div class="back-to-top">
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="back-to-top-btn">
        <i class="fas fa-chevron-up"></i>
    </button>
</div>

<script>
// Real-time input validation (same logic as original)
document.addEventListener('DOMContentLoaded', function() {
    // Name validation - only letters and spaces
    const nameInput = document.querySelector('input[name="name"]');
    nameInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });

    // Email validation - only letters, numbers, @ and .
    const emailInput = document.querySelector('input[name="email"]');
    emailInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-z0-9@.]/g, '');
    });

    // Phone validation - only numbers, max 12 digits
    const phoneInput = document.querySelector('input[name="phone"]');
    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 12);
    });
});

// Form submission with SweetAlert (same logic, works with JSON response)
document.getElementById('donationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('.donate-btn');

    // Disable button during submission
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message || 'Thank you for your donation! Your contribution is currently pending and need admin approval. Our admin team will contact you once it has been approved.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2d5f3f',
                customClass: {
                    popup: 'donation-alert',
                    title: 'donation-alert-title',
                    content: 'donation-alert-text'
                }
            }).then(() => {
                this.reset();
                // Optionally redirect back to detail page
                window.location.href = "<?php echo e(route('fundraiser.detail', $fundraiser->id)); ?>";
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Something went wrong. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2d5f3f'
            });
            submitBtn.disabled = false;
            submitBtn.textContent = 'Donate Now';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#2d5f3f'
        });
        submitBtn.disabled = false;
        submitBtn.textContent = 'Donate Now';
    });
});
</script>
<style>
.btn-download-form {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 10px 18px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;

    color: #00542A;
    background-color: #F3F8F5;   /* soft green background */
    border: 1.5px solid #00542A;
    border-radius: 8px;

    transition: all 0.25s ease;
}

.btn-download-form::before {
    content: "⬇";
    font-size: 14px;
}

.btn-download-form:hover {
    background-color: #00542A;
    color: #ffffff;
    box-shadow: 0 6px 14px rgba(0, 84, 42, 0.25);
    transform: translateY(-1px);
}

</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/fundraiser-donate.blade.php ENDPATH**/ ?>