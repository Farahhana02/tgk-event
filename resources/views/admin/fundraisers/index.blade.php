@extends('admin.layouts.admin-template')

@section('title', 'Fundraiser')

@section('content')
<link rel="stylesheet" href="/assets/css/admin-fundraiser-index.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- ======================
     BREADCRUMB
======================= -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">FUNDRAISING</div>

        <div class="breadcrumb-path">
            <a href="{{ route('admin.index') }}">
                <img src="{{ asset('assets/icons/Home.png') }}" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <a href="{{ route('admin.fundraisers') }}" class="breadcrumb-link">FUNDRAISING</a>
        </div>
    </div>
</div>

<!-- ======================
     PAGE CONTENT
======================= -->
<div class="fundraiser-page">

    <!-- SEARCH BAR -->
    <div class="search-area">
        <input type="text" placeholder="SEARCH..." class="search-input" id="searchInput">
        <button class="search-btn" onclick="searchFundraisers()">
            <img src="/assets/icons/Search.png" class="search-icon">
        </button>
    </div>

    <!-- TABLE WRAPPER -->
    <div class="fundraiser-table-wrapper">
        <table class="fundraiser-table">
            <thead>
                <tr>
                    <th>BIL</th>
                    <th>IMAGE</th>
                    <th>PROGRAMME</th>
                    <th>START DATE</th>
                    <th>END DATE</th>
                    <th>TARGET</th>
                    <th>PROGRESS</th>
                    <th>FORM</th>
                    <th>STATUS</th>
                    <th>INFO</th>
                    <th>ACTION</th>
                </tr>
            </thead>

            <tbody id="fundraisersTableBody">
                @foreach($fundraisers as $index => $f)
                <tr id="row-{{ $f->id }}">
                    <td>{{ $index + 1 }}</td>
                    
                    <!-- IMAGE COLUMN -->
                    <td>
                        @if($f->image_path)
                            <img src="{{ asset('storage/' . $f->image_path) }}" 
                                 alt="{{ $f->programme_name }}" 
                                 class="fundraiser-thumbnail"
                                 onerror="this.src='/assets/icons/no-image.png'">
                        @else
                            <div class="no-image-placeholder">
                                <span>NO IMAGE</span>
                            </div>
                        @endif
                    </td>

                    <td>{{ strtoupper($f->programme_name) }}</td>

                    <td>{{ \Carbon\Carbon::parse($f->start_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($f->end_date)->format('d/m/Y') }}</td>

                    <td>RM {{ number_format($f->target_amount, 2) }}</td>
                    <td>{{ $f->progress }} %</td>
                    <td>
                        @if($f->form_path)
                            <a href="{{ asset('storage/'.$f->form_path) }}" 
                            target="_blank"
                            class="btn btn-sm btn-primary">
                                View
                            </a>
                        @else
                            <span class="text-muted">No Form</span>
                        @endif
                    </td>
                    <td>
                        @if(now()->lte(\Carbon\Carbon::parse($f->end_date)))
                            <span class="status-active">ACTIVE</span>
                        @else
                            <span class="status-inactive">NOT ACTIVE</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.fundraisers.show', $f->id) }}" class="icon-btn info-btn">
                            <img src="/assets/icons/info.png">
                        </a>
                    </td>

                    <td class="action-buttons">
                        <button class="icon-btn edit-btn" onclick="openEditModal({{ $f->id }})">
                            <img src="/assets/icons/update.png">
                        </button>

                        <button class="icon-btn delete-btn"
                                onclick="confirmDelete({{ $f->id }}, '{{ $f->programme_name }}')">
                            <img src="/assets/icons/delete.png">
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ADD BUTTON -->
    <button class="add-button" onclick="openAddModal()">+</button>

</div>

<!-- ======================
     ADD MODAL
======================= -->
<div id="addModal" class="modal-overlay">
    <div class="modal-box">
        <h2 class="modal-title">ADD PROGRAMME</h2>

        <div class="modal-content">
            <form id="addForm" enctype="multipart/form-data">
                @csrf

                <label>PROGRAMME :</label>
                <input type="text" name="programme_name" class="modal-input uppercase-input" required style="text-transform: uppercase;">

                <label>START DATE :</label>
                <input type="text" name="start_date_display" id="startDateDisplay"
                       class="modal-input date-picker" placeholder="DD/MM/YYYY" 
                       autocomplete="off" required style="text-transform: uppercase;">
                <input type="hidden" name="start_date" id="startDateHidden">

                <label>END DATE :</label>
                <input type="text" name="end_date_display" id="endDateDisplay"
                       class="modal-input date-picker" placeholder="DD/MM/YYYY" 
                       autocomplete="off" required style="text-transform: uppercase;">
                <input type="hidden" name="end_date" id="endDateHidden">

                <label>TARGET :</label>
                <input type="text" name="target_amount" id="targetField" class="modal-input"
                       placeholder="RM 0.00" required style="text-transform: uppercase;">

                <label>PROGRAMME IMAGE :</label>
                <input type="file" name="image" id="addImage" class="modal-input" accept="image/*">
                <small class="image-note">ACCEPTED FORMATS: JPG, PNG, GIF, WEBP. MAX SIZE: 15MB</small>
                <div id="addImagePreview" class="image-preview"></div>

                <label>DONATION FORM (HARDCOPY) :</label>
                <input type="file"
                    name="form_file"
                    class="modal-input"
                    accept=".pdf,.jpg,.jpeg,.png">

                <small class="image-note">
                    ACCEPTED FORMATS: PDF, JPG, PNG. MAX SIZE: 5MB
                </small>

                <label>DESCRIPTION :</label>
                <textarea name="description" class="modal-input uppercase-input" rows="4"
                          placeholder="ENTER PROGRAMME DESCRIPTION..." style="text-transform: uppercase;"></textarea>
            </form>
        </div>

        <div class="modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeAddModal()">CANCEL</button>
            <button type="submit" class="save-btn" form="addForm">SAVE</button>
        </div>
    </div>
</div>

<!-- ======================
     EDIT MODAL
======================= -->
<div id="editModal" class="modal-overlay">
    <div class="modal-box">
        <h2 class="modal-title">UPDATE PROGRAMME</h2>

        <div class="modal-content">
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="fundraiser_id" id="editFundraiserId">

                <label>PROGRAMME :</label>
                <input type="text" name="programme_name" id="editProgrammeName" class="modal-input uppercase-input" required style="text-transform: uppercase;">

                <label>START DATE :</label>
                <input type="text" name="start_date_display" id="editStartDateDisplay"
                       class="modal-input date-picker" placeholder="DD/MM/YYYY" 
                       autocomplete="off" required style="text-transform: uppercase;">
                <input type="hidden" name="start_date" id="editStartDateHidden">

                <label>END DATE :</label>
                <input type="text" name="end_date_display" id="editEndDateDisplay"
                       class="modal-input date-picker" placeholder="DD/MM/YYYY" 
                       autocomplete="off" required style="text-transform: uppercase;">
                <input type="hidden" name="end_date" id="editEndDateHidden">

                <label>TARGET :</label>
                <input type="text" name="target_amount" id="editTargetAmount"
                       class="modal-input" placeholder="RM 0.00" required style="text-transform: uppercase;">

                <label>PROGRAMME IMAGE :</label>
                <input type="file" name="image" id="editImage" class="modal-input" accept="image/*">
                <small class="image-note">LEAVE EMPTY TO KEEP CURRENT IMAGE</small>
                <div id="editImagePreview" class="image-preview"></div>

                <label>DONATION FORM (HARDCOPY) :</label>
                <input type="file"
                    name="form_file"
                    class="modal-input"
                    accept=".pdf,.jpg,.jpeg,.png">

                <small class="image-note">
                    LEAVE EMPTY TO KEEP CURRENT FORM
                </small>
                
                <div id="currentFormWrapper" style="margin-top:6px; display:none;">
                    <a id="currentFormLink"
                    href="#"
                    target="_blank"
                    class="btn btn-sm btn-outline-success">
                        View Current Form
                    </a>

                    <button type="button"
                            class="btn btn-sm btn-danger"
                            style="margin-left:6px;"
                            onclick="deleteProgrammeForm()">
                        Delete
                    </button>
                </div>

                <label>DESCRIPTION :</label>
                <textarea name="description" id="editDescription" class="modal-input uppercase-input" rows="4" style="text-transform: uppercase;"></textarea>
            </form>
        </div>

        <div class="modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeEditModal()">CANCEL</button>
            <button type="submit" class="save-btn" form="editForm">UPDATE</button>
        </div>
    </div>
</div>

<!-- ======================
     INFO MODAL (SMALL)
======================= -->
<div id="infoModal" class="modal-overlay">
    <div class="modal-box info-modal-box">
        <h2 class="modal-title">PROGRAMME INFORMATION</h2>

        <div class="info-content">
            <div class="info-row">
                <strong>IMAGE:</strong>
                <img id="infoImage" src="" alt="Programme Image">
            </div>

            <div class="info-row"><strong>PROGRAMME:</strong><span id="infoProgrammeName"></span></div>
            <div class="info-row"><strong>START DATE:</strong><span id="infoStartDate"></span></div>
            <div class="info-row"><strong>END DATE:</strong><span id="infoEndDate"></span></div>
            <div class="info-row"><strong>TARGET AMOUNT:</strong><span id="infoTargetAmount"></span></div>
            <div class="info-row"><strong>PROGRESS:</strong><span id="infoProgress"></span></div>
            <div class="info-row"><strong>STATUS:</strong><span id="infoStatus"></span></div>

            <div class="info-row">
                <strong>DESCRIPTION:</strong>
                <p id="infoDescription"></p>
            </div>
        </div>

        <div class="modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeInfoModal()">CLOSE</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/assets/js/fundraiser.js"></script>

@endsection