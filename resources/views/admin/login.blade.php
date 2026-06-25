@extends('layouts.app')

@section('title', 'Login')

@section('content')
<link rel="stylesheet" href="/assets/css/login.css">

<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        
        <div class="breadcrumb-title">LOGIN</div>

        <div class="breadcrumb-path">
            <a href="/">
                <img src="/assets/icons/Home.png" class="breadcrumb-home-icon">
            </a>

            <span>/</span>

            <a href="/admin/login" class="breadcrumb-link">
                LOGIN
            </a>
        </div>

    </div>
</div>

<!-- LOGIN SECTION -->
<div class="login-container">

    <div class="login-card">

        <h3 class="login-title">Enter your email and password.</h3>

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            @if ($errors->any())
        <p style="color:red; margin-bottom:10px;">
            {{ $errors->first() }}
        </p>
    @endif
            <!-- Email -->
            <div class="form-group">
                <label>EMAIL :</label>
                <div class="input-wrapper">
                    <input type="email" name="email" required>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>PASSWORD:</label>
                <div class="input-wrapper">
                    <input type="password" name="password" required>
                    <span class="input-icon toggle-password">
                        <img src="/assets/icons/password-hidden.png">
                    </span>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="login-btn">LOG IN</button>

        </form>

    </div>

</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {

    const toggle = document.querySelector('.toggle-password');
    const passwordInput = toggle.previousElementSibling;

    toggle.addEventListener('click', function () {

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggle.querySelector("img").src = "/assets/icons/password-display.png";
        } 
        else {
            passwordInput.type = "password";
            toggle.querySelector("img").src = "/assets/icons/password-hidden.png";
        }
    });

});
</script>
