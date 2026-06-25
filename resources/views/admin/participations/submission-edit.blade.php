@extends('admin.layouts.admin-template')
@section('title', 'Edit Submission')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/admin-participation-submission.css') }}">

<div class="container-fluid">
<div class="ps-wrap">

    {{-- PAGE TITLE --}}
    <h1 class="ps-title">Edit Submission</h1>

    {{-- BACK BUTTON --}}
    <a href="{{ route('admin.participations.participant_list', $submission->programme_id) }}"
       class="ps-btn-add"
       style="background:#6B7280; margin:10px 0 20px; display:inline-block;">
        ← Back to Participant List
    </a>

    {{-- INFO CARD --}}
    <div class="ps-info">
        <p><strong>Programme:</strong> {{ $submission->programme->title }}</p>
        <p><strong>Company / Agency:</strong> {{ $submission->company_name }}</p>
        <p><strong>Package:</strong> {{ optional($submission->package)->name ?? '-' }}</p>

        {{-- STATUS UPDATE --}}
        <form method="POST"
              action="{{ route('admin.submissions.status.update', $submission->id) }}"
              style="margin-top:12px;">
            @csrf
            @method('PUT')

            <label style="font-weight:600;">Status</label>

            <div style="display:flex; gap:10px; align-items:center;">
                <select name="status" class="form-control" style="max-width:220px;">
                    <option value="pending"
                        {{ $submission->status === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="approved"
                        {{ $submission->status === 'approved' ? 'selected' : '' }}>
                        Approved
                    </option>
                </select>

                <button type="submit" class="ps-btn-add">
                    Update Status
                </button>
            </div>
        </form>
    </div>

    {{-- PARTICIPANTS HEADER --}}
    <div class="ps-section-header">
        <h4>Participants</h4>

        {{-- Disable add if approved (future-proof) --}}
        @if($submission->status !== 'approved')
            <button type="button" class="ps-btn-add" onclick="addRow()">+ Add Participant</button>
        @endif
    </div>

    {{-- PARTICIPANTS FORM --}}
    <form method="POST"
          action="{{ route('admin.submissions.participants.update', $submission->id) }}">
        @csrf
        @method('PUT')

        <table class="ps-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Table No</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody id="participant-table">
                @foreach ($submission->participants as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>

                    {{-- NAME --}}
                    <td>
                        <input type="hidden"
                            name="participants[{{ $i }}][id]"
                            value="{{ $p->id }}">

                        <input type="text"
                            name="participants[{{ $i }}][name]"
                            value="{{ $p->name }}"
                            {{ $submission->status === 'approved' ? 'readonly' : '' }}
                            required>
                    </td>

                    {{-- POSITION --}}
                    <td>
                        <input type="text"
                            name="participants[{{ $i }}][position]"
                            value="{{ $p->position }}"
                            {{ $submission->status === 'approved' ? 'readonly' : '' }}
                            placeholder="e.g. Officer">
                    </td>

                    {{-- ✅ TABLE NUMBER (INI JAWAPAN “WHERE”) --}}
                    <td>
                        <input type="text"
                            name="participants[{{ $i }}][table_number]"
                            value="{{ $p->table_number }}"
                            placeholder="-"
                            {{ $submission->status === 'approved' ? 'readonly' : '' }}
                            style="width:90px; text-align:center;">
                    </td>

                    {{-- ACTION --}}
                    <td>
                        @if($submission->status !== 'approved')
                            <button type="button"
                                    class="ps-btn-delete"
                                    onclick="deleteParticipant({{ $p->id }}, this)">
                                Delete
                            </button>
                        @else
                            <span style="color:#9CA3AF;">Locked</span>
                        @endif
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>

        {{-- SAVE BUTTON --}}
        @if($submission->status !== 'approved')
            <div style="margin-top:16px;">
                <button type="submit" class="ps-btn-add">
                    Save Changes
                </button>
            </div>
        @endif
    </form>

</div>
</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* =====================================================
   RE-INDEX TABLE
===================================================== */
function reindexTable() {
    document.querySelectorAll('#participant-table tr').forEach((row, index) => {
        row.querySelector('td:first-child').innerText = index + 1;

        row.querySelectorAll('input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
        });
    });
}

/* =====================================================
   ADD ROW
===================================================== */
function addRow() {
    const table = document.getElementById('participant-table');
    const index = table.querySelectorAll('tr').length;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${index + 1}</td>
        <td><input type="text" name="participants[${index}][name]" required></td>
        <td><input type="text" name="participants[${index}][position]" placeholder="e.g. Officer"></td>
        <td>
            <button type="button" class="ps-btn-delete" onclick="removeRow(this)">
                Delete
            </button>
        </td>
    `;
    table.appendChild(row);
}

/* =====================================================
   REMOVE UNSAVED ROW
===================================================== */
function removeRow(btn) {
    btn.closest('tr').remove();
    reindexTable();
}

/* =====================================================
   DELETE PARTICIPANT (AJAX + SWEETALERT)
===================================================== */
function deleteParticipant(id, btn) {

    Swal.fire({
        title: 'Delete participant?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete'
    }).then((result) => {

        if (!result.isConfirmed) return;

        fetch(`/admin/participants/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                btn.closest('tr').remove();
                reindexTable();

                Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });
    });
}
</script>

{{-- =====================
   SWEET ALERT FLASH
===================== --}}

@if(session('success'))
<script>
    const msg = "{{ session('success') }}";

    let icon = 'success';
    let title = 'Success';
    let color = '#00542A';

    // 🔥 Detect pending status
    if (msg.toLowerCase().includes('pending')) {
        icon = 'warning';
        title = 'Pending';
        color = '#C08329';
    }

    Swal.fire({
        icon: icon,
        title: title,
        text: msg,
        confirmButtonColor: color,
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: "{{ session('info') }}",
        confirmButtonColor: '#6B7280'
    });
</script>
@endif

@endsection
