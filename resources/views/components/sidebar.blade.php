@php
    $visiblePrograms = App\Models\Program::where('is_visible', true)
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-menu">

        <!-- HOME -->
        <li>
            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                Home
            </a>
        </li>

        <!-- PROGRAMMES DROPDOWN -->
        @if($visiblePrograms->count() > 0)
        <li class="dropdown">
            <div class="dropdown-header {{ request()->is('programs/*') ? 'open' : '' }}" onclick="toggleProgrammesDropdown(event)">
                <button class="dropdown-arrow {{ request()->is('programs/*') ? 'open' : '' }}"></button>
                <span class="dropdown-title">PROGRAMMES</span>
            </div>

            <ul class="dropdown-menu {{ request()->is('programs/*') ? 'show' : '' }}">
@foreach($visiblePrograms as $program)
    @php
        // Fix: Ensure visible_sections is always an array
        $visibleSections = $program->visible_sections ?? [];
        
        // If it's a JSON string, decode it
        if (is_string($visibleSections)) {
            $visibleSections = json_decode($visibleSections, true) ?? [];
        }
        
        // Ensure it's an array
        if (!is_array($visibleSections)) {
            $visibleSections = [];
        }
        
        $isSectionVisible = function($sectionName) use ($visibleSections) {
            return isset($visibleSections[$sectionName]) 
       && $visibleSections[$sectionName] === true;

        };
    @endphp
                
                <li class="nested-dropdown">
                    <div class="nested-dropdown-header {{ request()->is('programs/'.$program->id.'*') ? 'open' : '' }}" 
                         onclick="toggleProgramNestedDropdown(event)">
                        <button class="nested-dropdown-arrow {{ request()->is('programs/'.$program->id.'*') ? 'open' : '' }}"></button>
                        <span class="nested-dropdown-title">{{ strtoupper($program->title) }}</span>
                    </div>

                    <ul class="nested-dropdown-menu {{ request()->is('programs/'.$program->id.'*') ? 'show' : '' }}">
                        @if($isSectionVisible('overview'))
                        <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'overview']) }}"
                               class="{{ request()->get('section') == 'overview' ? 'active' : '' }}">
                                Overview
                            </a>
                        </li>
                        @endif
                        
                        @if($isSectionVisible('tentative'))
                        <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'tentative']) }}"
                               class="{{ request()->get('section') == 'tentative' ? 'active' : '' }}">
                                Programme Tentative
                            </a>
                        </li>
                        @endif
                        
                        @if($isSectionVisible('vip'))
                        <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'vip']) }}"
                               class="{{ request()->get('section') == 'vip' ? 'active' : '' }}">
                                VIP
                            </a>
                        </li>
                        @endif
                        
                        @if($isSectionVisible('participation'))
                        <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'participation']) }}"
                               class="{{ request()->get('section') == 'participation' ? 'active' : '' }}">
                                Participation
                            </a>
                        </li>
                        @endif
                        
                        @if($isSectionVisible('sponsorship'))
                        <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'sponsorship']) }}"
                               class="{{ request()->get('section') == 'sponsorship' ? 'active' : '' }}">
                                Sponsorship
                            </a>
                        </li>
                        @endif

                        @if($isSectionVisible('photo'))
                       <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'photo']) }}"
                                class="{{ request()->get('section') == 'photo' ? 'active' : '' }}">
                                 Photo of The Event
                                </a>
                            </li>
                        @endif
                        
                        @if($isSectionVisible('programme'))
                        <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'programme']) }}"
                               class="{{ request()->get('section') == 'programme' ? 'active' : '' }}">
                                Key Initiatives & Achievements
                            </a>
                        </li>
                        @endif

                    {{-- PARTICIPANT LIST - FIXED --}}
                    @if($isSectionVisible('link-participation') && $program->participation_programme_id)
                        <li>
                            <a href="{{ route('programs.show', ['id' => $program->id, 'section' => 'participant-list']) }}" 
                            class="{{ request()->get('section') == 'participant-list' ? 'active' : '' }}">
                                Participant List
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endforeach
            </ul>
        </li>
        @endif

        <!-- FUNDRAISER -->
        <li>
            <a href="{{ url('/fundraisers') }}"
               class="{{ request()->is('fundraisers*') ? 'active' : '' }}">
                Fundraising
            </a>
        </li>

    </ul>
</aside>

<style>
.nested-dropdown { position: relative; }

.nested-dropdown-header {
    display: flex;
    align-items: center;
    padding: 12px 20px 12px 40px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 14px;
    color: #00542a;
    font-weight: 500;
}

.nested-dropdown-header:hover { background-color: rgba(13, 92, 60, 0.05); }
.nested-dropdown-header.open { background-color: rgba(13, 92, 60, 0.1); }

.nested-dropdown-arrow {
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid #00542a;
    transition: transform 0.3s;
    margin-right: 10px;
    background: none;
    border-style: solid;
    cursor: pointer;
    padding: 0;
    outline: none;
}

.nested-dropdown-arrow.open { transform: rotate(180deg); }
.nested-dropdown-title { font-size: 13px; font-weight: 600; }

.nested-dropdown-menu {
    display: none;
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: rgba(0, 0, 0, 0.02);
}

.nested-dropdown-menu.show { display: block; }

.nested-dropdown-menu li {
    border-left: 2px solid #e0e0e0;
    margin-left: 50px;
}

.nested-dropdown-menu li a {
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    color: #C08329;
    font-size: 13px;
    transition: all 0.3s;
}

.nested-dropdown-menu li a:hover {
    background-color: rgba(13, 92, 60, 0.05);
    color: #0d5c3c;
    padding-left: 25px;
}

.nested-dropdown-menu li a.active {
    background-color: #0d5c3c;
    color: white;
    border-left: 3px solid #0a4429;
    font-weight: 600;
}
</style>

<script>
function toggleProgrammesDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    const element = event.currentTarget;
    if (!element) return;
    const dropdownMenu = element.nextElementSibling;
    const arrow = element.querySelector('.dropdown-arrow');
    if (element && dropdownMenu && arrow) {
        element.classList.toggle('open');
        arrow.classList.toggle('open');
        dropdownMenu.classList.toggle('show');
    }
}

function toggleProgramNestedDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    const element = event.currentTarget;
    if (!element) return;
    const nestedMenu = element.nextElementSibling;
    const arrow = element.querySelector('.nested-dropdown-arrow');
    if (element && nestedMenu && arrow) {
        element.classList.toggle('open');
        arrow.classList.toggle('open');
        nestedMenu.classList.toggle('show');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const activeLinks = document.querySelectorAll('.nested-dropdown-menu a.active');
        activeLinks.forEach(link => {
            const nestedDropdown = link.closest('.nested-dropdown');
            if (nestedDropdown) {
                const nestedHeader = nestedDropdown.querySelector('.nested-dropdown-header');
                const nestedMenu = nestedDropdown.querySelector('.nested-dropdown-menu');
                const nestedArrow = nestedDropdown.querySelector('.nested-dropdown-arrow');
                if (nestedHeader && nestedMenu && nestedArrow) {
                    nestedHeader.classList.add('open');
                    nestedMenu.classList.add('show');
                    nestedArrow.classList.add('open');
                }
            }
            const mainDropdown = link.closest('.dropdown');
            if (mainDropdown) {
                const mainHeader = mainDropdown.querySelector('.dropdown-header');
                const mainMenu = mainDropdown.querySelector('.dropdown-menu');
                const mainArrow = mainDropdown.querySelector('.dropdown-arrow');
                if (mainHeader && mainMenu && mainArrow) {
                    mainHeader.classList.add('open');
                    mainMenu.classList.add('show');
                    mainArrow.classList.add('open');
                }
            }
        });
    }, 100);
});
</script>