@extends('layouts.app')
@section('title', 'Submission Success')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/public-participation.css') }}">

<div class="pp-wrap">
    <div class="pp-card" style="text-align:center;">
        <h2 style="margin:0 0 10px 0; font-weight:900; color:#111827;">Submission Successful</h2>

        <div class="pp-note" style="font-size:14px;">
            YOUR SUBMISSION HAS BEEN SUCCESSFULLY RECORDED.<br>
            SECRETARIAT WILL BE IN TOUCH SOON.
        </div>

        <div class="pp-actions" style="justify-content:center; margin-top:18px;">
            <a class="pp-btn pp-btn-primary" href="{{ route('participation.public.form', $programme->public_token) }}">
                NEW SUBMISSION
            </a>

            <a class="pp-btn pp-btn-outline" href="/">
                HOME
            </a>
        </div>
    </div>
</div>
@endsection
