@extends('layouts.app')
@section('title', 'Participation Form')

@section('content')

@php
    $hasPackages = isset($packages) && $packages->filter(fn($p) => (float)$p->price > 0)->count() > 0;
    $hasPayments = isset($paymentMethods) && $paymentMethods->count() > 0;
    $hasCommercialFlow = $hasPackages && $hasPayments;
@endphp

<link rel="stylesheet" href="{{ asset('assets/css/public-participation.css') }}">

<div class="pp-wrap">

    {{-- PROGRESS INDICATOR --}}
    <div class="pp-progress">
        <div class="pp-progress-track">

            <div class="pp-progress-step active">
                <span>1</span>
                <label>Programme Details</label>
            </div>

            <div class="pp-progress-step">
                <span>2</span>
                <label>Company Details</label>
            </div>

            @if($hasCommercialFlow)
                <div class="pp-progress-step">
                    <span>3</span>
                    <label>Package Selection</label>
                </div>
            @endif

            <div class="pp-progress-step">
                <span>{{ $hasCommercialFlow ? 4 : 3 }}</span>
                <label>Participants</label>
            </div>

            @if($hasCommercialFlow)
                <div class="pp-progress-step">
                    <span>5</span>
                    <label>Payment</label>
                </div>

                <div class="pp-progress-step">
                    <span>6</span>
                    <label>Receipt</label>
                </div>
            @endif

        </div>
    </div>

    {{-- FORM VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="pp-alert pp-alert-error">
            <strong>Please check the following errors:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="pp-alert pp-alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('participation.public.submit', $programme->public_token) }}"
          enctype="multipart/form-data"
          id="participation-form">
        @csrf

        {{-- PROGRAM DETAILS (READ-ONLY) --}}
        <div class="pp-card">
            <h3 class="pp-card-title" data-section="Section 1">Programme Detail</h3>

            <div class="pp-grid-2">
                <div class="pp-field">
                    <div class="pp-label">Programme</div>
                    <input class="pp-input" value="{{ $programme->title }}" readonly>
                </div>
                <div class="pp-field">
                    <div class="pp-label">Venue</div>
                    <input class="pp-input" value="{{ $programme->venue ?? '-' }}" readonly>
                    <div class="pp-note">Event location</div>
                </div>
            </div>

            <div class="pp-grid-3" style="margin-top: 16px;">
                <div class="pp-field">
                    <div class="pp-label">Start Date</div>
                    <input class="pp-input" value="{{ optional($programme->start_date)->format('d/m/Y') }}" readonly>
                    <div class="pp-note">Programme commencement date</div>
                </div>
                <div class="pp-field">
                    <div class="pp-label">End Date</div>
                    <input class="pp-input" value="{{ optional($programme->end_date)->format('d/m/Y') }}" readonly>
                    <div class="pp-note">Programme conclusion date</div>
                </div>
                <div class="pp-field">
                    <div class="pp-label">Time</div>
                    @php
                        $timeDisplay = '-';
                        if ($programme->start_time) {
                            try {
                                $timeDisplay = \Carbon\Carbon::parse($programme->start_time)->format('h:i A');
                            } catch (\Exception $e) {
                                $timeDisplay = '-';
                            }
                        }
                        if ($programme->end_time) {
                            try {
                                $timeDisplay .= ' - ' . \Carbon\Carbon::parse($programme->end_time)->format('h:i A');
                            } catch (\Exception $e) {}
                        }
                    @endphp
                    <input class="pp-input" value="{{ $timeDisplay }}" readonly>
                    <div class="pp-note">Event duration</div>
                </div>
            </div>
        </div>

        {{-- COMPANY DETAILS --}}
        <div class="pp-card">
            <h3 class="pp-card-title" data-section="Section 2">
                Company Details <span style="color:#dc2626;font-size:12px;">* Required</span>
            </h3>

            <div class="pp-grid-3">
                <div class="pp-field">
                    <div class="pp-label required">Company Name</div>
                    <input class="pp-input uppercase-field"
                           name="company_name"
                           value="{{ old('company_name') }}"
                           placeholder="ENTER COMPANY NAME"
                           required>
                    <div class="pp-note">Uppercase letters, numbers, spaces, and . , & - ( ) ' only</div>
                </div>

                <div class="pp-field">
                    <div class="pp-label required">Officer Name</div>
                    <input class="pp-input uppercase-field"
                           name="officer_name"
                           value="{{ old('officer_name') }}"
                           placeholder="ENTER OFFICER'S FULL NAME"
                           required>
                    <div class="pp-note">Uppercase letters, spaces, and full stop only</div>
                </div>

                <div class="pp-field">
                    <div class="pp-label required">Phone Number</div>
                    <input class="pp-input"
                           name="phone_number"
                           value="{{ old('phone_number') }}"
                           placeholder="0123456789"
                           required
                           pattern="[0-9]*"
                           inputmode="numeric">
                    <div class="pp-note">Numbers only</div>
                </div>
            </div>

            <div class="pp-grid-2" style="margin-top: 16px;">
                <div class="pp-field">
                    <div class="pp-label required">Email Address</div>
                    <input class="pp-input"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="company@example.com">
                    <div class="pp-note">Valid email address (lowercase accepted)</div>
                </div>
            </div>
        </div>

        {{-- PACKAGE + QUANTITY (ONLY IF COMMERCIAL) --}}
        @if($hasCommercialFlow)
        <div class="pp-card">
            <h3 class="pp-card-title" data-section="Section 3">
                Package Selection <span style="color:#dc2626;font-size:12px;">* Required</span>
            </h3>

            <div class="pp-grid-2">
                <div class="pp-field">
                    <div class="pp-label required">Package Type</div>
                    <select class="pp-select" name="package_id" data-package-select required>
                        <option value="">-- Select package --</option>
                        @foreach($packages as $pkg)
                            @php
                                $peopleCount = $pkg->people_per_package ?? $pkg->package->people_per_package ?? 1;
                                $price = number_format((float)$pkg->price, 2);
                                $packageType = $pkg->package->package_type === 'multi_person' ? 'MULTI-PERSON' : 'ONE-PERSON';
                            @endphp
                            <option value="{{ $pkg->id }}"
                                    data-price="{{ $pkg->price }}"
                                    data-people="{{ $peopleCount }}"
                                    data-type="{{ $pkg->package->package_type }}"
                                    {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                {{ $packageType }} - {{ $pkg->package->name }} (RM {{ $price }})
                                @if($pkg->package->package_type === 'multi_person')
                                    - {{ $peopleCount }} pax per package
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="pp-note">Price is fixed by Secretariat</div>
                    @error('package_id')
                        <div class="pp-error-message" style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pp-field">
                    <div class="pp-label required">Quantity</div>
                    <div class="pp-input-group" style="display:flex;gap:8px;">
                        <button type="button" class="pp-btn-quantity" data-action="decrease"
                                style="background:#f3f4f6;border:2px solid #e5e7eb;border-radius:8px;width:40px;cursor:pointer;">-</button>
                        <input class="pp-input" type="number" name="quantity" min="1"
                               value="{{ old('quantity', 1) }}"
                               data-qty style="text-align:center;" required>
                        <button type="button" class="pp-btn-quantity" data-action="increase"
                                style="background:#f3f4f6;border:2px solid #e5e7eb;border-radius:8px;width:40px;cursor:pointer;">+</button>
                    </div>
                    <div class="pp-note">
                        Expected participants: <strong data-expected-count style="color:#00542A;">-</strong>
                    </div>
                    @error('quantity')
                        <div class="pp-error-message" style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pp-total">
                <div style="display:flex;flex-direction:column;gap:4px;">
                    <span style="font-size:14px;color:#6b7280;">Total Amount</span>
                    <span style="font-size:12px;color:#9ca3af;">Including all selected packages</span>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                    <strong data-total style="font-size:28px;">RM 0.00</strong>
                    <span style="font-size:12px;color:#9ca3af;" data-total-participants>0 participants</span>
                </div>
            </div>
        </div>
        @endif
        
{{-- PARTICIPANTS (DUAL MODE) --}}
<div class="pp-card">
    <h3 class="pp-card-title" data-section="Section {{ $hasCommercialFlow ? 4 : 3 }}">
        Participants <span style="color:#dc2626;font-size:12px;">* Required</span>
    </h3>

    @if($hasCommercialFlow)
        <div class="pp-note">
            Fill exactly based on package type and quantity. Each participant must have a name and position.
        </div>

        <div class="pp-divider"></div>

        <div data-participants-wrap>
            <div class="pp-participant-placeholder" style="text-align:center;padding:40px 20px;color:#9ca3af;">
                <div style="font-size:48px;margin-bottom:16px;">👥</div>
                <h4 style="margin:0 0 8px 0;color:#6b7280;">No participants yet</h4>
                <p style="margin:0;font-size:14px;">Select a package above to add participants</p>
            </div>
        </div>
    @else
        {{-- PARTICIPANT-ONLY MODE (SAME DESIGN AS COMMERCIAL) --}}
        <div class="pp-note">
            This programme does not require package selection or payment. Please add participant(s) below.
        </div>

        <div class="pp-divider"></div>

        <div data-participants-wrap id="participants-manual-wrap">
            {{-- Participant 1 (Default) --}}
            <div class="pp-participant-row" data-participant-item="1">
                <div>
                    <div class="pp-row-title">Participant 1</div>
                    <div class="pp-field">
                        <div class="pp-label required">Name</div>
                        <input class="pp-input uppercase-field" 
                               type="text" 
                               name="participants[0][name]" 
                               value="{{ old('participants.0.name') }}"
                               required 
                               placeholder="ENTER FULL NAME">
                    </div>
                </div>
                <div>
                    <div class="pp-row-title">&nbsp;</div>
                    <div class="pp-field">
                        <div class="pp-label required">Position</div>
                        <input class="pp-input uppercase-field" 
                               type="text" 
                               name="participants[0][position]" 
                               value="{{ old('participants.0.position') }}"
                               required 
                               placeholder="ENTER POSITION">
                    </div>
                </div>
                <div style="display:flex;align-items:flex-end;padding-bottom:8px;">
                    <button type="button" 
                            class="pp-btn-remove-participant" 
                            onclick="removeManualParticipant(this)"
                            style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;padding:8px 14px;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;transition:all 0.2s ease;"
                            title="Remove participant">×</button>
                </div>
            </div>

            {{-- Old participants if validation fails --}}
            @if(old('participants'))
                @foreach(old('participants') as $index => $participant)
                    @if($index > 0)
                    <div class="pp-participant-row" data-participant-item="1">
                        <div>
                            <div class="pp-row-title">Participant {{ $index + 1 }}</div>
                            <div class="pp-field">
                                <div class="pp-label required">Name</div>
                                <input class="pp-input uppercase-field" 
                                       type="text" 
                                       name="participants[{{ $index }}][name]" 
                                       value="{{ $participant['name'] ?? '' }}"
                                       required 
                                       placeholder="ENTER FULL NAME">
                            </div>
                        </div>
                        <div>
                            <div class="pp-row-title">&nbsp;</div>
                            <div class="pp-field">
                                <div class="pp-label required">Position</div>
                                <input class="pp-input uppercase-field" 
                                       type="text" 
                                       name="participants[{{ $index }}][position]" 
                                       value="{{ $participant['position'] ?? '' }}"
                                       required 
                                       placeholder="ENTER POSITION">
                            </div>
                        </div>
                        <div style="display:flex;align-items:flex-end;padding-bottom:8px;">
                            <button type="button" 
                                    class="pp-btn-remove-participant" 
                                    onclick="removeManualParticipant(this)"
                                    style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;padding:8px 14px;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;transition:all 0.2s ease;"
                                    title="Remove participant">×</button>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif
        </div>

        <button type="button" 
                class="pp-btn pp-btn-outline" 
                onclick="addManualParticipant()"
                style="margin-top:16px;width:100%;display:flex;align-items:center;justify-content:center;gap:8px;">
            <span style="font-size:18px;font-weight:600;">+</span>
            <span>Add Another Participant</span>
        </button>
    @endif
</div>

{{-- ======================
   PARTICIPATION FORM (HARDCOPY)
====================== --}}
@if($programme->upload_form_path)
<div class="pf-card">
    <h3 class="pf-card-title">
        Participation Form
        <span class="pf-required">* Required</span>
    </h3>

    {{-- Download Link --}}
    <div class="pf-field">
        <a href="{{ asset('storage/'.$programme->upload_form_path) }}"
           class="pf-download-btn"
           target="_blank">
            ⬇ Download Participation Form
        </a>

        <p class="pf-note">
            Please download the form above, fill it manually, then upload the completed form below.
        </p>
    </div>

    {{-- Upload Box --}}
    <div class="pf-field">
        <label class="pf-label">
            Upload Completed Form
            <span class="pf-required">* Required</span>
        </label>

        <div class="pf-upload-box"
             onclick="document.getElementById('supportingDocumentInput').click()">
            <span class="pf-upload-icon">📁</span>
            <span class="pf-upload-text">Upload Form</span>
        </div>

        <input type="file"
               id="supportingDocumentInput"
               name="supporting_document"
               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
               required
               hidden>

        {{-- Selected file preview --}}
        <div id="pfUploadPreview" class="pf-upload-preview" style="display:none;">  
            <span id="pfUploadFileName"></span>
            <button type="button"
                    class="pf-upload-remove"
                    onclick="clearSupportingDoc()">✕</button>
        </div>

        <div class="pf-note">
            Upload PDF, DOC, DOCX, JPG, or PNG (max 30MB)
        </div>
    </div>
</div>
@endif



        {{-- PAYMENT METHOD + RECEIPT (ONLY IF COMMERCIAL) --}}
        @if($hasCommercialFlow)

        {{-- PAYMENT METHOD --}}
        <div class="pp-card">
            <h3 class="pp-card-title" data-section="Section 5">
                Payment Method <span style="color:#dc2626;font-size:12px;">* Required</span>
            </h3>

            <div class="pp-field">
                <div class="pp-label required">Select Payment Method</div>
                <select class="pp-select" name="payment_method_id" data-payment-select required>
                    <option value="">-- Select payment method --</option>
                    @if($programme->qr_path)
                        <option value="qr_payment" data-type="qr">QR Code Payment (Instant)</option>
                    @endif
                    @foreach($paymentMethods as $pm)
                        @php
                            $accountNumber = $pm->account_number ?? $pm->paymentMethod->account_number ?? '';
                            $accountName = $pm->account_name ?? $pm->paymentMethod->account_name ?? '';
                            $bankName = $pm->paymentMethod->bank ?? '';
                        @endphp
                        <option value="{{ $pm->id }}"
                                data-bank="{{ $bankName }}"
                                data-account="{{ $accountNumber }}"
                                data-name="{{ $accountName }}"
                                data-type="bank"
                                {{ old('payment_method_id') == $pm->id ? 'selected' : '' }}>
                            {{ $bankName }} - {{ $accountName }} ({{ $accountNumber }})
                        </option>
                    @endforeach
                </select>
                <div class="pp-note">Choose QR for instant payment or select a bank account for manual transfer</div>
                @error('payment_method_id')
                    <div class="pp-error-message" style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="pp-divider"></div>

            {{-- QR CODE SECTION (HIDDEN BY DEFAULT) --}}
            @if($programme->qr_path)
                <div data-qr-info style="display:none;animation:fadeIn 0.3s ease-out;">
                    <h3 class="pp-card-title" style="color:#00542A;margin-bottom:16px;">
                        <span style="font-size:20px;margin-right:8px;">📱</span>
                        Quick Payment via QR
                    </h3>
                    <div class="pp-qr-box" style="background:linear-gradient(135deg,#f0fdf4 0%,#ecfdf5 100%);border:2px solid #bbf7d0;">
                        <img class="pp-qr-img" src="{{ asset('storage/'.$programme->qr_path) }}" alt="Payment QR Code"
                             style="box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                        <div style="max-width:350px;text-align:center;"></div>
                    </div>
                </div>
            @endif

            {{-- Payment Info Box (FOR BANK TRANSFER) --}}
            <div data-payment-info style="display:none;animation:fadeIn 0.3s ease-out;">
                <h3 class="pp-card-title" style="margin-bottom:16px;color:#00542A;">Payment Details</h3>
                <div class="pp-grid-3">
                    <div class="pp-field">
                        <div class="pp-label">Bank Name</div>
                        <input class="pp-input" readonly data-payment-bank style="background:#f0fdf4;border-color:#bbf7d0;">
                        <div class="pp-note">Financial institution</div>
                    </div>
                    <div class="pp-field">
                        <div class="pp-label">Account Number</div>
                        <input class="pp-input" readonly data-payment-acc style="background:#f0fdf4;border-color:#bbf7d0;">
                        <div class="pp-note">For bank transfer</div>
                    </div>
                    <div class="pp-field">
                        <div class="pp-label">Account Name</div>
                        <input class="pp-input" readonly data-payment-name style="background:#f0fdf4;border-color:#bbf7d0;">
                        <div class="pp-note">Beneficiary name</div>
                    </div>
                </div>

                <div class="pp-alert" style="background:#f0fdf4;border-color:#bbf7d0;color:#166534;margin-top:16px;">
                    <strong>💡 Payment Instructions:</strong>
                    <ul style="margin:8px 0 0 0;padding-left:20px;font-size:13px;">
                        <li>Transfer the exact total amount shown above</li>
                        <li>Use your company name as reference</li>
                        <li>Upload proof of payment below</li>
                        <li>Payment must be completed within 24 hours</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- RECEIPT UPLOAD --}}
        <div class="pp-card">
            <h3 class="pp-card-title" data-section="Section 6">
                Payment Proof <span style="color:#dc2626;font-size:12px;">* Required</span>
            </h3>

            <div class="pp-field">
                <div class="pp-label required">Upload Payment Receipt</div>
                <div class="pp-file-upload"
                     style="border:2px dashed #d1d5db;border-radius:12px;padding:32px;text-align:center;cursor:pointer;transition:all 0.3s ease;"
                     onclick="document.getElementById('receipt-input').click()"
                     onmouseover="this.style.borderColor='#00542A';this.style.backgroundColor='#f8fafc'"
                     onmouseout="this.style.borderColor='#d1d5db';this.style.backgroundColor='transparent'">
                    <div style="font-size:48px;margin-bottom:16px;color:#9ca3af;">📎</div>
                    <h4 style="margin:0 0 8px 0;color:#374151;">Click to upload receipt</h4>
                    <p style="margin:0;color:#6b7280;font-size:14px;">
                        Max file size: {{ $programme->receipt_max_mb ?? 20 }} MB
                        <br>
                        Supported: JPG, PNG, PDF
                    </p>
                    <input id="receipt-input"
                           class="pp-input"
                           type="file"
                           name="receipt"
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                           style="display:none;"
                           onchange="updateFileName(this)"
                           required>
                    <div id="file-name" style="margin-top:12px;font-size:13px;color:#00542A;"></div>
                </div>
                <div class="pp-note">Upload clear image/PDF of your bank transfer receipt</div>
                @error('receipt')
                    <div class="pp-error-message" style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="pp-alert" style="background:#fffbeb;border-color:#fde68a;color:#92400e;margin-top:20px;">
                <strong>⚠️ Important Notes:</strong>
                <ul style="margin:8px 0 0 0;padding-left:20px;font-size:13px;">
                    <li>Registration is confirmed only after payment verification</li>
                    <li>Keep your receipt for reference</li>
                    <li>Contact secretariat if payment fails</li>
                    <li>No refunds after payment confirmation</li>
                </ul>
            </div>
        </div>

        @endif

        {{-- FORM ACTIONS --}}
        <div class="pp-actions">
            <button class="pp-btn pp-btn-primary" type="submit" id="submit-btn">
                <span>SUBMIT REGISTRATION</span>
                <div class="pp-loading" style="display:none;"></div>
            </button>
            <a class="pp-btn pp-btn-outline" href="/">
                ← BACK TO HOME
            </a>
        </div>

        {{-- SUMMARY MODAL --}}
        <div class="pp-summary-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
            <div style="background:white;border-radius:16px;padding:24px;max-width:500px;width:90%;max-height:80vh;overflow-y:auto;">
                <h3 style="margin:0 0 16px 0;color:#00542A;">Registration Summary</h3>
                <div id="summary-content"></div>
                <div style="display:flex;gap:12px;margin-top:24px;">
                    <button class="pp-btn pp-btn-primary" onclick="submitForm()" style="flex:1;">CONFIRM & SUBMIT</button>
                    <button class="pp-btn pp-btn-outline" onclick="closeSummary()" style="flex:1;">EDIT DETAILS</button>
                </div>
            </div>
        </div>

    </form>
</div>
@php
    $jsProgramme = [
        'title' => $programme->title,
        'date' => optional($programme->start_date)->format('d/m/Y'),
        'max_file_size' => $programme->receipt_max_mb ?? 20,
        'hasCommercialFlow' => $hasCommercialFlow,
    ];
@endphp

<script>
    window.__PACKAGE_MAP__ = @json($packageData ?? []);
    window.__PAYMENT_MAP__ = @json($paymentData ?? []);
    window.__PROGRAMME__ = @json($jsProgramme);
</script>


<script src="{{ asset('assets/js/public-participation.js') }}"></script>

<script>
    let participantIndex = 1; 
document.addEventListener('DOMContentLoaded', function() {

    // Payment method selection handler (only if commercial and element exists)
    const paymentSelect = document.querySelector('[data-payment-select]');
    const qrInfo = document.querySelector('[data-qr-info]');
    const paymentInfo = document.querySelector('[data-payment-info]');

    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const paymentType = selectedOption.getAttribute('data-type');

            if (qrInfo) qrInfo.style.display = 'none';
            if (paymentInfo) paymentInfo.style.display = 'none';

            if (paymentType === 'qr') {
                if (qrInfo) qrInfo.style.display = 'block';
            } else if (paymentType === 'bank') {
                if (paymentInfo) {
                    paymentInfo.style.display = 'block';

                    const bank = selectedOption.getAttribute('data-bank');
                    const account = selectedOption.getAttribute('data-account');
                    const name = selectedOption.getAttribute('data-name');

                    const bankInput = document.querySelector('[data-payment-bank]');
                    const accInput = document.querySelector('[data-payment-acc]');
                    const nameInput = document.querySelector('[data-payment-name]');

                    if (bankInput) bankInput.value = bank || '';
                    if (accInput) accInput.value = account || '';
                    if (nameInput) nameInput.value = name || '';
                }
            }
        });
    }

    // Convert to uppercase on input (real-time)
    const uppercaseFields = document.querySelectorAll('.uppercase-field');
    uppercaseFields.forEach(field => {
        field.addEventListener('input', function() {
            const cursorPos = this.selectionStart;
            this.value = (this.value || '').toUpperCase();
            this.setSelectionRange(cursorPos, cursorPos);
        });
    });

    // Phone number validation (numbers only)
    const phoneInput = document.querySelector('[name="phone_number"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Officer name validation (letters, spaces, and full stop only)
    const officerInput = document.querySelector('[name="officer_name"]');
    if (officerInput) {
        officerInput.addEventListener('input', function() {
            const cursorPos = this.selectionStart;
            this.value = this.value.replace(/[^A-Z\s.]/gi, '').toUpperCase();
            this.setSelectionRange(cursorPos, cursorPos);
        });
    }

        increaseBtn.addEventListener('click', () => {
            const current = parseInt(quantityInput.value) || 1;
            quantityInput.value = current + 1;
            quantityInput.dispatchEvent(new Event('input'));
        });
    }

    // Submit button loading
    const form = document.getElementById('participation-form');
    const submitBtn = document.getElementById('submit-btn');
    if (form) {
        form.addEventListener('submit', function() {
            if (submitBtn) {
                submitBtn.disabled = true;
                const span = submitBtn.querySelector('span');
                const loading = submitBtn.querySelector('.pp-loading');
                if (span) span.textContent = 'PROCESSING...';
                if (loading) loading.style.display = 'block';
            }
        });
    }
});

function addParticipantRow() {
    const tableBody = document.querySelector('#participantTable tbody');
    if (!tableBody) return;

    const row = document.createElement('tr');

    row.innerHTML = `
        <td style="padding:10px;">
            <input class="pp-input uppercase-field"
                   name="participants[${participantIndex}][name]"
                   required
                   placeholder="ENTER NAME">
        </td>
        <td style="padding:10px;">
            <input class="pp-input uppercase-field"
                   name="participants[${participantIndex}][position]"
                   required
                   placeholder="ENTER POSITION">
        </td>
        <td style="padding:10px;text-align:center;">
            <button type="button"
                    class="pp-btn pp-btn-outline"
                    onclick="removeParticipantRow(this)">
                ×
            </button>
        </td>
    `;

    tableBody.appendChild(row);
    participantIndex++;
}


function removeParticipantRow(btn) {
    const tbody = document.querySelector('#participantTable tbody');
    if (!tbody) return;

    // keep at least 1 row
    if (tbody.children.length <= 1) return;
    btn.closest('tr').remove();
}

// File name display
function updateFileName(input) {
    const fileNameDiv = document.getElementById('file-name');
    if (!fileNameDiv) return;

    if (input.files.length > 0) {
        const file = input.files[0];
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        fileNameDiv.innerHTML = `
            <div style="background:#f0fdf4;padding:8px 12px;border-radius:8px;border:1px solid #bbf7d0;">
                ✓ Selected: <strong>${file.name}</strong> (${fileSize} MB)
            </div>
        `;
    } else {
        fileNameDiv.innerHTML = '';
    }
}

function closeSummary() {
    const modal = document.querySelector('.pp-summary-modal');
    if (modal) modal.style.display = 'none';
}

function submitForm() {
    const form = document.getElementById('participation-form');
    if (form) form.submit();
}
</script>

<style>
.pp-error-message { animation: fadeIn 0.3s ease-out; }

.pp-btn-quantity:hover {
    background: #e5e7eb !important;
    border-color: #d1d5db !important;
}
.pp-btn-quantity:active { transform: scale(0.95); }

/* Force uppercase styling */
.uppercase-field { text-transform: uppercase; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media print {
    .pp-progress,
    .pp-actions,
    .pp-btn-outline { display: none !important; }

    .pp-card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }
}
</style>

<script>
document.getElementById('supportingDocumentInput')
    .addEventListener('change', function () {
        if (!this.files.length) return;

        document.getElementById('pfUploadFileName').textContent =
            this.files[0].name;

        document.getElementById('pfUploadPreview').style.display = 'flex';
    });

function clearSupportingDoc() {
    const input = document.getElementById('supportingDocumentInput');
    input.value = '';
    document.getElementById('pfUploadPreview').style.display = 'none';
}
</script>



@endsection
