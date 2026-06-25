@extends('layouts.app')

@section('title', 'Fundraising')

@section('content')
<link rel="stylesheet" href="/assets/css/fundraisers.css">

<!-- Breadcrumb -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title">FUNDRAISING</div>
        <div class="breadcrumb-path">
            <a href="/">
                <img src="/assets/icons/Home.png" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <span class="breadcrumb-current">FUNDRAISING</span>
        </div>
    </div>
</div>

<div class="hero-section">
    <div class="hero-overlay">
        <div class="hero-content">

            <div class="hero-text-wrapper">
                <h1 class="hero-title">Fundraising</h1>
                <p class="hero-subtitle">
                    Turning collective support into long-term impact for Kedah's next generation.<br>
                    #EmpoweringLives
                </p>
            </div>

        </div>
    </div>
</div>

<!-- Active Programme Section -->
<div class="programme-section">
    <div class="container">
        <h2 class="section-title">Active Programme</h2>
        
        <div class="programme-grid">
            
            @forelse($fundraisers as $fundraiser)
                <!-- Programme Card -->
                <a href="{{ route('fundraiser.detail', $fundraiser->id) }}" class="programme-card">
                    <div class="programme-image">
                        @if($fundraiser->image_path)
                            <img src="{{ asset('storage/' . $fundraiser->image_path) }}" 
                                 alt="{{ $fundraiser->programme_name }}"
                                 onerror="this.onerror=null; this.parentElement.classList.add('no-image');">
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="programme-info">
                        <h3 class="programme-name">{{ strtoupper($fundraiser->programme_name) }}</h3>
                    </div>
                </a>
            @empty
                <div class="no-programmes">
                    <p>No active fundraising at the moment. Please check back later!</p>
                </div>
            @endforelse

        </div>
    </div>
</div>

<!-- Back to Top Button -->
<div class="back-to-top">
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="back-to-top-btn">
        <i class="fas fa-chevron-up"></i>
    </button>
</div>

@endsection