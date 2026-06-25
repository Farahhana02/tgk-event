@extends('admin.layouts.admin-template')
@section('title', 'Participation Info')

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

            <a href="{{ route('admin.participations.index') }}" class="breadcrumb-link">
                PARTICIPATION
            </a>
            <span>/</span>

            <a href="{{ route('admin.participations.info', $programme->id) }}"
   class="breadcrumb-current">
   INFO
</a>
        </div>
    </div>
</div>


<div class="pa-wrap pa-info-page">
    <div class="pa-grid-2">

        <a href="{{ route('admin.participations.participant_list', $programme->id) }}" class="pa-hub-card">
            <div class="pa-hub-title">Participant List</div>
            <div class="pa-hub-desc">
                View participant details.
            </div>
        </a>

        <a href="{{ route('admin.participations.form', $programme->id) }}" class="pa-hub-card">
            <div class="pa-hub-title">Participation Form</div>
            <div class="pa-hub-desc">
                Setup form.
            </div>
        </a>

    </div>
</div>
@endsection
