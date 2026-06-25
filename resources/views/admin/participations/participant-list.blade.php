@extends('admin.layouts.admin-template')
@section('title', 'Participant List')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/admin-program-detail.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin-participations.css') }}">

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
            <span class="breadcrumb-current">PARTICIPANT LIST</span>
        </div>
    </div>
</div>

<div class="pa-wrap">

{{-- TOP BAR --}}
<div class="pa-topbar pa-participant-page">
    <div class="pa-topbar-left">
        <a href="{{ route('admin.participations.export.print', $programme->id) }}?{{ request()->getQueryString() }}"
           target="_blank"
           class="pa-btn pa-btn-green">
            PRINT
        </a>

        <a href="{{ route('admin.participations.export.excel', $programme->id) }}?{{ request()->getQueryString() }}"
           class="pa-btn pa-btn-gold">
            EXCEL
        </a>

        <form id="filterForm"
              method="GET"
              action="{{ route('admin.participations.participant_list', $programme->id) }}"
              class="pa-filter-group">

            <select name="status" class="pa-filter" onchange="this.form.submit()">
                <option value="">ALL STATUS</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>PENDING</option>
                <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>APPROVED</option>
            </select>

            <select name="agency" class="pa-filter" onchange="this.form.submit()">
                <option value="">ALL AGENCY</option>
                @foreach($agencies as $agency)
                    <option value="{{ $agency }}" {{ request('agency')==$agency ? 'selected' : '' }}>
                        {{ $agency }}
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="q" value="{{ request('q') }}">
        </form>
    </div>

    <form method="GET"
          action="{{ route('admin.participations.participant_list', $programme->id) }}"
          class="pa-searchbar">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="agency" value="{{ request('agency') }}">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="SEARCH..."
               class="pa-search-input-2">
        <button type="submit" class="pa-search-btn-2"></button>
    </form>
</div>

{{-- TABLE --}}
<div class="pa-table-card">
    <div class="pa-table-scroll">
        <table class="pa-table-2">
            <thead>
                <tr>
                    <th rowspan="2">BIL</th>
                    <th rowspan="2">COMPANY</th>
                    <th rowspan="2">OFFICER</th>
                    <th rowspan="2">PHONE</th>
                    <th rowspan="2">PACKAGE</th>
                    <th rowspan="2">QTY</th>
                    <th rowspan="2">TOTAL</th>
                    <th colspan="3" class="pa-center">PARTICIPANTS</th>
                    <th rowspan="2">RECEIPT</th>
                    <th rowspan="2">FORM</th>
                    <th rowspan="2">STATUS</th>
                    <th rowspan="2" class="pa-center">ACTION</th>
                </tr>
                <tr>
                    <th class="pa-col-name">NAME</th>
                    <th class="pa-col-position">POSITION</th>
                    <th class="pa-col-table">TABLE NO</th>
                </tr>
            </thead>

            <tbody>
                @forelse($submissions as $i => $s)
                    <tr>
                        <td>{{ $submissions->firstItem() + $i }}</td>
                        <td>{{ $s->company_name }}</td>
                        <td>{{ $s->officer_name }}</td>
                        <td>{{ $s->phone_number }}</td>
                        <td>{{ optional(optional($s->programmePackage)->package)->name ?? '-' }}</td>
                        <td class="js-qty">{{ $s->quantity ?: '-' }}</td>
                        <td class="js-total">{{ $s->total_price > 0 ? 'RM ' . number_format($s->total_price, 2) : '-' }}</td>
                        <td class="pa-col-name">
                            @foreach($s->participants as $p)
                                <div class="pa-participant-row">{{ $p->name }}</div>
                            @endforeach
                        </td>

                        <td class="pa-col-position">
                            @foreach($s->participants as $p)
                                <div class="pa-participant-row">{{ $p->position }}</div>
                            @endforeach
                        </td>

                        <td class="pa-col-table">
                            @foreach($s->participants as $p)
                                <div class="pa-participant-row">
                                    {{ $p->table_number ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                        <td class="pa-center">
                            @if($s->receipt_path)
                                <a href="{{ asset('storage/'.$s->receipt_path) }}" target="_blank">VIEW</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="pa-center">
                        @if($s->supporting_document_path)
                            <a href="{{ asset('storage/'.$s->supporting_document_path) }}"
                            target="_blank"
                            class="pa-form-view-btn">
                                VIEW
                            </a>
                        @else
                            -
                        @endif
                    </td>

                        <td>
                            <span class="pa-status-badge pa-status-{{ $s->status }}">
                                {{ strtoupper($s->status) }}
                            </span>
                        </td>
                        <td class="pa-center">
                            <div class="pa-actions">
                                <a href="{{ route('admin.submissions.edit', $s->id) }}"
                                   class="pa-action-btn pa-edit">✎</a>

                                <form method="POST"
                                      action="{{ route('admin.submissions.delete', $s->id) }}"
                                      onsubmit="return confirm('Delete this submission?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="pa-action-btn pa-delete">×</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="pa-empty">No submissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
<div class="pa-pagination">
    {{ $submissions->links() }}
</div>

</div>
<style>
    .pa-participant-row {
        padding: 6px 0;
        border-bottom: 1px dashed #e5e7eb;
        line-height: 1.4;
    }

    .pa-participant-row:last-child {
        border-bottom: none;
    }
</style>
@endsection