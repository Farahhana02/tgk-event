@extends('admin.layouts.admin-template')

@section('title', 'Programme Detail')

@section('content')
<link rel="stylesheet" href="/assets/css/admin-fundraisers-programme.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- BREADCRUMB -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">PROGRAMME DETAIL</div>
        <div class="breadcrumb-path">
            <a href="{{ route('admin.index') }}">
                <img src="{{ asset('assets/icons/Home.png') }}" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <a href="{{ route('admin.fundraisers') }}" class="breadcrumb-link">FUNDRAISING</a>
            <span>/</span>
            <span class="breadcrumb-current">
                PROGRAM {{ strtoupper($fundraiser->programme_name) }}
            </span>
        </div>
    </div>
</div>

<!-- PAGE CONTENT -->
<div class="detail-page">

    <!-- PROGRAMME INFO -->
    <div class="info-section">
        <div class="info-card">

            @if($fundraiser->image_path)
                <div class="programme-image">
                    <img src="{{ asset('storage/'.$fundraiser->image_path) }}"
                         alt="{{ $fundraiser->programme_name }}"
                         onerror="this.src='/assets/icons/no-image.png'">
                </div>
            @endif

            <div class="info-grid-vertical">
                <div class="info-item-vertical">
                    <strong>PROGRAMME :</strong>
                    <span>{{ strtoupper($fundraiser->programme_name) }}</span>
                </div>

                <div class="info-item-vertical date-row">
                    <div class="date-item">
                        <strong>START DATE :</strong>
                        <span>{{ \Carbon\Carbon::parse($fundraiser->start_date)->format('d/m/Y') }}</span>
                    </div>
                    <span class="date-separator">-</span>
                    <div class="date-item">
                        <strong>END DATE :</strong>
                        <span>{{ \Carbon\Carbon::parse($fundraiser->end_date)->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="info-item-vertical">
                    <strong>TARGET :</strong>
                    <span>RM {{ number_format($fundraiser->target_amount, 2) }}</span>
                </div>

                <div class="info-item-vertical">
                    <strong>PROGRESS :</strong>
                    <span>{{ $fundraiser->progress }} %</span>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="fundraising-summary">
                <div class="summary-item raised">
                    <div class="summary-label">TOTAL RAISED</div>
                    <div class="summary-value">
                        RM {{ number_format($fundraiser->total_raised ?? 0, 2) }}
                    </div>
                </div>

                <div class="summary-item donors">
                    <div class="summary-label">TOTAL DONORS</div>
                    <div class="summary-value">{{ $fundraiser->donors->count() }}</div>
                </div>

                <div class="summary-item remaining">
                    <div class="summary-label">REMAINING</div>
                    <div class="summary-value">
                        RM {{ number_format(max(0, $fundraiser->target_amount - ($fundraiser->total_raised ?? 0)), 2) }}
                    </div>
                </div>
            </div>

            @if($fundraiser->description)
                <div class="description-section">
                    <strong>DESCRIPTION :</strong>
                    <p>{{ strtoupper($fundraiser->description) }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- DONORS TABLE -->
<div class="section-header">
    <h3>DONOR LIST</h3>
    <div class="action-buttons-group">
        <button class="export-btn print-btn" onclick="exportToPrint()">
            <img src="/assets/icons/print.png" alt="Print" style="width: 18px; height: 18px;">
            PRINT
        </button>
        <button class="export-btn excel-btn" onclick="exportToExcel()">
            <img src="/assets/icons/excel.png" alt="Excel" style="width: 18px; height: 18px;">
            EXCEL
        </button>
        <button class="add-donor-btn" onclick="openAddDonorModal()">
            <span>+</span> ADD DONOR
        </button>
    </div>
</div>

        <div class="donors-table-wrapper">
            <table class="donors-table">
                <thead>
                    <tr>
                        <th>BIL</th>
                        <th>DONOR</th>
                        <th>EMAIL</th>
                        <th>NO. TEL</th>
                        <th>AMOUNT PLEDGE</th>
                        <th>NOTES</th>
                        <th>RECEIPT</th>
                        <th>FORM</th>
                        <th>DONATE TIME</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>

                {{-- ✅ ONLY ONE TBODY --}}
                <tbody id="donorsTableBody">
                    @forelse($fundraiser->donors as $index => $donor)
                    <tr id="donor-row-{{ $donor->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td class="donor-name">{{ strtoupper($donor->name) }}</td>
                        <td class="donor-email">{{ strtolower($donor->email) }}</td>
                        <td class="donor-phone">{{ strtoupper($donor->phone) }}</td>
                        <td>RM {{ number_format($donor->amount, 2) }}</td>
                        <td>{{ strtoupper($donor->notes ?? '-') }}</td>

                        <td>
                            @if($donor->receipt_path)
                                <a href="{{ asset('storage/'.$donor->receipt_path) }}"
                                   target="_blank"
                                   class="receipt-link">View</a>
                            @else
                                <span class="no-receipt">-</span>
                            @endif
                        </td>

                        <td>
                            @if($donor->submitted_form_path)
                                <a href="{{ asset('storage/'.$donor->submitted_form_path) }}"
                                   target="_blank"
                                   class="receipt-link">View</a>
                            @else
                                <span class="no-receipt">-</span>
                            @endif
                        </td>

                        <td>{{ \Carbon\Carbon::parse($donor->created_at)->format('d/m/Y H:i') }}</td>

                        <td>
                            <span class="status-badge status-{{ strtolower($donor->status) }}">
                                {{ strtoupper($donor->status) }}
                            </span>
                        </td>

                        <td class="action-buttons">
                            <button class="icon-btn edit-btn"
                                    onclick="openEditDonorModal({{ $donor->id }})">
                                <img src="/assets/icons/update.png">
                            </button>
                            <button class="icon-btn delete-btn"
                                    onclick="confirmDeleteDonor({{ $donor->id }}, '{{ $donor->name }}')">
                                <img src="/assets/icons/delete.png">
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="no-data">No donors yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- ADD DONOR MODAL -->
<div id="addDonorModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <h2 class="modal-title">ADD DONOR</h2>

        <div class="modal-content">
            <form id="addDonorForm" enctype="multipart/form-data">
                <input type="hidden" name="status" value="pending">

                @csrf
                <input type="hidden" name="fundraiser_id" value="{{ $fundraiser->id }}">

                <label>DONOR :</label>
                <input type="text" name="name" class="modal-input" required>

                <label>EMAIL :</label>
                <input type="email" name="email" class="modal-input" required>

                <label>NO. TEL :</label>
                <input type="text" name="phone" class="modal-input" required>

                <label>AMOUNT PLEDGE :</label>
                <input type="number" name="amount" class="modal-input" step="0.01" required>

                <label>NOTES :</label>
                <textarea name="notes" class="modal-input" rows="3"></textarea>

                <!-- RECEIPT -->
                <label>RECEIPT (PDF / JPG / PNG ≤ 30MB) :</label>
                <input type="file"
                       name="receipt"
                       id="addReceipt"
                       class="modal-input"
                       accept=".pdf,.jpg,.jpeg,.png">

                <!-- ✅ HARDCOPY FORM -->
                <label>SUBMITTED FORM (HARDCOPY) :</label>
                <input type="file"
                       name="submitted_form"
                       id="addSubmittedForm"
                       class="modal-input"
                       accept=".pdf,.jpg,.jpeg,.png">

                <small class="file-note">
                    Accepted formats: PDF, JPG, PNG (Max 10MB)
                </small>
            </form>
        </div>

        <div class="modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeAddDonorModal()">CANCEL</button>
            <button type="submit" class="save-btn" form="addDonorForm">SAVE</button>
        </div>
    </div>
</div>

<!-- EDIT DONOR MODAL -->
<div id="editDonorModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <h2 class="modal-title">UPDATE DONOR DETAILS</h2>

        <div class="modal-content">
            <form id="editDonorForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="donor_id" id="editDonorId">

                <label>DONOR :</label>
                <input type="text" name="name" id="editDonorName"
                       class="modal-input" required>

                <label>EMAIL :</label>
                <input type="email" name="email" id="editDonorEmail"
                       class="modal-input" required>

                <label>NO. TEL :</label>
                <input type="text" name="phone" id="editDonorPhone"
                       class="modal-input" required>

                <label>AMOUNT PLEDGE :</label>
                <input type="number" name="amount" id="editDonorAmount"
                       class="modal-input" step="0.01" min="0" required>

                <label>NOTES :</label>
                <textarea name="notes" id="editDonorNotes"
                          class="modal-input" rows="3"></textarea>

                <label>RECEIPT (PDF / JPG / PNG, ≤ 30MB) :</label>
                <input type="file" name="receipt" id="editReceipt"
                    class="modal-input"
                    accept=".pdf,.jpg,.jpeg,.png">

                <div id="currentReceipt"></div>             

                <!-- ✅ HARDCOPY FORM -->
                <label>SUBMITTED FORM (HARDCOPY) :</label>
                <input type="file"
                    name="submitted_form"
                    id="editSubmittedForm"
                    class="modal-input"
                    accept=".pdf,.jpg,.jpeg,.png">

                <div id="currentSubmittedForm"></div>

                <label>STATUS :</label>
                <select name="status" id="editDonorStatus"
                        class="modal-input" required>
                    <option value="pending">PENDING</option>
                    <option value="approved">APPROVED</option>
                </select>
            </form>
        </div>

        <div class="modal-buttons">
            <button type="button"
                    class="cancel-btn"
                    onclick="closeEditDonorModal()">
                CANCEL
            </button>

            <button type="submit"
                    class="save-btn"
                    form="editDonorForm">
                UPDATE
            </button>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/assets/js/admin-fundraiser-programme.js"></script>
<script>
    window.fundraiserId = "{{ $fundraiser->id }}";
</script>

@endsection
