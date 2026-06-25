@php
    $visiblePrograms = App\Models\Program::where('is_visible', true)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
@endphp

<footer class="footer">

    <div class="footer-col">
        <h4>Kedah State Executive Council, Industry & Investment, Higher Education and Science, Technology & Innovation</h4>
        <p>Address:<br>
           Aras 5 (Zon A), Blok E, Wisma Darul Aman,<br>
           05503 Alor Setar, Kedah Darul Aman</p>
        <p>Phone:<br>
        En. Mu'ti Invest Kedah - 012-4678587<br>
        <br>En. Iqbal Pejabat Exco - 017-4101239<br> </p>
        <p>Email:<br>
        majlisapresiasi@kedah.gov.my<br> </p>
    </div>

    @if($visiblePrograms->count() > 0)
    <div class="footer-col" style = " text-transform:uppercase;">
        <h4>Programmes</h4>
        @foreach($visiblePrograms as $program)
        <p>
            <a href="{{ route('programs.show', $program->id) }}">
                {{ $program->title }}
            </a>
        </p>
        @endforeach
    </div>
    @endif

    <div class="footer-col">
        <h4>
            <a href="{{ url('/fundraisers') }}" class="fundraiser-link" style = " text-transform:uppercase;">Fundraising</a>
        </h4>
    </div>

    <div class="footer-logo">
        <img src="{{ asset('assets/images/tgk-footer.png') }}">
    </div>
</footer>

<div class="footer-bottom">
    © All Rights Reserved TGK EVENTS
</div>