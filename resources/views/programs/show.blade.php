@extends('layouts.app')

@section('title', $program->title)

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/program-show.css') }}">

<!-- PAGE HEADER -->
<div class="kf-page-header">
    <div class="kf-page-header-inner">
        @php
            // Map section names to display titles matching sidebar
            $sectionTitles = [
                'overview' => 'Overview',
                'tentative' => 'Programme Tentative',
                'vip' => 'VIP',
                'participation' => 'Participation',
                'sponsorship' => 'Sponsorship',
                'photo' => 'Photo of The Event',
                'programme' => 'Key Initiatives & Achievements',
                'participant-list' => 'Participant List'
            ];
            
            $displayTitle = $sectionTitles[$section] ?? ucfirst(str_replace('-', ' ', $section));
        @endphp
        
        <h1 class="kf-page-title">{{ strtoupper($displayTitle) }}</h1>
        <div class="kf-breadcrumb">
            <a href="/" class="kf-breadcrumb-link">
                <img src="{{ asset('assets/icons/Home.png') }}" class="kf-breadcrumb-home-icon">
            </a>
            <span>/</span>
            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'overview']) }}" 
               class="kf-breadcrumb-link">
                {{ $program->title }}
            </a>
            <span>/</span>
            <span>{{ $displayTitle }}</span>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="program-container">
    <div class="tgk-sub-header">
        <div class="tgk-section-title">{{ strtoupper($displayTitle) }}</div>
        <div class="tgk-program-title">{{ strtoupper($program->title) }}</div>
        <div class="tgk-program-underline"></div>
    </div>

@php
    $visibleSections = $program->visible_sections ?? [];

    if (is_string($visibleSections)) {
        $visibleSections = json_decode($visibleSections, true) ?? [];
    }

    $isSectionVisible = fn($s) =>
        isset($visibleSections[$s]) && $visibleSections[$s] === true;
        
@endphp


<!-- OVERVIEW -->
@if($section === 'overview' && $isSectionVisible('overview'))
<div class="program-section">
    @if(!empty($program->introduction))
    <div class="content expandable-section">
        <h4>Introduction</h4>
        <div class="expandable-text">
            @if(is_array($program->introduction))
                @foreach($program->introduction as $p)
                    @if(!empty(trim($p)))
                        <p>{{ $p }}</p>
                    @endif
                @endforeach
            @else
                <p>{{ $program->introduction }}</p>
            @endif
        </div>
        <div class="fade-bottom"></div>
        <button class="see-more-btn" style="display:none;">See More</button>
    </div>
    @endif

    @if(!empty($program->background))
    <div class="content expandable-section" style="margin-top:30px;">
        <h4>Background</h4>
        <div class="expandable-text">
            @if(is_array($program->background))
                @foreach($program->background as $p)
                    @if(!empty(trim($p)))
                        <p>{{ $p }}</p>
                    @endif
                @endforeach
            @else
                <p>{{ $program->background }}</p>
            @endif
        </div>
        <div class="fade-bottom"></div>
        <button class="see-more-btn" style="display:none;">See More</button>
    </div>
    @endif

    @if(!empty($program->objectives))
    <div class="content" style="margin-top: 30px;">
        <h4>Objectives</h4>
        @if(is_array($program->objectives))
            <ul>
                @foreach($program->objectives as $obj)
                    @if(!empty(trim($obj)))
                        <li>{{ $obj }}</li>
                    @endif
                @endforeach
            </ul>
        @else
            <p>{{ $program->objectives }}</p>
        @endif
    </div>
    @endif
</div>
@endif

<!-- TENTATIVE -->
@if($section === 'tentative' && $isSectionVisible('tentative'))
<div class="program-section">
    <div class="info-grid">
        @if($program->event_date)
        <div class="info-item">
            <label>Date</label>
            <span>{{ \Carbon\Carbon::parse($program->event_date)->format('d/m/Y') }}</span>
        </div>
        @endif
        @if($program->event_time)
        <div class="info-item">
            <label>Time</label>
            <span>{{ \Carbon\Carbon::parse($program->event_time)->format('h:i A') }}</span>
        </div>
        @endif
        @if($program->location)
        <div class="info-item">
            <label>Venue</label>
            <span>{{ strtoupper($program->location) }}</span>
        </div>
        @endif
        @if($program->theme)
        <div class="info-item">
            <label>Theme</label>
            <span>{{ strtoupper($program->theme) }}</span>
        </div>
        @endif
    </div>

    @php $schedules = $program->schedules; @endphp
    @if($schedules && is_array($schedules) && count($schedules) > 0)
    <div style="margin-top: 30px;">
        <h4>Tentative</h4>
        <ul class="schedule-list">
            @foreach($schedules as $schedule)
                @if($schedule && is_array($schedule))
                    @php
                        $time = $schedule['time'] ?? '';
                        $description = $schedule['description'] ?? '';
                    @endphp
                    @if($time || $description)
                    <li>
                        <strong>{{ $time }}</strong>
                        <span>{{ $description }}</span>
                    </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endif

<!-- VIP -->
@if($section === 'vip' && $isSectionVisible('vip'))
<div class="program-section">
    @if(is_array($program->vip_list) && count($program->vip_list) > 0)
    <div class="vip-grid">
        @foreach($program->vip_list as $vip)
        <div class="vip-card">
            @if(!empty($vip['image']))
            <img src="{{ asset('storage/' . $vip['image']) }}" alt="{{ $vip['name'] ?? 'VIP' }}">
            @endif
            <div class="vip-info">
                <h4>{{ $vip['name'] ?? '' }}</h4>
                <p>{{ $vip['position'] ?? '' }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif

<!-- PARTICIPATION -->
@if($section === 'participation' && $isSectionVisible('participation'))
<div class="program-section">
    <div id="participation-display" class="participation-container">
        
        <!-- DESCRIPTION SECTION -->
        @php
            $hasDescription = is_array($program->participation_description) && count($program->participation_description) > 0;
        @endphp
        
        @if($hasDescription)
            <div class="participation-description-section">
                <h4>Description</h4>
                <div class="description-content">
                    @foreach($program->participation_description as $p)
                        @if(!empty(trim($p)))
                            <p>{!! nl2br(e($p)) !!}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

       
<!-- PRICE SECTION -->
        @php
            $hasValidPrices = false;
            if (is_array($program->participation_prices)) {
                foreach ($program->participation_prices as $price) {
                    if (!empty($price['description']) || !empty($price['amount'])) {
                        $hasValidPrices = true;
                        break;
                    }
                }
            }
        @endphp

        @if($hasValidPrices)
            <div class="participation-price-section">
                <h4>Price</h4>
                <div class="price-list">
                    @foreach($program->participation_prices as $price)
                        @if(!empty($price['description']) || !empty($price['amount']))
                            <div class="price-item">
                                <div class="price-name">
                                    {{ !empty($price['description']) ? $price['description'] : 'Per person' }}
                                </div>
                                <div class="price-amount">
                                    {{ !empty($price['amount']) ? 'RM ' . $price['amount'] : 'RM' }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        
        @endif

        <!-- ADDITIONAL FILES SECTION - Hide completely if none exist -->
        @if($program->participation_additional_files)
            <div class="participation-additional-files-section">
                <h4>Additional Resources</h4>
                <div class="file-download-wrapper">
                    <a href="{{ asset('storage/' . $program->participation_additional_files) }}" 
                       class="download-button" 
                       target="_blank">
                        <span class="download-icon">📄</span>
                        Download Document
                    </a>
                </div>
            </div>
        @endif

        <!-- FORM SECTION -->
        @php
            $hasForm = !empty($program->participation_form);
        @endphp

        @if($hasForm)
            <div class="participation-form-section">
                <h4>Participation Form</h4>
                
                @if($program->participation_form_type === 'file')
                    <div class="form-file-wrapper">
                        <a href="{{ asset('storage/' . $program->participation_form) }}" 
                           class="form-button" 
                           target="_blank">
                            <span class="form-icon">📋</span>
                            View Form
                        </a>
                    </div>
                @elseif($program->participation_form_type === 'link')
                    <div class="form-link-wrapper">
                        <a href="{{ $program->participation_form }}" 
                           class="form-button external-link" 
                           target="_blank">
                            <span class="form-icon">🔗</span>
                            Open Form
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="participation-form-section not-applicable">
                <h4>Participation Form</h4>
                <p class="not-applicable-text">Not applicable</p>
            </div>
        @endif

    </div>
</div>
@endif

<!-- SPONSORSHIP -->
@if($section === 'sponsorship' && $isSectionVisible('sponsorship'))
<div class="program-section">
    <div id="sponsorship-display" class="sponsorship-container">
        
        <!-- DESCRIPTION SECTION -->
        @php
            $hasDescription = is_array($program->sponsorship_description) && count($program->sponsorship_description) > 0;
        @endphp
        
        @if($hasDescription)
            <div class="sponsorship-description-section">
                <h4>Description</h4>
                <div class="description-content">
                    @foreach($program->sponsorship_description as $p)
                        @if(!empty(trim($p)))
                            <p>{{ $p }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- PACKAGE SECTION -->
        @php
            $hasValidPackages = false;
            if (is_array($program->sponsorship_packages)) {
                foreach ($program->sponsorship_packages as $package) {
                    if (!empty($package['description']) || !empty($package['amount'])) {
                        $hasValidPackages = true;
                        break;
                    }
                }
            }
        @endphp

@if($hasValidPackages)
    <div class="sponsorship-package-section">
        <h4>Sponsorship Packages</h4>
        <div class="package-table-wrapper">
            <table class="package-table">
                <tbody>
                    @foreach($program->sponsorship_packages as $package)
                        @if(!empty($package['description']) || !empty($package['amount']))
                            <tr class="package-row">
                                <td class="package-amount-cell">
                                    <div class="package-amount-badge">
                                        {{ !empty($package['amount']) ? '' . $package['amount'] : 'Contact for pricing' }}
                                    </div>
                                </td>
                                <td class="package-name-cell">{{ $package['description'] ?? 'Package' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="sponsorship-package-section no-information">
        <h4>Sponsorship Packages</h4>
        <p class="no-info-text">No information available</p>
    </div>
@endif

        <!-- ADDITIONAL FILES SECTION - Hide completely if none exist -->
        @if($program->sponsorship_additional_files)
            <div class="sponsorship-additional-files-section">
                <h4>Additional Resources</h4>
                <div class="file-download-wrapper">
                    <a href="{{ asset('storage/' . $program->sponsorship_additional_files) }}" 
                       class="download-button" 
                       target="_blank">
                        <span class="download-icon">📄</span>
                        Download Document
                    </a>
                </div>
            </div>
        @endif

        <!-- FORM SECTION -->
        @php
            $hasForm = !empty($program->sponsorship_form);
        @endphp

        @if($hasForm)
            <div class="sponsorship-form-section">
                <h4>Sponsorship Form</h4>
                
                @if($program->sponsorship_form_type === 'file')
                    <div class="form-file-wrapper">
                        <a href="{{ asset('storage/' . $program->sponsorship_form) }}" 
                           class="form-button" 
                           target="_blank">
                            <span class="form-icon">📋</span>
                            View Form
                        </a>
                    </div>
                @elseif($program->sponsorship_form_type === 'link')
                    <div class="form-link-wrapper">
                        <a href="{{ $program->sponsorship_form }}" 
                           class="form-button external-link" 
                           target="_blank">
                            <span class="form-icon">🔗</span>
                            Open Form
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="sponsorship-form-section not-applicable">
                <h4>Sponsorship Form</h4>
                <p class="not-applicable-text">Not applicable</p>
            </div>
        @endif

    </div>
</div>
@endif

<!-- PROGRAMME -->
@if($section === 'programme' && $isSectionVisible('programme'))
<div class="program-section">
    @php $programmeItems = $program->programmeItems; @endphp

    @if($programmeItems && $programmeItems->count() > 0)
        @foreach($programmeItems as $item)
        <div class="programme-item-public">
            <h3 class="programme-item-title">{{ strtoupper($item->title) }}</h3>
            
            @if($item->images && count($item->images) > 0)
            <div class="programme-item-gallery">
                @foreach($item->images as $image)
                <div class="programme-gallery-item">
                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}">
                </div>
                @endforeach
            </div>
            @endif

            @php
                $description = is_string($item->description) 
                    ? json_decode($item->description, true) 
                    : $item->description;
            @endphp
            
            @if($description && is_array($description) && count($description) > 0)
            <div class="content programme-description">
                @foreach($description as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    @else
        <div class="content">
            <p style="text-align: center; color: #666;">No programme items available.</p>
        </div>
    @endif
</div>
@endif

<!-- PHOTO SECTION -->
@if($section === 'photo' && $isSectionVisible('photo'))
<div class="program-section">

    @php
        // Safety: ensure collection exists
        $photoItems = $program->photoItems ?? collect();
    @endphp

    @if($photoItems->count() > 0)

        {{-- PHOTO COUNT --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <p style="color: #00542A; font-size: 16px; font-weight: 600;">
                <span style="font-size: 24px;">📸</span>
                {{ $photoItems->count() }} {{ $photoItems->count() === 1 ? 'Photo' : 'Photos' }}
            </p>
        </div>

        {{-- PHOTO GRID --}}
        <div class="photo-gallery">
            @foreach($photoItems as $index => $photo)
                @php
                    // Build correct public URL
                    $imageUrl = asset('storage/' . ltrim($photo->image, '/'));
                @endphp

                <div class="photo-gallery-item"
                     onclick="openPhotoModal({{ $index }})"
                     data-index="{{ $index }}"
                     data-image="{{ $imageUrl }}">

                    <img
                        src="{{ $imageUrl }}"
                        alt="Program Photo {{ $index + 1 }}"
                        class="photo-image"
                        loading="lazy"
                    >

                    <div class="photo-overlay">
                        <div class="photo-zoom-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                        </div>
                        <div class="photo-overlay-text">Click to enlarge</div>
                    </div>

                </div>
            @endforeach
        </div>

    @else
        {{-- EMPTY STATE --}}
        <div class="content" style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 72px; margin-bottom: 20px; opacity: 0.3;">📷</div>
            <p style="color: #666; font-size: 18px; margin-bottom: 10px;">
                No photos available yet
            </p>
            <p style="color: #999; font-size: 14px;">
                Check back later for event photos
            </p>
        </div>
    @endif

</div>
@endif

{{-- PARTICIPANT LIST SECTION --}}
@if($section === 'participant-list' && isset($program->visible_sections['link-participation']) && $program->visible_sections['link-participation'])
<section id="participant-list-section" class="program-section">
    <div class="container">
        <h2 class="section-title">PARTICIPANT LIST</h2>
        
        @if($participantData && $participantData['submissions']->count() > 0)
            <!-- Stats Cards -->
            <div class="participant-stats-public">
                <div class="stat-card-public">
                    <div class="stat-icon-public">🏢</div>
                    <div class="stat-content-public">
                        <div class="stat-value-public">{{ $participantData['total_companies'] }}</div>
                        <div class="stat-label-public">Companies</div>
                    </div>
                </div>
                <div class="stat-card-public">
                    <div class="stat-icon-public">👥</div>
                    <div class="stat-content-public">
                        <div class="stat-value-public">{{ $participantData['total_participants'] }}</div>
                        <div class="stat-label-public">Participants</div>
                    </div>
                </div>
            </div>
<!-- Search Bar -->
<div class="participant-search-wrapper">
    <input 
        type="text" 
        id="participantSearch" 
        placeholder="Search by company or participant name..."
        class="participant-search-input"
    >
</div>
            <!-- Participant Table -->
            <div class="participant-table-public-wrapper">
                <table class="participant-table-public">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 250px;">COMPANY</th>
                            <th>PARTICIPANT NAME</th>
                            <th style="width: 200px;">POSITION</th>
                            <th style="width: 120px;">TABLE NO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participantData['submissions'] as $index => $submission)
                            @php $rowCount = $submission->participants->count(); @endphp
                            @foreach($submission->participants as $pIndex => $participant)
                                <tr>
                                    @if($pIndex === 0)
                                        <td rowspan="{{ $rowCount }}" class="table-center table-index-public">{{ $index + 1 }}</td>
                                        <td rowspan="{{ $rowCount }}" class="table-company-public">{{ $submission->company_name }}</td>
                                    @endif
                                    <td class="table-name-public">{{ $participant->name }}</td>
                                    <td class="table-position-public">{{ $participant->position ?? '-' }}</td>
                                    <td class="table-center table-table-no-public">{{ $participant->table_number ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- No Participants -->
            <div class="empty-state-public">
                <div class="empty-icon-public">📭</div>
                <h3>No Participants Yet</h3>
                <p>Participant information will be displayed here once registrations are approved.</p>
            </div>
        @endif
    </div>
</section>
@endif

</div>
<!-- END CONTAINER -->

<!-- PHOTO MODAL (WITH DOWNLOAD) -->
<div id="photoModal" class="photo-modal" onclick="closePhotoModal()">
    <span class="photo-modal-close" onclick="event.stopPropagation(); closePhotoModal()">&times;</span>
    
    <!-- Download Button -->
    <a id="photoDownloadBtn" class="photo-modal-download" onclick="event.stopPropagation()" download>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
    </a>
    
    @if($section === 'photo' && isset($photoItems) && $photoItems && $photoItems->count() > 1)
    <button class="photo-modal-prev" onclick="event.stopPropagation(); navigatePhoto(-1)">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <button class="photo-modal-next" onclick="event.stopPropagation(); navigatePhoto(1)">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>
    @endif
    
    <img class="photo-modal-content" id="modalImage" onclick="event.stopPropagation()">
    <div class="photo-modal-counter" id="photoCounter"></div>
</div>

<script>
let currentPhotoIndex = 0;
let totalPhotos = 0;
let photoElements = [];

document.addEventListener("DOMContentLoaded", function () {
    photoElements = Array.from(document.querySelectorAll('.photo-gallery-item'));
    totalPhotos = photoElements.length;
    
    // Expandable sections code...
    document.querySelectorAll(".expandable-section").forEach(section => {
        const textBlock = section.querySelector(".expandable-text");
        const btn = section.querySelector(".see-more-btn");
        const fade = section.querySelector(".fade-bottom");
        if (!textBlock || !btn) return;
        
        const collapsedHeight = textBlock.clientHeight;
        textBlock.style.maxHeight = "none";
        const fullHeight = textBlock.scrollHeight;
        textBlock.style.maxHeight = collapsedHeight + "px";
        
        if (fullHeight <= collapsedHeight + 5) {
            btn.style.display = "none";
            fade.style.display = "none";
            return;
        }
        
        btn.style.display = "inline-flex";
        btn.addEventListener("click", function () {
            const isExpanded = section.classList.contains("expanded");
            if (isExpanded) {
                section.classList.remove("expanded");
                textBlock.classList.remove("expanded");
                textBlock.style.maxHeight = collapsedHeight + "px";
                fade.style.opacity = 1;
                btn.textContent = "See More";
            } else {
                section.classList.add("expanded");
                textBlock.classList.add("expanded");
                textBlock.style.maxHeight = textBlock.scrollHeight + "px";
                fade.style.opacity = 0;
                btn.textContent = "See Less";
            }
        });
    });
});

window.openPhotoModal = function(index) {
    const modal = document.getElementById('photoModal');
    const modalImg = document.getElementById('modalImage');
    const counter = document.getElementById('photoCounter');
    const downloadBtn = document.getElementById('photoDownloadBtn');
    
    if (!modal || !modalImg || photoElements.length === 0) return;
    
    currentPhotoIndex = index;
    const photoElement = photoElements[index];
    const imageSrc = photoElement.getAttribute('data-image');
    
    modal.style.display = 'flex';
    modalImg.src = imageSrc;
    
    // Update download button href
    if (downloadBtn) {
        downloadBtn.href = imageSrc;
        downloadBtn.download = `photo-${index + 1}.jpg`;
    }
    
    if (counter && totalPhotos > 0) {
        counter.textContent = `${index + 1} / ${totalPhotos}`;
    }
    
    document.body.style.overflow = 'hidden';
    setTimeout(() => { modalImg.style.opacity = '1'; }, 10);
}

window.navigatePhoto = function(direction) {
    currentPhotoIndex += direction;
    if (currentPhotoIndex < 0) currentPhotoIndex = totalPhotos - 1;
    else if (currentPhotoIndex >= totalPhotos) currentPhotoIndex = 0;
    
    const modalImg = document.getElementById('modalImage');
    const counter = document.getElementById('photoCounter');
    const downloadBtn = document.getElementById('photoDownloadBtn');
    
    modalImg.style.opacity = '0';
    
    setTimeout(() => {
        const photoElement = photoElements[currentPhotoIndex];
        const imageSrc = photoElement.getAttribute('data-image');
        modalImg.src = imageSrc;
        
        // Update download button
        if (downloadBtn) {
            downloadBtn.href = imageSrc;
            downloadBtn.download = `photo-${currentPhotoIndex + 1}.jpg`;
        }
        
        if (counter) counter.textContent = `${currentPhotoIndex + 1} / ${totalPhotos}`;
        setTimeout(() => { modalImg.style.opacity = '1'; }, 10);
    }, 200);
}

window.closePhotoModal = function() {
    const modal = document.getElementById('photoModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(event) {
    const modal = document.getElementById('photoModal');
    if (modal && modal.style.display === 'flex') {
        if (event.key === 'Escape') closePhotoModal();
        else if (event.key === 'ArrowLeft') navigatePhoto(-1);
        else if (event.key === 'ArrowRight') navigatePhoto(1);
    }
});
document.getElementById('participantSearch').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();

    // Each company group = first row with rowspan
    const companyCells = document.querySelectorAll('.table-company-public');

    companyCells.forEach(companyCell => {
        const firstRow = companyCell.closest('tr');
        const rowspan = parseInt(companyCell.getAttribute('rowspan')) || 1;

        // Collect all rows belonging to this company
        const rows = [firstRow];
        let nextRow = firstRow;

        for (let i = 1; i < rowspan; i++) {
            nextRow = nextRow.nextElementSibling;
            if (nextRow) rows.push(nextRow);
        }

        // Combine text of entire group
        const groupText = rows.map(r => r.innerText.toLowerCase()).join(' ');

        const match = groupText.includes(keyword);

        rows.forEach(r => {
            r.style.display = match ? '' : 'none';
        });
    });
});
</script>
<style>
    .participant-search-wrapper {
    display: flex;
    justify-content: flex-end;
    margin: 20px 0 12px;
}

.participant-search-input {
    width: 320px;
    max-width: 100%;
    padding: 10px 14px;
    border: 1px solid #d1fae5;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: border 0.2s, box-shadow 0.2s;
}

.participant-search-input:focus {
    border-color: #00542A;
    box-shadow: 0 0 0 2px rgba(0, 84, 42, 0.1);
}

/* ============= PUBLIC PARTICIPANT LIST SECTION ============= */

.participant-stats-public {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

.stat-card-public {
    background: linear-gradient(135deg, #f0f9f7 0%, #e8f5f3 100%);
    border: 2px solid #d4ebe5;
    border-radius: 16px;
    padding: 30px;
    display: flex;
    align-items: center;
    gap: 24px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 84, 42, 0.08);
}

.stat-card-public:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(0, 84, 42, 0.15);
    border-color: #00542A;
}

.stat-icon-public {
    font-size: 56px;
    line-height: 1;
}

.stat-content-public {
    flex: 1;
}

.stat-value-public {
    font-size: 42px;
    font-weight: 700;
    color: #00542A;
    line-height: 1;
    margin-bottom: 10px;
}

.stat-label-public {
    font-size: 16px;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.participant-table-public-wrapper {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.participant-table-public {
    width: 100%;
    border-collapse: collapse;
}

.participant-table-public thead {
    background: linear-gradient(135deg, #00542A 0%, #007844 100%);
    color: white;
}

.participant-table-public thead th {
    padding: 20px 24px;
    text-align: left;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    border-bottom: 3px solid #00542A;
}

.participant-table-public tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s ease;
}

.participant-table-public tbody tr:hover {
    background: #f8faf9;
}

.participant-table-public tbody tr:last-child {
    border-bottom: none;
}

.participant-table-public tbody td {
    padding: 18px 24px;
    font-size: 15px;
    color: #374151;
    vertical-align: middle;
}

.table-center {
    text-align: center;
}

.table-index-public {
    font-weight: 700;
    color: #00542A;
    font-size: 18px;
    background: #f0f9f7;
}

.table-company-public {
    font-weight: 600;
    color: #00542A;
    background: #f0f9f7;
    border-right: 3px solid #d4ebe5;
}

.table-name-public {
    font-weight: 500;
    color: #111827;
}

.table-position-public {
    color: #6b7280;
    font-size: 14px;
}

.table-table-no-public {
    font-weight: 600;
    color: #00542A;
    background: #fef9f0;
}

/* Empty State */
.empty-state-public {
    text-align: center;
    padding: 80px 40px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-icon-public {
    font-size: 80px;
    margin-bottom: 24px;
    line-height: 1;
}

.empty-state-public h3 {
    font-size: 24px;
    color: #374151;
    margin: 0 0 12px 0;
}

.empty-state-public p {
    font-size: 16px;
    color: #9ca3af;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .participant-stats-public {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .stat-card-public {
        padding: 20px;
    }
    
    .stat-icon-public {
        font-size: 42px;
    }
    
    .stat-value-public {
        font-size: 32px;
    }
    
    .stat-label-public {
        font-size: 14px;
    }
    
    .participant-table-public-wrapper {
        overflow-x: auto;
    }
    
    .participant-table-public thead th {
        padding: 14px 12px;
        font-size: 11px;
    }
    
    .participant-table-public tbody td {
        padding: 12px;
        font-size: 13px;
    }
}
</style>

<style>
/* ============================================================
   SPONSORSHIP SECTION - CONDITIONAL DISPLAY STYLES
============================================================ */

.sponsorship-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.sponsorship-description-section,
.sponsorship-package-section,
.sponsorship-additional-files-section,
.sponsorship-form-section {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============================================================
   DESCRIPTION SECTION - IMPROVED STYLING
============================================================ */

.sponsorship-description-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 5px solid #00542a;
}

.sponsorship-description-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #00542a;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #00542a;
    padding-bottom: 12px;
    padding-left: 0 !important;
}

.sponsorship-description-section h4::before,
.sponsorship-description-section h4::after {
    display: none !important;
}

.description-content {
    color: #374151;
    line-height: 1.8;
    font-size: 15px;
}

.description-content p {
    margin-bottom: 18px;
    padding: 0;
}

.description-content p:last-child {
    margin-bottom: 0;
}

/* ============================================================
   PACKAGE SECTION - TABLE DESIGN
============================================================ */

.sponsorship-package-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 5px solid #00542a;
}

.sponsorship-package-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #00542a;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #00542a;
    padding-bottom: 12px;
    padding-left: 0 !important;
}

.sponsorship-package-section h4::before,
.sponsorship-package-section h4::after {
    display: none !important;
}

.package-table-wrapper {
    overflow: hidden;
    border-radius: 8px;
}

.package-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.package-row {
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.package-row:last-child {
    border-bottom: none;
}

.package-row:hover {
    background: #f8faf9;
}

.package-amount-cell {
    padding: 0;
    width: 220px;
    vertical-align: middle;
}

.package-amount-badge {
    background: linear-gradient(135deg, #daa85dff 0%, #c08329 100%);
    color: white;
    padding: 16px 20px;
    font-weight: 700;
    font-size: 14px;
    text-align: center;
    border-right: 1px solid #e5e7eb;
}

.package-name-cell {
    padding: 16px 24px;
    color: #374151;
    font-size: 14px;
    line-height: 1.6;
    font-weight: 500;
}

.sponsorship-package-section.no-information {
    background: #f3f4f6;
    border-left: 5px solid #d1d5db;
    padding: 30px;
    border-radius: 8px;
    text-align: center;
    border-bottom: 2px dashed #d1d5db;
}

.no-info-text {
    color: #9ca3af;
    font-size: 14px;
    margin: 0;
    font-style: italic;
}

/* ============================================================
   ADDITIONAL FILES SECTION
============================================================ */

.sponsorship-additional-files-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 5px solid #00542a;
}

.sponsorship-additional-files-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #00542a;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #00542a;
    padding-bottom: 12px;
    padding-left: 0 !important;
}

.sponsorship-additional-files-section h4::before,
.sponsorship-additional-files-section h4::after {
    display: none !important;
}

/* ============================================================
   SPONSORSHIP FORM SECTION
============================================================ */

.sponsorship-form-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 5px solid #00542a;
}

.sponsorship-form-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #00542a;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #00542a;
    padding-bottom: 12px;
    padding-left: 0 !important;
}

.sponsorship-form-section h4::before,
.sponsorship-form-section h4::after {
    display: none !important;
}

.sponsorship-form-section.not-applicable {
    background: #f3f4f6;
    border-left: 5px solid #d1d5db;
    padding: 30px;
    border-radius: 8px;
    text-align: center;
    border-bottom: 2px dashed #d1d5db;
}

.not-applicable-text {
    color: #9ca3af;
    font-size: 14px;
    margin: 0;
    font-style: italic;
}

/* ============================================================
   FILE DOWNLOAD STYLES
============================================================ */

.file-download-wrapper,
.form-file-wrapper,
.form-link-wrapper {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.download-button,
.form-button {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #daa85dff 0%, #c08329 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2);
}

.download-button:hover,
.form-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(166, 122, 0, 0.3);
}

.download-button:active,
.form-button:active {
    transform: translateY(-1px);
}

.download-icon,
.form-icon {
    font-size: 18px;
}

.form-button.external-link {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.form-button.external-link:hover {
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
}

/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 768px) {
    .sponsorship-container {
        gap: 20px;
    }
    
    .sponsorship-description-section,
    .sponsorship-package-section,
    .sponsorship-additional-files-section,
    .sponsorship-form-section {
        padding: 20px;
    }
    
    .sponsorship-description-section h4,
    .sponsorship-package-section h4,
    .sponsorship-additional-files-section h4,
    .sponsorship-form-section h4 {
        font-size: 16px;
        margin-bottom: 15px;
    }
    
    .description-content {
        font-size: 14px;
        line-height: 1.7;
    }
    
    .description-content p {
        margin-bottom: 14px;
    }
    
    .package-amount-cell {
        width: 180px;
    }
    
    .package-amount-badge {
        padding: 14px 16px;
        font-size: 13px;
    }
    
    .package-name-cell {
        padding: 14px 16px;
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .sponsorship-container {
        gap: 15px;
    }
    
    .sponsorship-description-section,
    .sponsorship-package-section,
    .sponsorship-additional-files-section,
    .sponsorship-form-section {
        padding: 16px;
        border-left-width: 4px;
    }
    
    .sponsorship-description-section h4,
    .sponsorship-package-section h4,
    .sponsorship-additional-files-section h4,
    .sponsorship-form-section h4 {
        font-size: 14px;
        margin-bottom: 12px;
    }
    
    .description-content {
        font-size: 13px;
        line-height: 1.6;
    }
    
    .description-content p {
        margin-bottom: 12px;
    }
    
    .package-amount-cell {
        width: 140px;
    }
    
    .package-amount-badge {
        padding: 12px 12px;
        font-size: 11px;
    }
    
    .package-name-cell {
        padding: 12px 12px;
        font-size: 12px;
    }
    
    .download-button,
    .form-button {
        padding: 12px 20px;
        font-size: 13px;
        width: 100%;
        justify-content: center;
    }
}

/* ============================================================
   PARTICIPATION SECTION - CONDITIONAL DISPLAY STYLES
============================================================ */

.participation-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.participation-description-section,
.participation-additional-files-section,
.participation-form-section {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============================================================
   DESCRIPTION SECTION - IMPROVED STYLING
============================================================ */

.participation-description-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 5px solid #00542a;
}

.participation-description-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #00542a;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #00542a;
    padding-bottom: 12px;
    padding-left: 0 !important;
}

.participation-description-section h4::before,
.participation-description-section h4::after {
    display: none !important;
}

.description-content {
    color: #374151;
    line-height: 1.8;
    font-size: 15px;
}

.description-content p {
    margin-bottom: 18px;
    padding: 0;
}

.description-content p:last-child {
    margin-bottom: 0;
}

/* ============================================================
   ADDITIONAL FILES SECTION
============================================================ */

.participation-additional-files-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 5px solid #00542a;
}

.participation-additional-files-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #00542a;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #00542a;
    padding-bottom: 12px;
    padding-left: 0 !important;
}

.participation-additional-files-section h4::before,
.participation-additional-files-section h4::after {
    display: none !important;
}

/* ============================================================
   PARTICIPATION FORM SECTION
============================================================ */

.participation-form-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 5px solid #00542a;
}

.participation-form-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #00542a;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #00542a;
    padding-bottom: 12px;
    padding-left: 0 !important;
}

.participation-form-section h4::before,
.participation-form-section h4::after {
    display: none !important;
}

/* ============================================================
   FILE DOWNLOAD STYLES
============================================================ */

.file-download-wrapper,
.form-file-wrapper,
.form-link-wrapper {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.download-button,
.form-button {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #daa85dff 0%, #c08329 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2);
}

.download-button:hover,
.form-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 166, 81, 0.3);
}

.download-button:active,
.form-button:active {
    transform: translateY(-1px);
}

.download-icon,
.form-icon {
    font-size: 18px;
}

.form-button.external-link {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.form-button.external-link:hover {
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
}

/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 768px) {
    .participation-container {
        gap: 20px;
    }
    
    .participation-description-section,
    .participation-additional-files-section,
    .participation-form-section {
        padding: 20px;
    }
    
    .participation-description-section h4,
    .participation-additional-files-section h4,
    .participation-form-section h4 {
        font-size: 16px;
        margin-bottom: 15px;
    }
    
    .description-content {
        font-size: 14px;
        line-height: 1.7;
    }
    
    .description-content p {
        margin-bottom: 14px;
    }
}

@media (max-width: 480px) {
    .participation-container {
        gap: 15px;
    }
    
    .participation-description-section,
    .participation-additional-files-section,
    .participation-form-section {
        padding: 16px;
        border-left-width: 4px;
    }
    
    .participation-description-section h4,
    .participation-additional-files-section h4,
    .participation-form-section h4 {
        font-size: 14px;
        margin-bottom: 12px;
    }
    
    .description-content {
        font-size: 13px;
        line-height: 1.6;
    }
    
    .description-content p {
        margin-bottom: 12px;
    }
    
    .download-button,
    .form-button {
        padding: 12px 20px;
        font-size: 13px;
        width: 100%;
        justify-content: center;
    }
}
</style>

@endsection