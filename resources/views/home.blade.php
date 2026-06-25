@extends('layouts.app')

@section('title', 'Home - Kedah Forward')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">

@push('styles')
<style>
.award-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.award-header {
    text-align: center;
    margin-bottom: 40px;
}

.award-header h1 {
    font-size: 2.5em;
    color: #2c5f2d;
    margin-bottom: 20px;
}

.award-header img {
    max-width: 400px;
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.award-description {
    font-size: 1.1em;
    line-height: 1.8;
    color: #555;
    margin: 20px 0;
}

.award-sections {
    margin-top: 40px;
}

.award-section {
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.award-section h2 {
    color: #2c5f2d;
    font-size: 1.8em;
    margin-bottom: 20px;
    border-bottom: 3px solid #2c5f2d;
    padding-bottom: 10px;
}

.award-section .banner-image {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 20px;
}

.award-section .content {
    line-height: 1.8;
    color: #333;
}

.award-section .content ul {
    padding-left: 30px;
    margin: 15px 0;
}

.award-section .content li {
    margin: 10px 0;
}
/* Remove any gap above the hero */
.hero {
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    top: 0;
}

/* Make background touch the navbar */
.hero-bg {
    margin: 0 !important;
    padding: 0 !important;
    height: 100vh; /* full screen */
    background-size: cover;
    background-position: center;
}

/* Remove body default spacing */
body {
    margin: 0 !important;
    padding: 0 !important;
}

</style>
@endpush

<!-- =========================
     HERO SECTION
========================== -->
<section class="hero">
    <div class="hero-bg" style="background-image: url('{{ asset('assets/images/bg.png') }}');"></div>
    <h1 class="hero-title">THE GREATER KEDAH <br>EVENTS</h1>
</section>

<!-- Back to Top Button -->
<div class="back-to-top">
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="back-to-top-btn">
        <i class="fas fa-chevron-up"></i>
    </button>
</div>

@endsection
