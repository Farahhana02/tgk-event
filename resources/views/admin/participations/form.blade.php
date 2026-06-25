@extends('admin.layouts.admin-template')
@section('title', 'Participation Form')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/admin-participations-form.css') }}">

<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">{{ strtoupper($programme->title) }}</div>
        <div class="breadcrumb-path">
            <a href="{{ route('admin.index') }}">
                <img src="{{ asset('assets/icons/Home.png') }}" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <a href="{{ route('admin.participations.index') }}" class="breadcrumb-link">PARTICIPATION</a>
            <span>/</span>
            <a href="{{ route('admin.participations.info', $programme->id) }}" class="breadcrumb-link">INFO</a>
            <span>/</span>
            <span class="breadcrumb-current">PARTICIPATION FORM</span>
        </div>
    </div>
</div>

<div class="paf-wrap">

    {{-- PROGRAM DETAILS --}}
    <div class="paf-card">
        <h3 class="paf-card-title">Program Details</h3>
        <div class="paf-grid-2">
            <div class="paf-field">
                <div class="paf-label">Programme</div>
                <input class="paf-input" value="{{ $programme->title }}" readonly>
            </div>
            <div class="paf-field">
                <div class="paf-label">Venue</div>
                <input class="paf-input" value="{{ $programme->venue ?? '-' }}" readonly>
            </div>
        </div>
        <div class="paf-grid-3" style="margin-top:12px;">
            <div class="paf-field">
                <div class="paf-label">Start Date</div>
                <input class="paf-input" value="{{ optional($programme->start_date)->format('d/m/Y') }}" readonly>
            </div>
            <div class="paf-field">
                <div class="paf-label">End Date</div>
                <input class="paf-input" value="{{ optional($programme->end_date)->format('d/m/Y') }}" readonly>
            </div>
            <div class="paf-field">
                <div class="paf-label">Time</div>
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
                        } catch (\Exception $e) {
                            // Keep existing time display
                        }
                    }
                @endphp
                <input class="paf-input" value="{{ $timeDisplay }}" readonly>
            </div>
        </div>
    </div>

    {{-- =====================================================
         PACKAGE SELECTION - HYBRID APPROACH
    ===================================================== --}}
    <div class="paf-card">
        <h3 class="paf-card-title">Package Selection</h3>

{{-- ✅ OPTIONAL: SELECT FROM PREVIOUS PROGRAMMES --}}
@if($allExistingPackages->isNotEmpty())
    <div class="paf-field" style="margin-bottom:20px;">
        <div class="paf-label" style="color:#C08329; font-weight:600;">
            📋 Quick Select from Previous Programmes (Optional)
        </div>
        <select id="existingPackageSelect" class="paf-select" 
                onchange="fillPackageFromExisting(this)">
            <option value="">— Select to auto-fill form below —</option>
            @foreach($allExistingPackages as $pkg)
                @if(!$pkg->is_used_in_current)
                    <option value="{{ $pkg->id }}" 
                            data-name="{{ $pkg->name }}"
                            data-type="{{ $pkg->package_type }}"
                            data-price="{{ $pkg->price }}"
                            data-people="{{ $pkg->people_per_package }}"
                            data-description="{{ $pkg->description }}">
                        {{ $pkg->name }} 
                        ({{ strtoupper(str_replace('_', ' ', $pkg->package_type)) }})
                        - RM {{ number_format($pkg->price, 2) }}
                        @if($pkg->total_usage > 0)
                            <small style="color:#6B7280;">(Used {{ $pkg->total_usage }} time{{ $pkg->total_usage > 1 ? 's' : '' }})</small>
                        @endif
                    </option>
                @endif
            @endforeach
        </select>
        <div class="paf-note">
            <small style="color:#6B7280;">
                ✅ Packages already added to this programme are hidden.
                <br>Selecting will auto-fill the form below. You can modify values before saving.
                <br><strong>Note:</strong> Only changed values will be stored as programme-specific overrides.
            </small>
        </div>
    </div>
    <div class="paf-divider"></div>
@endif

{{-- ✅ PACKAGE FORM (ADD/EDIT) --}}
<div class="paf-form-section">
    <h4 class="paf-form-title">
        @if(isset($editingPackage))
            ✏️ Edit Package
        @else
            ➕ Add New Package
        @endif
    </h4>
    
    <form id="packageForm" method="POST" 
          action="{{ route('admin.participations.packages.store.new', $programme->id) }}">
        @csrf
        
        <div class="paf-grid-2">
            <div class="paf-field">
                <div class="paf-label">Package Name <span class="required">*</span></div>
                <input type="text" id="package_label" name="label" class="paf-input" 
                       placeholder="e.g. VIP, Student, Corporate" required>
            </div>
            <div class="paf-field">
                <div class="paf-label">Package Type <span class="required">*</span></div>
                <select id="package_type" name="package_type" class="paf-select" required 
                        onchange="toggleMultiPersonField()">
                    <option value="">— Select Type —</option>
                    <option value="one_person">ONE PERSON</option>
                    <option value="multi_person">MULTI PERSON</option>
                </select>
            </div>
        </div>
        
        <div class="paf-grid-2" style="margin-top: 16px;">
            <div class="paf-field">
                <div class="paf-label">Price (RM) <span class="required">*</span></div>
                <input type="number" id="package_price" name="price" class="paf-input" 
                       step="0.01" min="0" placeholder="e.g. 250.00" required>
            </div>
            
            <div class="paf-field" id="multiPersonField" style="display:none;">
                <div class="paf-label">People per Package <span class="required">*</span></div>
                <input type="number" id="people_per_package" name="people_per_package" 
                       class="paf-input" min="1" placeholder="e.g. 5">
            </div>
        </div>
        
        {{-- Description on its own line but smaller --}}
        <div class="paf-field" style="margin-top: 16px;">
            <div class="paf-label">Description (Optional)</div>
            <textarea id="package_description" name="description" class="paf-textarea" 
                      placeholder="e.g. includes meals, materials, certificate" 
                      rows="2"></textarea>
        </div>
        
        {{-- Save and Clear buttons on separate line --}}
        <div class="paf-form-actions">
            <button type="submit" class="paf-btn paf-btn-green" style= "font-weight:600;">
                💾 SAVE PACKAGE
            </button>
            <button type="button" class="paf-btn paf-btn-outline" onclick="clearPackageForm()">
                🗑️ CLEAR
            </button>
        </div>
    </form>
</div>

{{-- ✅ SAVED PACKAGES LIST --}}
@if($selectedProgrammePackages->isNotEmpty())
    <div class="paf-divider" style="margin:24px 0;"></div>

    <div class="paf-note" style="margin-bottom:12px; font-weight:600; color:#00542A;">
        ✅ Saved Packages for {{ $programme->title }}:
    </div>

    <div class="paf-list">
        @foreach($selectedProgrammePackages as $progPkg)
            <div class="paf-item" style="{{ $progPkg->is_override ? 'border-left: 3px solid #C08329; background-color: #FEF3C7; padding-left: 12px;' : '' }}">

                <div class="paf-item-left">
                    <div class="paf-item-title">
                        {{ $progPkg->name }}

                        @if($progPkg->is_override)
                            <span style="background:#C08329; color:white; padding:2px 8px; border-radius:4px; font-size:11px; margin-left:8px;">
                                OVERRIDE
                            </span>
                        @endif
                    </div>

                    <div class="paf-item-sub">
                        <div><strong>Type:</strong> {{ strtoupper(str_replace('_', ' ', $progPkg->package_type)) }}</div>

                        <div>
                            <strong>Price:</strong> RM {{ number_format($progPkg->price, 2) }}
                        </div>

                        @if($progPkg->people_per_package)
                            <div>
                                <strong>People per Package:</strong> {{ $progPkg->people_per_package }}
                            </div>
                        @endif

                        @if($progPkg->description)
                            <div>
                                <strong>Description:</strong> {{ $progPkg->description }}
                            </div>
                        @endif
                    </div>
                </div>

                <div style="display:flex; gap:8px;">
                    <button type="button" class="paf-btn paf-btn-outline"
                        onclick="editPackage({{ $progPkg->id }}, '{{ addslashes($progPkg->name) }}', '{{ $progPkg->package_type }}', {{ $progPkg->price }}, '{{ addslashes($progPkg->description ?? '') }}', {{ $progPkg->people_per_package ?? 'null' }}, {{ $progPkg->is_override ? 'true' : 'false' }})">
                        ✏️ EDIT
                    </button>

                    <form method="POST"
                        action="{{ route('admin.participations.packages.destroy', [$programme->id, $progPkg->id]) }}"
                        onsubmit="return confirmPackageDelete(this, '{{ addslashes($progPkg->name) }}')">
                        @csrf
                        @method('DELETE')
                        <button class="paf-btn paf-btn-danger" type="submit">🗑️ DELETE</button>
                    </form>
                </div>

            </div>
        @endforeach
    </div>

@else
    <div class="paf-note" style="margin-top:20px;">
        ⚠️ No packages saved yet for this programme.
    </div>
@endif

    {{-- =====================================================
         PAYMENT METHOD SELECTION - HYBRID APPROACH
    ===================================================== --}}
    <div class="paf-card">
        <h3 class="paf-card-title">Payment Method Selection</h3>

{{-- ✅ OPTIONAL: SELECT FROM PREVIOUS PROGRAMMES --}}
@if($allExistingPaymentMethods->isNotEmpty())
    <div class="paf-field" style="margin-bottom:20px;">
        <div class="paf-label" style="color:#C08329; font-weight:600;">
            📋 Quick Select from Previous Programmes (Optional)
        </div>
        <select id="existingPaymentSelect" class="paf-select" 
                onchange="fillPaymentFromExisting(this)">
            <option value="">— Select to auto-fill form below —</option>
            @foreach($allExistingPaymentMethods as $pm)
                @if(!$pm->is_used_in_current)
                    <option value="{{ $pm->id }}"
                            data-bank="{{ $pm->bank }}"
                            data-account-name="{{ $pm->account_name }}"
                            data-account-number="{{ $pm->account_number }}">
                        {{ $pm->bank }} - {{ $pm->account_name }} ({{ $pm->account_number }})
                        @if($pm->total_usage > 0)
                            <small style="color:#6B7280;">(Used {{ $pm->total_usage }} time{{ $pm->total_usage > 1 ? 's' : '' }})</small>
                        @endif
                    </option>
                @endif
            @endforeach
        </select>
        <div class="paf-note">
            <small style="color:#6B7280;">
                ✅ Payment methods already added to this programme are hidden.
                <br>Selecting will auto-fill the form below. You can modify values before saving.
            </small>
        </div>
    </div>
    <div class="paf-divider"></div>
@endif

{{-- ✅ PAYMENT METHOD FORM (ADD/EDIT) --}}
<div class="paf-form-section">
    <h4 class="paf-form-title">
        @if(isset($editingPayment))
            ✏️ Edit Payment Method
        @else
            ➕ Add New Payment Method
        @endif
    </h4>
    
<form id="paymentForm" method="POST" enctype="multipart/form-data"
      action="{{ route('admin.participations.payment_methods.store.new', $programme->id) }}">
        @csrf
        
        <div class="paf-grid-3">
            <div class="paf-field">
                <div class="paf-label">Bank <span class="required">*</span></div>
                <input type="text" id="payment_bank" name="bank" class="paf-input" 
                       placeholder="e.g. Maybank" required>
            </div>
            <div class="paf-field">
                <div class="paf-label">Account Name <span class="required">*</span></div>
                <input type="text" id="payment_account_name" name="account_name" class="paf-input" 
                       placeholder="e.g. ABC Sdn Bhd" required>
            </div>
            <div class="paf-field">
                <div class="paf-label">Account Number <span class="required">*</span></div>
                <input type="text" id="payment_account_number" name="account_number" class="paf-input" 
                       placeholder="e.g. 1234567890" required>
            </div>
        </div>

        <div class="paf-form-actions">
            <button type="submit" class="paf-btn paf-btn-green" style= "font-weight:600;">
                💾 SAVE PAYMENT METHOD
            </button>
            <button type="button" class="paf-btn paf-btn-outline" onclick="clearPaymentForm()">
                🗑️ CLEAR
            </button>
        </div>
    </form>
</div>

{{-- ✅ SAVED PAYMENT METHODS LIST --}}
@if($selectedPaymentMethods->isNotEmpty())
    <div class="paf-divider" style="margin:24px 0;"></div>

    <div class="paf-note" style="margin-bottom:12px; font-weight:600; color:#00542A;">
        ✅ Saved Payment Methods for {{ $programme->title }}:
    </div>

    <div class="paf-list">
        @foreach($selectedPaymentMethods as $progPM)
            <div class="paf-item" style="{{ $progPM->is_override ? 'border-left: 3px solid #C08329; background-color: #FEF3C7; padding-left: 12px;' : '' }}">
                <div class="paf-item-left">
                    <div class="paf-item-title">
                        {{ $progPM->bank }}
                        @if($progPM->is_override)
                            <span style="background:#C08329; color:white; padding:2px 8px; border-radius:4px; font-size:11px; margin-left:8px;">
                                OVERRIDE
                            </span>
                        @endif
                    </div>
                    <div class="paf-item-sub paf-payment-details">
                        {{-- ACCOUNT NAME WITH OVERRIDE INDICATOR --}}
                        <div>
                            <strong>Account Name:</strong> {{ $progPM->account_name }}
                            @if($progPM->is_override && isset($progPM->master_data['default_account_name']))
                                <small style="color:#6B7280; margin-left: 8px;">
                                    (Master: {{ $progPM->master_data['default_account_name'] }})
                                </small>
                            @endif
                        </div>
                        
                        {{-- ACCOUNT NUMBER WITH OVERRIDE INDICATOR --}}
                        <div>
                            <strong>Account Number:</strong> {{ $progPM->account_number }}
                            @if($progPM->is_override && isset($progPM->master_data['default_account_number']))
                                <small style="color:#6B7280; margin-left: 8px;">
                                    (Master: {{ $progPM->master_data['default_account_number'] }})
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:8px;">
                    <button type="button" class="paf-btn paf-btn-outline"
                            onclick="editPayment({{ $progPM->id }}, '{{ addslashes($progPM->bank) }}', '{{ addslashes($progPM->account_name) }}', '{{ $progPM->account_number }}', {{ $progPM->is_override ? 'true' : 'false' }})">
                        ✏️ EDIT
                    </button>

                    <form method="POST"
                        action="{{ route('admin.participations.payment_methods.destroy', [$programme->id, $progPM->id]) }}"
                        onsubmit="return confirmPaymentDelete(this, '{{ addslashes($progPM->bank) }}')">
                        @csrf
                        @method('DELETE')
                        <button class="paf-btn paf-btn-danger" type="submit">🗑️ DELETE</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="paf-note" style="margin-top:20px;">
        ⚠️ No payment methods saved yet for this programme.
    </div>
@endif
    </div>
{{-- PROGRAMME QR CODE (FIXED VERSION) --}}
<div class="paf-card">
    <h3 class="paf-card-title">QR Code (Optional)</h3>

    <form method="POST"
          action="{{ route('admin.participations.form.save', $programme->id) }}"
          enctype="multipart/form-data"
          id="qrUploadForm">
        @csrf

        <div class="paf-field">
            @if($programme->qr_path)
                <div class="qr-box">
                    <div class="qr-row">
                        <img src="{{ asset('storage/'.$programme->qr_path) }}" class="qr-thumb">

                        <div class="qr-info">
                            <div class="qr-title">Current QR Code</div>
                            <div class="qr-filename">{{ basename($programme->qr_path) }}</div>
                        </div>

                        <div class="qr-actions">
                            <button type="button"
                                    onclick="previewQR('{{ asset('storage/'.$programme->qr_path) }}')"
                                    class="paf-btn paf-btn-outline">
                                👁️ Preview
                            </button>

                            <button type="button"
                                    onclick="confirmDeleteQR()"
                                    class="paf-btn paf-btn-danger">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- FILE INPUT --}}
            <input type="file"
                   name="qr"
                   id="qrFileInput"
                   accept="image/jpeg,image/jpg,image/png,image/webp"
                   hidden
                   onchange="previewSelectedQR(this)">

            {{-- UPLOAD BUTTON --}}
            <button type="button"
                    onclick="document.getElementById('qrFileInput').click()"
                    class="qr-upload-btn">
                📁 {{ $programme->qr_path ? 'REPLACE QR CODE' : 'UPLOAD QR CODE' }}
            </button>

            {{-- PREVIEW OF NEW SELECTION --}}
            <div id="newQRPreview" class="qr-new-preview" style="display:none;">
                <div class="qr-row">
                    <img id="newQRImage"
                         style="width:60px;height:60px;object-fit:contain;
                                border:1px solid #E5E7EB;border-radius:6px;
                                background:#fff;padding:4px;">
                    <div class="qr-info">
                        <div id="newQRFileName" style="font-size:13px;font-weight:500;"></div>
                        <div class="qr-hint">Ready to upload</div>
                    </div>
                    <button type="button"
                            onclick="clearNewQR()"
                            style="font-size:20px;border:none;background:none;cursor:pointer;color:#DC2626;">
                        ✕
                    </button>
                </div>
            </div>

            {{-- SAVE BUTTON (only show when file is selected) --}}
            <div id="qrSaveButton" class="paf-actions-row" style="margin-top:10px; display:none;">
                <button type="submit" class="paf-btn paf-btn-green">
                    💾 SAVE QR CODE
                </button>
                <button type="button" onclick="clearNewQR()" class="paf-btn paf-btn-outline">
                    CANCEL
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Hidden form for QR deletion --}}
<form id="deleteQRForm"
      action="{{ route('admin.participations.qr.delete', $programme->id) }}"
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>

<!-- UPLOAD FORM SECTION - Place this BETWEEN QR CODE and SETTINGS sections -->

<div class="paf-card">
    <h3 class="paf-card-title">Upload Form (Optional)</h3>

    <form method="POST"
          action="{{ route('admin.participations.form.save', $programme->id) }}"
          enctype="multipart/form-data"
          id="uploadFormUpload">
        @csrf

        <div class="paf-field">
            <div class="paf-label">Participation Form File</div>

            <!-- CURRENT FILE DISPLAY -->
            @if($programme->upload_form_path)
                <div class="upload-form-box">
                    <div class="form-row">
                        <div class="form-info">
                            <div class="form-title">📋 Current Upload Form</div>
                            <div class="form-filename">{{ basename($programme->upload_form_path) }}</div>
                        </div>

                        <div class="form-actions">
                            <button type="button"
                                    onclick="previewForm('{{ asset('storage/'.$programme->upload_form_path) }}')"
                                    class="paf-btn paf-btn-outline">
                                👁️ Preview
                            </button>

                            <button type="button"
                                    onclick="confirmDeleteForm()"
                                    class="paf-btn paf-btn-danger">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- FILE INPUT -->
            <input type="file"
                   name="upload_form"
                   id="uploadFormFileInput"
                   accept=".pdf,.doc,.docx"
                   hidden
                   onchange="previewSelectedForm(this)">

            <!-- UPLOAD BUTTON -->
            <button type="button"
                    onclick="document.getElementById('uploadFormFileInput').click()"
                    class="upload-form-btn">
                📁 {{ $programme->upload_form_path ? 'REPLACE FORM' : 'UPLOAD FORM' }}
            </button>

            <!-- PREVIEW OF NEW SELECTION -->
            <div id="newFormPreview" class="form-new-preview" style="display:none;">
                <div class="form-row">
                    <div class="form-info">
                        <div id="newFormFileName" style="font-size:13px;font-weight:500;"></div>
                        <div class="form-hint">Ready to upload</div>
                    </div>
                    <button type="button"
                            onclick="clearNewForm()"
                            style="font-size:20px;border:none;background:none;cursor:pointer;color:#DC2626;">
                        ✕
                    </button>
                </div>
            </div>

            <!-- SAVE BUTTON (only show when file is selected) -->
            <div id="formSaveButton" class="paf-actions-row" style="margin-top:10px; display:none;">
                <button type="submit" class="paf-btn paf-btn-green">
                    💾 SAVE FORM
                </button>
                <button type="button" onclick="clearNewForm()" class="paf-btn paf-btn-outline">
                    CANCEL
                </button>
            </div>

            <div class="paf-note">
                Upload PDF, DOC, or DOCX files only. Max 30 MB.
                <br>This form will be available to participants during registration.
            </div>
        </div>
    </form>
</div>

<!-- Hidden form for form deletion -->
<form id="deleteFormForm"
      action="{{ route('admin.participations.form.delete', $programme->id) }}"
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- SETTINGS --}}
<div class="paf-card">
    <h3 class="paf-card-title">Settings</h3>

    <form method="POST" action="{{ route('admin.participations.form.save', $programme->id) }}"
          enctype="multipart/form-data" id="settingsForm">
        @csrf

        <div class="paf-field" style="max-width: 400px;">
            <div class="paf-label">Form Active</div>
            <select class="paf-select" name="is_active" id="formActiveSelect">
                <option value="1" {{ $programme->is_active ? 'selected' : '' }}>YES</option>
                <option value="0" {{ !$programme->is_active ? 'selected' : '' }}>NO</option>
            </select>
            <div class="paf-note">Enable or disable public form submissions</div>
        </div>

        <div class="paf-alert paf-alert-warning" style="margin-top: 16px;">
            <strong>⚠️ Warning:</strong> Setting to "NO" will:
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                <li>Prevent public form submissions</li>
                <li>Show "Form Not Active" message to visitors</li>
            </ul>
        </div>

        <div class="paf-actions-row" style="margin-top: 20px;">
            <button type="submit" class="paf-btn paf-btn-green" style= "font-weight:600;">
                💾 SAVE SETTINGS
            </button>
        </div>
    </form>
</div>

    {{-- PUBLIC LINK --}}
    <div class="paf-card">
        <h3 class="paf-card-title">Public Link</h3>

        <div class="paf-actions-row">
            <form method="POST" action="{{ route('admin.participations.generate_link', $programme->id) }}">
                @csrf
                <button class="paf-btn paf-btn-green" type="submit" style= "font-weight:600;">
                    {{ $programme->public_token ? 'REGENERATE LINK' : '🔗 CREATE LINK' }}
                </button>
            </form>

            @if($programme->public_token)
                <a class="paf-btn paf-btn-orange" 
                   href="{{ route('participation.public.form', $programme->public_token) }}"
                   target="_blank" style= "font-weight:600;">
                    PREVIEW
                </a>
            @endif
        </div>

        <div class="paf-divider"></div>

        @php
            $link = session('public_link') ?? $publicLink;
        @endphp

        <div class="paf-link-row">
            <input class="paf-input paf-input-link"
                   data-public-link
                   value="{{ $link ?? '' }}"
                   placeholder="Click CREATE LINK to generate..."
                   readonly>

            <button class="paf-btn paf-btn-outline" type="button" data-copy-link>📋 COPY</button>
        </div>
    </div>

</div>

{{-- JAVASCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/admin-participations-form.js') }}"></script>
<script>
// Check for session messages and show SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    // Check for success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    @endif
    
    // Check for error message
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
        });
    @endif
    
    // Check for info message
    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: '{{ session('info') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    @endif
    
    // Check for public link message
    @if(session('public_link'))
        Swal.fire({
            icon: 'success',
            title: 'Link Generated!',
            html: 'Public link has been generated successfully.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    @endif

    // Setup QR Upload Form validation
    const qrForm = document.getElementById('qrUploadForm');
    if (qrForm) {
        qrForm.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('qrFileInput');
            
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No File Selected',
                    text: 'Please select a QR code image to upload.',
                });
                return false;
            }
        });
    }
});

// Preview existing QR in modal
function previewQR(imageUrl) {
    Swal.fire({
        title: 'QR Code Preview',
        imageUrl: imageUrl,
        imageWidth: 400,
        imageHeight: 400,
        imageAlt: 'QR Code',
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            image: 'qr-preview-image'
        }
    });
}

// Confirm and delete QR code (with forced page reload)
function confirmDeleteQR() {
    Swal.fire({
        title: 'Delete QR Code?',
        text: 'This will remove the QR code from this programme. You can upload a new one anytime.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting QR Code...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form and force page reload
            const form = document.getElementById('deleteQRForm');
            
            // Add a hidden input to indicate this is a QR delete action
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_qr_delete';
            input.value = '1';
            form.appendChild(input);
            
            // Submit the form
            form.submit();
            
            // Force reload after a short delay (in case redirect doesn't work)
            setTimeout(() => {
                window.location.reload(true); // true forces reload from server, not cache
            }, 1000);
        }
    });
}

// Preview newly selected QR file
function previewSelectedQR(input) {
    const preview = document.getElementById('newQRPreview');
    const image = document.getElementById('newQRImage');
    const fileName = document.getElementById('newQRFileName');
    const saveButton = document.getElementById('qrSaveButton');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please select a JPG, PNG, or WEBP image.',
            });
            input.value = '';
            return;
        }
        
        // Validate file size (10MB max)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please select an image smaller than 10MB.',
            });
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            image.src = e.target.result;
            fileName.textContent = file.name;
            preview.style.display = 'block';
            saveButton.style.display = 'flex';
        };
        
        reader.readAsDataURL(file);
    }
}

// Clear newly selected file
function clearNewQR() {
    const input = document.getElementById('qrFileInput');
    const preview = document.getElementById('newQRPreview');
    const saveButton = document.getElementById('qrSaveButton');
    
    input.value = '';
    preview.style.display = 'none';
    saveButton.style.display = 'none';
}
// Confirm before deactivating form
document.addEventListener('DOMContentLoaded', function() {
    const settingsForm = document.getElementById('settingsForm');
    const formActiveSelect = document.getElementById('formActiveSelect');
    let originalValue = formActiveSelect.value;
    
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            const newValue = formActiveSelect.value;
            
            // If changing from active (1) to inactive (0)
            if (originalValue === '1' && newValue === '0') {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Lock Programme?',
                    html: `
                        <div style="text-align: left; padding: 0 20px;">
                            <p style="margin-bottom: 16px;">This action will:</p>
                            <ul style="margin: 0; padding-left: 20px; color: #DC2626;">
                                <li style="margin-bottom: 8px;"><strong>Disable public form submissions</strong></li>
                                <li style="margin-bottom: 8px;"><strong>Show "Form Not Active" to visitors</strong></li>
                            </ul>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, Lock Programme',
                    cancelButtonText: 'Cancel',
                    width: '600px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Saving Settings...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        settingsForm.submit();
                    } else {
                        // Reset to original value
                        formActiveSelect.value = originalValue;
                    }
                });
            }
            // If just saving other settings or activating form, submit normally
            // No confirmation needed
        });
    }
});
</script>


<style>
/* QR Code Styles */
.qr-box {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.qr-row {
    display: flex;
    align-items: center;
    gap: 16px;
}

.qr-thumb {
    width: 60px;
    height: 60px;
    object-fit: contain;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    background: white;
    padding: 4px;
}

.qr-info {
    flex: 1;
}

.qr-title {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
}

.qr-filename {
    font-size: 12px;
    color: #6B7280;
}

.qr-hint {
    font-size: 12px;
    color: #059669;
    margin-top: 4px;
}

.qr-actions {
    display: flex;
    gap: 8px;
}

.qr-upload-btn {
    width: 100%;
    padding: 12px;
    background: #F3F4F6;
    border: 2px dashed #D1D5DB;
    border-radius: 8px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 12px;
}

.qr-upload-btn:hover {
    background: #E5E7EB;
    border-color: #9CA3AF;
}

.qr-new-preview {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    border-radius: 8px;
    padding: 12px;
    margin-top: 12px;
}

.qr-preview-image {
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    padding: 8px;
    background: white;
}

.paf-actions-row {
    display: flex;
    gap: 12px;
    align-items: center;
}
.paf-form-section {
    background: #ffffff;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 24px;
}

.paf-form-title {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.paf-form-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #E5E7EB;
}

.required {
    color: #DC2626;
}

/* FIX: Make textarea same height as other inputs */
.paf-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #D1D5DB;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
    resize: vertical;
    min-height: 42px; /* Same as input height */
    max-height: 120px; /* Limit maximum height */
    line-height: 1.5;
    font-family: inherit;
}

.paf-textarea:focus {
    outline: none;
    border-color: #00542A;
    box-shadow: 0 0 0 3px rgba(0, 84, 42, 0.1);
}

/* FIX: Make description field inline with other fields */
.paf-description-field {
    grid-column: span 2; /* Make it span 2 columns in the grid */
}

.paf-payment-details div {
    margin-bottom: 4px;
}
.paf-alert {
    padding: 16px;
    border-radius: 8px;
    border: 2px solid;
    font-size: 14px;
    line-height: 1.6;
}

.paf-alert-warning {
    background: #FEF3C7;
    border-color: #FCD34D;
    color: #92400E;
}

.paf-alert strong {
    display: block;
    margin-bottom: 8px;
    font-size: 15px;
}

.paf-alert ul {
    list-style: disc;
}

.paf-alert li {
    margin-bottom: 4px;
}
</style>

<style>
/* ============= UPLOAD FORM SECTION STYLES ============= */

.upload-form-box {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.form-row {
    display: flex;
    align-items: center;
    gap: 16px;
    justify-content: space-between;
}

.form-info {
    flex: 1;
}

.form-title {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
}

.form-filename {
    font-size: 12px;
    color: #6B7280;
    word-break: break-all;
}

.form-hint {
    font-size: 12px;
    color: #059669;
    margin-top: 4px;
}

.form-actions {
    display: flex;
    gap: 8px;
}

.upload-form-btn {
    width: 100%;
    padding: 12px;
    background: #F3F4F6;
    border: 2px dashed #D1D5DB;
    border-radius: 8px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 12px;
}

.upload-form-btn:hover {
    background: #E5E7EB;
    border-color: #9CA3AF;
    color: #111827;
}

.form-new-preview {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    border-radius: 8px;
    padding: 12px;
    margin-top: 12px;
}

.paf-actions-row {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* ============= RESPONSIVE ============= */

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .form-actions {
        width: 100%;
    }

    .form-actions button {
        flex: 1;
    }

    .paf-actions-row {
        flex-direction: column;
    }

    .paf-actions-row button {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .upload-form-box {
        padding: 12px;
    }

    .form-title {
        font-size: 13px;
    }

    .form-filename {
        font-size: 11px;
    }

    .upload-form-btn {
        font-size: 13px;
        padding: 10px;
    }
}
</style>

<!-- ============================================
     PASTE THIS JAVASCRIPT INTO YOUR <script> SECTION
     ============================================ -->
<script>
// Preview existing form in modal
function previewForm(fileUrl) {
    const fileName = fileUrl.split('/').pop();
    const fileExtension = fileName.split('.').pop().toLowerCase();

    if (fileExtension === 'pdf') {
        // Open PDF in new tab
        window.open(fileUrl, '_blank');
    } else {
        // For Word docs, show message
        Swal.fire({
            title: 'View Document',
            html: `
                <p>Document: <strong>${fileName}</strong></p>
                <p style="font-size: 13px; color: #6B7280; margin-top: 12px;">
                    This is a ${fileExtension.toUpperCase()} file.
                </p>
            `,
            icon: 'info',
            confirmButtonText: 'Download File',
            showCancelButton: true,
            cancelButtonText: 'Close'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = fileUrl;
            }
        });
    }
}

// Confirm and delete form
function confirmDeleteForm() {
    Swal.fire({
        title: 'Delete Upload Form?',
        text: 'This will remove the form file from this programme. You can upload a new one anytime.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting Form...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            const form = document.getElementById('deleteFormForm');
            form.submit();
            
            // Force reload after a short delay
            setTimeout(() => {
                window.location.reload(true);
            }, 1000);
        }
    });
}

// Preview newly selected form file
function previewSelectedForm(input) {
    const preview = document.getElementById('newFormPreview');
    const fileName = document.getElementById('newFormFileName');
    const saveButton = document.getElementById('formSaveButton');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        const validTypes = ['application/pdf', 'application/msword', 
                          'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please select a PDF, DOC, or DOCX file.',
            });
            input.value = '';
            return;
        }
        
        // Validate file size (30MB max)
        const maxSize = 30 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please select a file smaller than 30MB.',
            });
            input.value = '';
            return;
        }
        
        // Show preview
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        fileName.innerHTML = `
            <div style="font-size: 13px; color: #00542A; font-weight: 500;">
                📄 ${file.name}
            </div>
            <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">
                ${fileSize} MB
            </div>
        `;
        
        preview.style.display = 'block';
        saveButton.style.display = 'flex';
    }
}

// Clear newly selected file
function clearNewForm() {
    const input = document.getElementById('uploadFormFileInput');
    const preview = document.getElementById('newFormPreview');
    const saveButton = document.getElementById('formSaveButton');
    
    input.value = '';
    preview.style.display = 'none';
    saveButton.style.display = 'none';
}

// Form submission validation
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadFormUpload');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('uploadFormFileInput');
            
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No File Selected',
                    text: 'Please select a form file to upload.',
                });
                return false;
            }
        });
    }
});
</script>
@endsection