@extends('layouts.app')
@section('title', 'Form Not Active')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/public-participation.css') }}">

<div class="pp-wrap" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="pp-card" style="max-width: 600px; text-align: center;">
        
        {{-- Icon --}}
        <div style="font-size: 80px; margin-bottom: 24px; color: #DC2626;">
            🔒
        </div>
        
        {{-- Title --}}
        <h1 style="margin: 0 0 16px 0; color: #DC2626; font-size: 28px; font-weight: 700;">
            Registration Form Not Active
        </h1>
        
        {{-- Programme Info --}}
        <div style="background: #FEF2F2; border: 2px solid #FCA5A5; border-radius: 12px; padding: 20px; margin: 24px 0;">
            <h3 style="margin: 0 0 12px 0; color: #991B1B; font-size: 18px;">
                {{ $programme->title }}
            </h3>
            
            @if($programme->venue)
                <p style="margin: 0 0 8px 0; color: #7F1D1D; font-size: 14px;">
                    📍 <strong>Venue:</strong> {{ $programme->venue }}
                </p>
            @endif
            
            @if($programme->start_date)
                <p style="margin: 0; color: #7F1D1D; font-size: 14px;">
                    📅 <strong>Date:</strong> 
                    {{ $programme->start_date->format('d/m/Y') }}
                    @if($programme->end_date && !$programme->start_date->eq($programme->end_date))
                        - {{ $programme->end_date->format('d/m/Y') }}
                    @endif
                </p>
            @endif
        </div>
        
        {{-- Message --}}
        <div style="background: #FFFBEB; border-left: 4px solid #F59E0B; padding: 16px; margin: 24px 0; text-align: left;">
            <p style="margin: 0 0 12px 0; color: #92400E; font-size: 15px; line-height: 1.6;">
                <strong>⚠️ Registration is currently closed</strong>
            </p>
            <p style="margin: 0; color: #92400E; font-size: 14px; line-height: 1.6;">
                This registration form has been temporarily disabled by the secretariat. 
                This could be due to:
            </p>
            <ul style="margin: 12px 0 0 20px; padding: 0; color: #92400E; font-size: 14px;">
                <li>Registration period has ended</li>
                <li>Event capacity has been reached</li>
                <li>Form is under maintenance</li>
                <li>Programme has been postponed or cancelled</li>
            </ul>
        </div>
        
        {{-- Contact Info --}}
        <div style="background: #F0FDF4; border: 2px solid #BBF7D0; border-radius: 12px; padding: 20px; margin: 24px 0; text-align: left;">
            <p style="margin: 0; color: #15803D; font-size: 14px; line-height: 1.6;">
                Please contact the TGK Events secretariat for more information about this programme
            </p>
        </div>
        
        {{-- Action Buttons --}}
        <div style="display: flex; gap: 12px; justify-content: center; margin-top: 32px;">
            <a href="/" class="pp-btn pp-btn-primary" style="text-decoration: none;">
                BACK TO HOME
            </a>
            <button onclick="window.location.reload()" class="pp-btn pp-btn-outline">
                🔄 REFRESH PAGE
            </button>
        </div>
        
        {{-- Footer Note --}}
        <p style="margin-top: 32px; color: #9CA3AF; font-size: 12px;">
            This page will automatically update when registration reopens.
        </p>
    </div>
</div>

<style>
/* Button Styles */
.pp-btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: inline-block;
}

.pp-btn-primary {
    background: #00542A;
    color: white;
    border-color: #00542A;
}

.pp-btn-primary:hover {
    background: #003D1F;
    border-color: #003D1F;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 84, 42, 0.3);
}

.pp-btn-outline {
    background: white;
    color: #00542A;
    border-color: #00542A;
}

.pp-btn-outline:hover {
    background: #F0FDF4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 84, 42, 0.15);
}

/* Card Styles */
.pp-card {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .pp-card {
        padding: 24px;
        margin: 20px;
    }
    
    div[style*="display: flex"] {
        flex-direction: column;
    }
    
    .pp-btn {
        width: 100%;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.pp-wrap {
    animation: fadeIn 0.5s ease-out;
}
</style>

@endsection