
<?php $__env->startSection('title', 'Programme Detail'); ?>
<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="/assets/css/admin-program-detail.css">
<link rel="stylesheet" href="/assets/css/admin-fundraiser-index.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- ADD THESE TWO SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- ====================== BREADCRUMB ======================= -->
<div class="breadcrumb-wrapper">
    <div class="breadcrumb-inner">
        <div class="breadcrumb-title"><?php echo e(strtoupper($program->title)); ?></div>
        <div class="breadcrumb-path">
            <a href="<?php echo e(route('admin.index')); ?>">
                <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <a href="<?php echo e(route('admin.programs.index')); ?>" class="breadcrumb-link">PROGRAMMES</a>
            <span>/</span>
            <span class="breadcrumb-current"><?php echo e(strtoupper($program->title)); ?></span>
        </div>
    </div>
</div>

<!-- ====================== SECTION NAVIGATION ======================= -->
<div class="section-nav">

    <!-- OVERVIEW -->
    <div class="section-card" data-section="overview">
        <div class="section-top">
            <div class="section-header">OVERVIEW</div>
            <div class="section-actions">
                <button type="button" class="section-edit-btn" onclick="openSection('overview'); return false;">
                    <img src="/assets/icons/update.png" alt="Edit">
                </button>
                <label class="section-toggle">
                    <input type="checkbox"
                           data-section="overview"
                           onchange="toggleSectionDisplay(this); event.stopPropagation();"
                           <?php echo e(isset($program->visible_sections['overview']) ? ($program->visible_sections['overview'] ? 'checked' : '') : 'checked'); ?>>
                    <span class="toggle-slider" onclick="event.stopPropagation();"></span>
                </label>
            </div>
        </div>
        <div class="section-desc">Title, Introduction, Background, Objectives</div>
    </div>

    <!-- PROGRAM TENTATIVE -->
    <div class="section-card" data-section="tentative">
        <div class="section-top">
            <div class="section-header">PROGRAMME TENTATIVE</div>
            <div class="section-actions">
                <button type="button" class="section-edit-btn" onclick="openSection('tentative'); return false;">
                    <img src="/assets/icons/update.png" alt="Edit">
                </button>
                <label class="section-toggle">
                    <input type="checkbox"
                           data-section="tentative"
                           onchange="toggleSectionDisplay(this); event.stopPropagation();"
                           <?php echo e(isset($program->visible_sections['tentative']) ? ($program->visible_sections['tentative'] ? 'checked' : '') : 'checked'); ?>>
                    <span class="toggle-slider" onclick="event.stopPropagation();"></span>
                </label>
            </div>
        </div>
        <div class="section-desc">Add Schedule(Time & Description)</div>
    </div>

    <!-- VIP -->
    <div class="section-card" data-section="vip">
        <div class="section-top">
            <div class="section-header">VIP</div>
            <div class="section-actions">
                <button type="button" class="section-edit-btn" onclick="openSection('vip'); return false;">
                    <img src="/assets/icons/update.png" alt="Edit">
                </button>
                <label class="section-toggle">
                    <input type="checkbox"
                           data-section="vip"
                           onchange="toggleSectionDisplay(this); event.stopPropagation();"
                           <?php echo e(isset($program->visible_sections['vip']) ? ($program->visible_sections['vip'] ? 'checked' : '') : 'checked'); ?>>
                    <span class="toggle-slider" onclick="event.stopPropagation();"></span>
                </label>
            </div>
        </div>
        <div class="section-desc">Name, Position, Images</div>
    </div>

    <!-- PARTICIPATION -->
    <div class="section-card" data-section="participation">
        <div class="section-top">
            <div class="section-header">PARTICIPATION</div>
            <div class="section-actions">
                <button type="button" class="section-edit-btn" onclick="openSection('participation'); return false;">
                    <img src="/assets/icons/update.png" alt="Edit">
                </button>
                <label class="section-toggle">
                    <input type="checkbox"
                           data-section="participation"
                           onchange="toggleSectionDisplay(this); event.stopPropagation();"
                           <?php echo e(isset($program->visible_sections['participation']) ? ($program->visible_sections['participation'] ? 'checked' : '') : 'checked'); ?>>
                    <span class="toggle-slider" onclick="event.stopPropagation();"></span>
                </label>
            </div>
        </div>
        <div class="section-desc">Description, Price, Additional Details, Form</div>
    </div>

    <!-- SPONSORSHIP -->
    <div class="section-card" data-section="sponsorship">
        <div class="section-top">
            <div class="section-header">SPONSORSHIP</div>
            <div class="section-actions">
                <button type="button" class="section-edit-btn" onclick="openSection('sponsorship'); return false;">
                    <img src="/assets/icons/update.png" alt="Edit">
                </button>
                <label class="section-toggle">
                    <input type="checkbox"
                           data-section="sponsorship"
                           onchange="toggleSectionDisplay(this); event.stopPropagation();"
                           <?php echo e(isset($program->visible_sections['sponsorship']) ? ($program->visible_sections['sponsorship'] ? 'checked' : '') : 'checked'); ?>>
                    <span class="toggle-slider" onclick="event.stopPropagation();"></span>
                </label>
            </div>
        </div>
        <div class="section-desc">Description, Package, Additional Details, Form</div>
    </div>

        <!-- PHOTO CARD IN SECTION NAVIGATION -->
<div class="section-card" data-section="photo">
    <div class="section-top">
        <div class="section-header">PHOTO</div>
        <div class="section-actions">
            <button type="button" class="section-edit-btn" onclick="openSection('photo'); return false;">
                <img src="/assets/icons/update.png" alt="Edit">
            </button>
            <label class="section-toggle">
                <input type="checkbox"
                       data-section="photo"
                       onchange="toggleSectionDisplay(this); event.stopPropagation();"
                       <?php echo e(isset($program->visible_sections['photo']) ? ($program->visible_sections['photo'] ? 'checked' : '') : 'checked'); ?>>
                <span class="toggle-slider" onclick="event.stopPropagation();"></span>
            </label>
        </div>
    </div>
    <div class="section-desc">Upload and manage program photos</div>
</div>

    <!-- PROGRAMME -->
    <div class="section-card" data-section="programme">
        <div class="section-top">
            <div class="section-header">KEY INITIATIVES & ACHIEVEMENTS</div>
            <div class="section-actions">
                <button type="button" class="section-edit-btn" onclick="openSection('programme'); return false;">
                    <img src="/assets/icons/update.png" alt="Edit">
                </button>
                <label class="section-toggle">
                    <input type="checkbox"
                           data-section="programme"
                           onchange="toggleSectionDisplay(this); event.stopPropagation();"
                           <?php echo e(isset($program->visible_sections['programme']) ? ($program->visible_sections['programme'] ? 'checked' : '') : 'checked'); ?>>
                    <span class="toggle-slider" onclick="event.stopPropagation();"></span>
                </label>
            </div>
        </div>
        <div class="section-desc">Add Programme (Title, Images, Description)</div>
    </div>

<!-- PARTICIPANT LIST CARD IN SECTION NAVIGATION -->
<div class="section-card" data-section="link-participation">
    <div class="section-top">
        <div class="section-header">PARTICIPANT LIST</div>
        <div class="section-actions">
            <button type="button" class="section-edit-btn" onclick="openSection('link-participation'); return false;">
                <img src="/assets/icons/update.png" alt="Edit">
            </button>
            <label class="section-toggle">
                <input type="checkbox"
                       data-section="link-participation"
                       onchange="toggleSectionDisplay(this); event.stopPropagation();"
                       <?php echo e(isset($program->visible_sections['link-participation']) ? ($program->visible_sections['link-participation'] ? 'checked' : '') : 'checked'); ?>>
                <span class="toggle-slider" onclick="event.stopPropagation();"></span>
            </label>
        </div>
    </div>
    <div class="section-desc">List of participant - company name and participants (name, position and no.table)</div>
</div>
</div>
<!-- ====================== SECTION EDITORS (Hidden by default) ======================= -->

<!-- OVERVIEW SECTION -->
<div id="overview-section" class="section-editor" style="display: none;">
    <div class="section-editor-header">
        <h2>OVERVIEW</h2>
        <button class="close-section-btn" onclick="closeSection()">x</button>
    </div>
    <div class="breadcrumb-path" style="margin-bottom: 20px;">
        <a href="<?php echo e(route('admin.index')); ?>">
            <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
        </a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
        <span>/</span>
        <span style="color: #111827; font-weight: 600;">OVERVIEW</span>
    </div>

    <form id="overviewForm" class="section-form" onsubmit="saveOverview(event)">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>TITLE</label>
            <input type="text" 
       name="title" 
       class="form-control" 
       value="<?php echo e($program->title); ?>" 
       readonly
       style="text-transform: uppercase !important; background:#f3f4f6; cursor:not-allowed;">
        </div>

        <div class="form-group">
            <label>INTRODUCTION</label>
            <div id="introductionContainer">
                <?php if($program->introduction && is_array($program->introduction)): ?>
                    <?php $__currentLoopData = $program->introduction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="paragraph-item">
                            <textarea name="introduction[]" class="form-control paragraph-input" 
                                      rows="3" placeholder="Write paragraph here"><?php echo e($paragraph); ?></textarea>
                            <?php if($index > 0): ?>
                                <button type="button" class="remove-paragraph-btn" onclick="removeParagraph(this, 'introduction')">x</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="paragraph-item">
                        <textarea name="introduction[]" class="form-control paragraph-input" 
                                  rows="3" placeholder="Write paragraph here"></textarea>
                    </div>
                <?php endif; ?>
            </div>
            <small>Write by paragraph</small>
            <button type="button" class="add-paragraph-btn" onclick="addParagraph('introduction')">+ Add Paragraph</button>
        </div>

        <div class="form-group">
            <label>BACKGROUND</label>
            <div id="backgroundContainer">
                <?php if($program->background && is_array($program->background)): ?>
                    <?php $__currentLoopData = $program->background; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="paragraph-item">
                            <textarea name="background[]" class="form-control paragraph-input" 
                                      rows="3" placeholder="Write background paragraph here"><?php echo e($paragraph); ?></textarea>
                            <?php if($index > 0): ?>
                                <button type="button" class="remove-paragraph-btn" onclick="removeParagraph(this, 'background')">x</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="paragraph-item">
                        <textarea name="background[]" class="form-control paragraph-input" 
                                  rows="3" placeholder="Write background paragraph here"></textarea>
                    </div>
                <?php endif; ?>
            </div>
            <small>Write by paragraph</small>
            <button type="button" class="add-paragraph-btn" onclick="addParagraph('background')">+ Add Paragraph</button>
        </div>

        <div class="form-group">
            <label>OBJECTIVES</label>
            <div id="objectivesContainer">
                <?php if($program->objectives && is_array($program->objectives)): ?>
                    <?php $__currentLoopData = $program->objectives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $objective): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="point-item">
                            <textarea name="objectives[]" class="form-control point-input" 
                                      rows="2" placeholder="Write objective point here"><?php echo e($objective); ?></textarea>
                            <?php if($index > 0): ?>
                                <button type="button" class="remove-point-btn" onclick="removePoint(this, 'objectives')">x</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="point-item">
                        <textarea name="objectives[]" class="form-control point-input" 
                                  rows="2" placeholder="Write objective point here"></textarea>
                    </div>
                <?php endif; ?>
            </div>
            <small>Write by point</small>
            <button type="button" class="add-point-btn" onclick="addPoint('objectives')">+ Add Point</button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">SAVE</button>
            <button type="button" class="btn-preview" onclick="previewProgram()">PREVIEW</button>
        </div>
    </form>
</div>

<!-- PROGRAM TENTATIVE SECTION -->
<div id="tentative-section" class="section-editor" style="display: none;">
    <div class="section-editor-header">
        <h2>PROGRAM TENTATIVE</h2>
        <button class="close-section-btn" onclick="closeSection()">x</button>
    </div>
    <div class="breadcrumb-path" style="margin-bottom: 20px;">
        <a href="<?php echo e(route('admin.index')); ?>">
            <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
        </a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
        <span>/</span>
        <span style="color: #111827; font-weight: 600;">PROGRAM TENTATIVE</span>
    </div>

    <form id="tentativeForm" class="section-form" onsubmit="saveTentative(event)">
        <?php echo csrf_field(); ?>
        
    <!-- FIX: Preserve original programme details -->
    <input type="hidden" name="event_date" value="<?php echo e($program->event_date); ?>">
    <input type="hidden" name="event_time" value="<?php echo e($program->event_time); ?>">
    <input type="hidden" name="location" value="<?php echo e($program->location); ?>">
    <input type="hidden" name="theme" value="<?php echo e($program->theme); ?>">

        <div class="tentative-info">
            <div class="info-row">
                <label>DATE :</label>
                <span><?php echo e($program->event_date ? \Carbon\Carbon::parse($program->event_date)->format('d/m/Y') : 'XXX'); ?></span>
            </div>
            <div class="info-row">
                <label>TIME :</label>
                <span><?php echo e($program->event_time ? \Carbon\Carbon::parse($program->event_time)->format('h:i A') : 'XXX'); ?></span>
            </div>
            <div class="info-row">
                <label>VENUE :</label>
                <span><?php echo e(strtoupper($program->location ?? 'XXX')); ?></span>
            </div>
            <div class="info-row">
                <label>THEME :</label>
                <span><?php echo e(strtoupper($program->theme ?? 'XXX')); ?></span>
            </div>
        </div>

        <div class="form-group">
            <label>TENTATIVES</label>
            <div id="schedulesContainer">
                <?php if($program->schedules && count($program->schedules) > 0): ?>
                    <?php $__currentLoopData = $program->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="schedule-item">
                        <input type="text" name="schedules[<?php echo e($index); ?>][time]" 
                               class="form-control schedule-time" placeholder="XX:XX a.m/p.m"
                               value="<?php echo e($schedule['time'] ?? ''); ?>">
                        <input type="text" name="schedules[<?php echo e($index); ?>][description]" 
                               class="form-control schedule-desc" placeholder="Description"
                               value="<?php echo e($schedule['description'] ?? ''); ?>">
                        <button type="button" class="remove-schedule-btn" onclick="removeSchedule(this)">x</button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="schedule-item">
                        <input type="text" name="schedules[0][time]" class="form-control schedule-time" placeholder="XX:XX a.m/p.m">
                        <input type="text" name="schedules[0][description]" class="form-control schedule-desc" placeholder="Description">
                        <button type="button" class="remove-schedule-btn" onclick="removeSchedule(this)">x</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" class="add-schedule-btn" onclick="addSchedule()">+ ADD SCHEDULE</button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">SAVE</button>
            <button type="button" class="btn-preview" onclick="previewProgram()">PREVIEW</button>
        </div>
    </form>
</div>

<!-- VIP SECTION -->
<div id="vip-section" class="section-editor" style="display: none;">
    <div class="section-editor-header">
        <h2>VIP</h2>
        <button class="close-section-btn" onclick="closeSection()">x</button>
    </div>
    <div class="breadcrumb-path" style="margin-bottom: 20px;">
        <a href="<?php echo e(route('admin.index')); ?>">
            <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
        </a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
        <span>/</span>
        <span style="color: #111827; font-weight: 600;">VIP</span>
    </div>

    <form id="vipForm" class="section-form" enctype="multipart/form-data" onsubmit="saveVip(event)">
        <?php echo csrf_field(); ?>
        <div id="vipContainer">
            <?php if($program->vip_list && count($program->vip_list) > 0): ?>
                <?php $__currentLoopData = $program->vip_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $vip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="vip-item" data-index="<?php echo e($index); ?>">
                    <div class="vip-image-upload">
                        <?php if(isset($vip['image']) && $vip['image']): ?>
                        <img src="<?php echo e(asset('storage/' . $vip['image'])); ?>" class="vip-preview" id="vipPreview<?php echo e($index); ?>">
                        <?php else: ?>
                        <div class="vip-placeholder" id="vipPlaceholder<?php echo e($index); ?>">
                            <img src="/assets/icons/upload.png" alt="Upload">
                            <span>Upload Image</span>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="vip_list[<?php echo e($index); ?>][image]" class="vip-file-input" 
                               accept="image/*" onchange="previewVipImage(this, <?php echo e($index); ?>)">
                        <input type="hidden" name="vip_list[<?php echo e($index); ?>][existing_image]" value="<?php echo e($vip['image'] ?? ''); ?>">
                    </div>
                    <div class="vip-details">
                        <input type="text" name="vip_list[<?php echo e($index); ?>][name]" 
                               class="form-control" placeholder="NAME" value="<?php echo e($vip['name'] ?? ''); ?>" required>
                        <input type="text" name="vip_list[<?php echo e($index); ?>][position]" 
                               class="form-control" placeholder="POSITION" value="<?php echo e($vip['position'] ?? ''); ?>" required>
                    </div>
                    <button type="button" class="btn-remove" onclick="removeVip(this)" title="Remove VIP">x</button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="vip-item" data-index="0">
                    <div class="vip-image-upload">
                        <div class="vip-placeholder" id="vipPlaceholder0">
                            <img src="/assets/icons/upload.png" alt="Upload">
                            <span>Upload Image</span>
                        </div>
                        <input type="file" name="vip_list[0][image]" class="vip-file-input" 
                               accept="image/*" onchange="previewVipImage(this, 0)">
                    </div>
                    <div class="vip-details">
                        <input type="text" name="vip_list[0][name]" class="form-control" placeholder="NAME" required>
                        <input type="text" name="vip_list[0][position]" class="form-control" placeholder="POSITION" required>
                    </div>
                    <button type="button" class="btn-remove" onclick="removeVip(this)" title="Remove VIP">x</button>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-add-vip" onclick="addVip()">+ ADD VIP</button>

        <div class="form-actions">
            <button type="submit" class="btn-save">SAVE</button>
            <button type="button" class="btn-preview" onclick="previewProgram()">PREVIEW</button>
        </div>
    </form>
</div>

<!-- PARTICIPATION SECTION -->
<div id="participation-section" class="section-editor" style="display: none;">
    <div class="section-editor-header">
        <h2>PARTICIPATION</h2>
        <button class="close-section-btn" onclick="closeSection()">x</button>
    </div>
    <div class="breadcrumb-path" style="margin-bottom: 20px;">
        <a href="<?php echo e(route('admin.index')); ?>">
            <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
        </a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
        <span>/</span>
        <span style="color: #111827; font-weight: 600;">PARTICIPATION</span>
    </div>

    <form id="participationForm" class="section-form" enctype="multipart/form-data" onsubmit="saveParticipation(event)">
        <?php echo csrf_field(); ?>
        
        <!-- DESCRIPTION -->
        <div class="form-group">
            <label class="form-label">DESCRIPTION</label>
            <div id="participationDescContainer">
                <?php if($program->participation_description && is_array($program->participation_description)): ?>
                    <?php $__currentLoopData = $program->participation_description; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="paragraph-item">
                            <textarea name="participation_description[]" class="form-control paragraph-input" 
                                      rows="3" placeholder="Write participation description paragraph"><?php echo e($paragraph); ?></textarea>
                            <?php if($index > 0): ?>
                                <button type="button" class="remove-paragraph-btn" onclick="removeParagraph(this, 'participation')">x</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="paragraph-item">
                        <textarea name="participation_description[]" class="form-control paragraph-input" 
                                  rows="3" placeholder="Write participation description paragraph"></textarea>
                    </div>
                <?php endif; ?>
            </div>
            <small>Write by paragraph</small>
            <button type="button" class="add-paragraph-btn" onclick="addParagraph('participation')">+ Add Paragraph</button>
        </div>

        <!-- PRICE (Leave empty to hide price section) -->
        <div class="form-group">
            <label class="form-label">PRICE <span class="optional-badge">(Leave empty to hide price section)</span></label>
            <div id="priceContainer">
                <?php if($program->participation_prices && count($program->participation_prices) > 0): ?>
                    <?php $__currentLoopData = $program->participation_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="price-item">
                        <input type="text" name="participation_prices[<?php echo e($index); ?>][description]" 
                               class="form-control price-desc" placeholder="ex: Per person"
                               value="<?php echo e($price['description'] ?? ''); ?>">
                        <input type="text" name="participation_prices[<?php echo e($index); ?>][amount]" 
                               class="form-control price-amount" placeholder="RM xxxx"
                               value="<?php echo e($price['amount'] ?? ''); ?>">
                        <button type="button" class="remove-price-btn" onclick="removePrice(this)">x</button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="price-item">
                        <input type="text" name="participation_prices[0][description]" class="form-control price-desc" placeholder="ex: Per person">
                        <input type="text" name="participation_prices[0][amount]" class="form-control price-amount" placeholder="RM xxxx">
                        <button type="button" class="remove-price-btn" onclick="removePrice(this)">x</button>
                    </div>
                <?php endif; ?>
            </div>
            <small>Write by fees package (leave both fields empty to hide price section)</small>
            <button type="button" class="add-price-btn" onclick="addPrice('participation')">+ ADD PRICE</button>
        </div>

        <!-- ADDITIONAL FILES (Optional) -->
        <div class="form-group">
            <label class="form-label">ADDITIONAL FILE <span class="optional-badge">(Optional)</span></label>
            
            <div class="file-management-section">
                <!-- Upload Area -->
                <div class="file-upload-box">
                    <div class="file-input-wrapper">
                        <input type="file" 
                               name="participation_additional_files" 
                               class="form-control file-input" 
                               accept="application/pdf"
                               id="participationAdditionalFiles">
                        <label for="participationAdditionalFiles" class="file-input-label">
                            <span class="file-icon"></span>
                            <span class="file-text">
                                <strong>Choose PDF File</strong>
                                <small>Max 30 MB</small>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Current File Display -->
                <?php if($program->participation_additional_files): ?>
                    <div class="file-display-box">
                        <div class="file-display-header">
                            <span class="file-display-title">Current Additional File</span>
                        </div>
                        <div class="file-display-content">
                            <div class="file-info-row">
                                <span class="file-label">File:</span>
                                <a href="<?php echo e(asset('storage/' . $program->participation_additional_files)); ?>" 
                                   target="_blank" 
                                   class="file-view-link">
                                     View PDF
                                </a>
                            </div>
                        </div>
                        <div class="file-display-footer">
                            <button type="button" 
                                    class="btn-remove-file" 
                                    onclick="removeAdditionalFile('participation_additional_files', '<?php echo e($program->participation_additional_files); ?>')">
                                 Remove File
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="file-display-box empty">
                        <div class="empty-state">
                            <span class="empty-icon">…</span>
                            <span class="empty-text">No file uploaded</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- FORM -->
        <div class="form-group">
            <label class="form-label">FORM</label>
            
            <div class="form-type-toggle-wrapper">
                <div class="form-type-toggle">
                    <button type="button" 
                            class="toggle-btn <?php echo e($program->participation_form_type === 'file' || !$program->participation_form_type ? 'active' : ''); ?>" 
                            onclick="toggleFormType('participation', 'file')">
                        <span class="toggle-icon"></span>
                        FILES PDF
                    </button>
                    <button type="button" 
                            class="toggle-btn <?php echo e($program->participation_form_type === 'link' ? 'active' : ''); ?>" 
                            onclick="toggleFormType('participation', 'link')">
                        <span class="toggle-icon"></span>
                        LINK
                    </button>
                </div>
            </div>
            
            <!-- FILE PDF SECTION -->
            <div id="participationFileInput" style="display: <?php echo e($program->participation_form_type === 'link' ? 'none' : 'block'); ?>">
                <div class="file-management-section">
                    <!-- Upload Area -->
                    <div class="file-upload-box">
                        <div class="file-input-wrapper">
                            <input type="file" 
                                   name="participation_form_file" 
                                   class="form-control file-input" 
                                   accept="application/pdf"
                                   id="participationFormFile">
                            <label for="participationFormFile" class="file-input-label">
                                <span class="file-icon"></span>
                                <span class="file-text">
                                    <strong>Choose PDF File</strong>
                                    <small>Max 30 MB</small>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Current File Display -->
                    <?php if($program->participation_form && $program->participation_form_type === 'file'): ?>
                        <div class="file-display-box">
                            <div class="file-display-header">
                                <span class="file-display-title">Current Form File</span>
                            </div>
                            <div class="file-display-content">
                                <div class="file-info-row">
                                    <span class="file-label">File:</span>
                                    <a href="<?php echo e(asset('storage/' . $program->participation_form)); ?>" 
                                       target="_blank" 
                                       class="file-view-link">
                                         View PDF
                                    </a>
                                </div>
                            </div>
                            <div class="file-display-footer">
                                <button type="button" 
                                        class="btn-remove-file" 
                                        onclick="removeFormFile('participation_form', '<?php echo e($program->participation_form); ?>')">
                                    Remove File
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="file-display-box empty">
                            <div class="empty-state">
                                <span class="empty-icon">…</span>
                                <span class="empty-text">No file uploaded</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- LINK SECTION -->
            <div id="participationLinkInput" style="display: <?php echo e($program->participation_form_type === 'link' ? 'block' : 'none'); ?>">
                <input type="url" 
                       name="participation_form_link" 
                       class="form-control" 
                       placeholder="https://example.com/form"
                       value="<?php echo e($program->participation_form_type === 'link' ? $program->participation_form : ''); ?>">
            </div>
            
            <input type="hidden" name="participation_form_type" id="participationFormType" value="<?php echo e($program->participation_form_type ?? 'file'); ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">SAVE</button>
            <button type="button" class="btn-preview" onclick="previewProgram()">PREVIEW</button>
        </div>
    </form>
</div>

<!-- Continue with sponsorship section in next artifact... -->

    <!-- SPONSORSHIP SECTION -->
    <div id="sponsorship-section" class="section-editor" style="display: none;">
        <div class="section-editor-header">
            <h2>SPONSORSHIP</h2>
            <button class="close-section-btn" onclick="closeSection()">x</button>
        </div>
        <div class="breadcrumb-path" style="margin-bottom: 20px;">
            <a href="<?php echo e(route('admin.index')); ?>">
                <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
            </a>
            <span>/</span>
            <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
            <span>/</span>
            <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
            <span>/</span>
            <span style="color: #111827; font-weight: 600;">SPONSORSHIP</span>
        </div>

        <form id="sponsorshipForm" class="section-form" enctype="multipart/form-data" onsubmit="saveSponsorship(event)">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>DESCRIPTION</label>
                <div id="sponsorshipDescContainer">
                    <?php if($program->sponsorship_description && is_array($program->sponsorship_description)): ?>
                        <?php $__currentLoopData = $program->sponsorship_description; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="paragraph-item">
                                <textarea name="sponsorship_description[]" class="form-control paragraph-input" 
                                        rows="3" placeholder="Write sponsorship description paragraph"><?php echo e($paragraph); ?></textarea>
                                <?php if($index > 0): ?>
                                    <button type="button" class="remove-paragraph-btn" onclick="removeParagraph(this, 'sponsorship')">x</button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="paragraph-item">
                            <textarea name="sponsorship_description[]" class="form-control paragraph-input" 
                                    rows="3" placeholder="Write sponsorship description paragraph"></textarea>
                        </div>
                    <?php endif; ?>
                </div>
                <small>Write by paragraph</small>
                <button type="button" class="add-paragraph-btn" onclick="addParagraph('sponsorship')">+ Add Paragraph</button>
            </div>

            <div class="form-group">
                <label>PACKAGE (Leave empty to hide package section)</label>
                <div id="packageContainer">
                    <?php if($program->sponsorship_packages && count($program->sponsorship_packages) > 0): ?>
                        <?php $__currentLoopData = $program->sponsorship_packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="price-item">
                            <input type="text" name="sponsorship_packages[<?php echo e($index); ?>][description]" 
                                class="form-control price-desc" placeholder="ex: Platinum"
                                value="<?php echo e($package['description'] ?? ''); ?>">
                            <input type="text" name="sponsorship_packages[<?php echo e($index); ?>][amount]" 
                                class="form-control price-amount" placeholder="RM xxxx"
                                value="<?php echo e($package['amount'] ?? ''); ?>">
                            <button type="button" class="remove-price-btn" onclick="removePackage(this)">x</button>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="price-item">
                            <input type="text" name="sponsorship_packages[0][description]" class="form-control price-desc" placeholder="ex: Platinum">
                            <input type="text" name="sponsorship_packages[0][amount]" class="form-control price-amount" placeholder="RM xxxx">
                            <button type="button" class="remove-price-btn" onclick="removePackage(this)">x</button>
                        </div>
                    <?php endif; ?>
                </div>
                <small>Write by one package (leave both fields empty to hide package section)</small>
                <button type="button" class="add-price-btn" onclick="addPrice('sponsorship')">+ ADD PACKAGE</button>
            </div>



<!-- ADDITIONAL FILES (Optional) -->
<div class="form-group">
    <label class="form-label">ADDITIONAL FILE <span class="optional-badge">(Optional)</span></label>
    
    <div class="file-management-section">
        <!-- Upload Area -->
        <div class="file-upload-box">
            <div class="file-input-wrapper">
                <input type="file" 
                       name="sponsorship_additional_files" 
                       class="form-control file-input" 
                       accept="application/pdf"
                       id="sponsorshipAdditionalFiles">
                <label for="sponsorshipAdditionalFiles" class="file-input-label">
                    <span class="file-icon"></span>
                    <span class="file-text">
                        <strong>Choose PDF File</strong>
                        <small>Max 30 MB</small>
                    </span>
                </label>
            </div>
        </div>

        <!-- Current File Display -->
        <?php if($program->sponsorship_additional_files): ?>
            <div class="file-display-box">
                <div class="file-display-header">
                    <span class="file-display-title">Current Additional File</span>
                </div>
                <div class="file-display-content">
                    <div class="file-info-row">
                        <span class="file-label">File:</span>
                        <a href="<?php echo e(asset('storage/' . $program->sponsorship_additional_files)); ?>" 
                           target="_blank" 
                           class="file-view-link">
                             View PDF
                        </a>
                    </div>
                </div>
                <div class="file-display-footer">
                    <button type="button" 
                            class="btn-remove-file" 
                            onclick="removeAdditionalFile('sponsorship_additional_files', '<?php echo e($program->sponsorship_additional_files); ?>')">
                         Remove File
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="file-display-box empty">
                <div class="empty-state">
                    <span class="empty-icon">…</span>
                    <span class="empty-text">No file uploaded</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- FORM -->
<div class="form-group">
    <label class="form-label">FORM</label>
    
    <div class="form-type-toggle-wrapper">
        <div class="form-type-toggle">
            <button type="button" 
                    class="toggle-btn <?php echo e($program->sponsorship_form_type === 'file' || !$program->sponsorship_form_type ? 'active' : ''); ?>" 
                    onclick="toggleFormType('sponsorship', 'file')">
                <span class="toggle-icon"></span>
                FILES PDF
            </button>
            <button type="button" 
                    class="toggle-btn <?php echo e($program->sponsorship_form_type === 'link' ? 'active' : ''); ?>" 
                    onclick="toggleFormType('sponsorship', 'link')">
                <span class="toggle-icon"></span>
                LINK
            </button>
        </div>
    </div>
    
    <!-- FILE PDF SECTION -->
    <div id="sponsorshipFileInput" style="display: <?php echo e($program->sponsorship_form_type === 'link' ? 'none' : 'block'); ?>">
        <div class="file-management-section">
            <!-- Upload Area -->
            <div class="file-upload-box">
                <div class="file-input-wrapper">
                    <input type="file" 
                           name="sponsorship_form_file" 
                           class="form-control file-input" 
                           accept="application/pdf"
                           id="sponsorshipFormFile">
                    <label for="sponsorshipFormFile" class="file-input-label">
                        <span class="file-icon"></span>
                        <span class="file-text">
                            <strong>Choose PDF File</strong>
                            <small>Max 30 MB</small>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Current File Display -->
            <?php if($program->sponsorship_form && $program->sponsorship_form_type === 'file'): ?>
                <div class="file-display-box">
                    <div class="file-display-header">
                        <span class="file-display-title">Current Form File</span>
                    </div>
                    <div class="file-display-content">
                        <div class="file-info-row">
                            <span class="file-label">File:</span>
                            <a href="<?php echo e(asset('storage/' . $program->sponsorship_form)); ?>" 
                               target="_blank" 
                               class="file-view-link">
                                 View PDF
                            </a>
                        </div>
                    </div>
                    <div class="file-display-footer">
                        <button type="button" 
                                class="btn-remove-file" 
                                onclick="removeFormFile('sponsorship_form', '<?php echo e($program->sponsorship_form); ?>')">
                             Remove File
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="file-display-box empty">
                    <div class="empty-state">
                        <span class="empty-icon">…</span>
                        <span class="empty-text">No file uploaded</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- LINK SECTION -->
    <div id="sponsorshipLinkInput" style="display: <?php echo e($program->sponsorship_form_type === 'link' ? 'block' : 'none'); ?>">
        <input type="url" 
               name="sponsorship_form_link" 
               class="form-control" 
               placeholder="https://example.com/form"
               value="<?php echo e($program->sponsorship_form_type === 'link' ? $program->sponsorship_form : ''); ?>">
    </div>
    
    <input type="hidden" name="sponsorship_form_type" id="sponsorshipFormType" value="<?php echo e($program->sponsorship_form_type ?? 'file'); ?>">
</div>

            <div class="form-actions">
                <button type="submit" class="btn-save">SAVE</button>
                <button type="button" class="btn-preview" onclick="previewProgram()">PREVIEW</button>
            </div>
        </form>
    </div>
<div id="programme-section" class="section-editor" style="display: none;">
    <div class="section-editor-header">
        <h2>KEY INITIATIVES & ACHIEVEMENTS</h2>
        <button class="close-section-btn" onclick="closeSection()">x</button>
    </div>
    <div class="breadcrumb-path" style="margin-bottom: 20px;">
        <a href="<?php echo e(route('admin.index')); ?>">
            <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
        </a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
        <span>/</span>
        <span style="color: #111827; font-weight: 600;">KEY INITIATIVES & ACHIEVEMENTS</span>
    </div>

    <!-- Existing Programme Items List -->
    <div id="programmeItemsList" style="margin-bottom: 30px;">
        <!-- Programme items will be loaded here dynamically -->
    </div>

    <!-- Add New Programme Item Form -->
    <div class="new-programme-form" style="background: #f9fafb; padding: 20px; border-radius: 8px; border: 2px dashed #d1d5db;">
        <h3 style="color: #007844; margin-bottom: 20px; font-size: 18px;">ADD NEW PROGRAMME</h3>
        
        <form id="programmeForm" class="section-form" enctype="multipart/form-data" onsubmit="saveProgramme(event)">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label>PROGRAMME TITLE <span style="color: red;">*</span></label>
                <input type="text" 
                       name="programme_title" 
                       id="programmeTitle"
                       class="form-control" 
                       placeholder="Enter programme title"
                       required>
            </div>

            <div class="form-group">
                <label>IMAGES (Max 3)</label>
                <div class="programme-images-upload-wrapper">
                    <!-- Image Preview Grid -->
                    <div class="programme-image-preview-grid" id="programmeImagePreviewGrid">
                        <!-- Preview images will appear here -->
                    </div>
                    
                    <!-- Upload Button -->
                    <div class="upload-button-wrapper">
                        <button type="button" class="btn-add-image" onclick="document.getElementById('programmeImageInput').click()">
                            <img src="/assets/icons/upload.png" alt="Upload" style="width: 24px; margin-right: 8px;">
                            Add Images (Max 3)
                        </button>
                        <input type="file" 
                               name="programme_images[]" 
                               id="programmeImageInput" 
                               class="hidden-file-input" 
                               accept="image/*" 
                               multiple 
                               onchange="previewProgrammeImages(this)">
                        <small style="display: block; margin-top: 8px; color: #666;">
                            Click to select up to 3 images
                        </small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>DESCRIPTION</label>
                <div id="programmeDescContainer">
                    <div class="paragraph-item">
                        <textarea name="programme_description[]" 
                                  class="form-control paragraph-input" 
                                  rows="3" 
                                  placeholder="Write programme description paragraph"></textarea>
                    </div>
                </div>
                <small>Write by paragraph</small>
                <button type="button" 
                        class="add-paragraph-btn" 
                        onclick="addParagraph('programmeDesc')">+ Add Paragraph</button>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">ADD PROGRAMME</button>
                <button type="button" class="btn-preview" onclick="previewProgram()">PREVIEW</button>
            </div>
        </form>
    </div>
</div>

<!-- ====================== PHOTO SECTION EDITOR (UPDATED) ======================= -->
<div id="photo-section" class="section-editor" style="display: none;">
    <div class="section-editor-header">
        <h2>PHOTO OF THE EVENT</h2>
        <button class="close-section-btn" onclick="closeSection()">x</button>
    </div>
    <div class="breadcrumb-path" style="margin-bottom: 20px;">
        <a href="<?php echo e(route('admin.index')); ?>">
            <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
        </a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
        <span>/</span>
        <span style="color: #111827; font-weight: 600;">PHOTO</span>
    </div>

    <!-- PHOTO GALLERY SECTION -->
    <div class="photo-section-container">
        <div class="photo-gallery-header">
            <h3>PHOTO GALLERY</h3>
            <a href="#" style="font-size: 12px; color: #007844; text-decoration: none;">Â»</a>
        </div>

    <!-- CURRENT PHOTOS -->
<div class="photo-subsection">
    <div class="photo-header-controls">
        <h4 class="photo-subsection-title">CURRENT PHOTOS <span id="photoCount">(0)</span></h4>
        <div class="photo-bulk-actions" id="photoBulkActions" style="display: none;">
            <label class="select-all-checkbox">
                <input type="checkbox" id="selectAllPhotos" onchange="toggleSelectAll()">
                <span>Select All</span>
            </label>
            <button type="button" class="btn-bulk-delete" onclick="bulkDeletePhotos()">
                <img src="/assets/icons/delete.png" alt="Delete" style="width: 16px; margin-right: 6px;">
                Delete Selected (<span id="selectedCount">0</span>)
            </button>
        </div>
    </div>
    <div id="photoGalleryList" class="current-photos-container">
        <p style="text-align: center; color: #9ca3af; padding: 20px;">No photos uploaded yet</p>
    </div>
</div>

        <!-- UPLOAD NEW PHOTOS -->
        <div class="photo-subsection">
            <h4 class="photo-subsection-title">UPLOAD NEW PHOTOS</h4>
            
            <form id="photoForm" class="photo-upload-form" enctype="multipart/form-data" onsubmit="savePhoto(event)">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label>PHOTO IMAGE <span style="color: red;">*</span></label>
                    <div class="photo-upload-area" onclick="document.getElementById('photoImageInput').click()">
                        <div class="photo-preview-container" id="photoPreviewContainer">
                            <div class="upload-placeholder" id="photoUploadPlaceholder">
                                <img src="/assets/icons/upload.png" alt="Upload">
                                <span>Click to Select Photos</span>
                                <small>You can upload up to 5 images. Supported formats: jpg, png, gif, webp</small>
                            </div>
                        </div>
                        <input type="file" 
                               name="photo_image" 
                               id="photoImageInput" 
                               class="hidden-file-input" 
                               accept="image/*" 
                               multiple
                               onchange="previewMultiplePhotos(this)"
                               required>
                    </div>
                </div>

                <div class="photo-form-actions">
                    <button type="submit" class="btn-save-photo">SAVE PHOTOS</button>
                    <button type="button" class="btn-preview-photo" onclick="previewProgram()">PREVIEW</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- PARTICIPANT LIST SECTION EDITOR -->
<div id="link-participation-section" class="section-editor" style="display: none;">
    <div class="section-editor-header">
        <h2>LINK PARTICIPATION PROGRAMME</h2>
        <button class="close-section-btn" onclick="closeSection()">x</button>
    </div>
    <div class="breadcrumb-path" style="margin-bottom: 20px;">
        <a href="<?php echo e(route('admin.index')); ?>">
            <img src="<?php echo e(asset('assets/icons/Home.png')); ?>" class="breadcrumb-home-icon">
        </a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.index')); ?>" style="color: #6B7280;">PROGRAMMES</a>
        <span>/</span>
        <a href="<?php echo e(route('admin.programs.show', $program->id)); ?>" style="color: #6B7280;"><?php echo e(strtoupper($program->title)); ?></a>
        <span>/</span>
        <span style="color: #111827; font-weight: 600;">LINK PARTICIPATION</span>
    </div>

    <form id="linkParticipationForm" onsubmit="saveLinkParticipation(event)">
        <?php echo csrf_field(); ?>
        
        <div class="form-group">
            <label>SELECT PARTICIPATION PROGRAMME</label>
            <select name="participation_programme_id" id="participationProgrammeSelect" class="form-control">
                <option value="">-- No Participation Programme Linked --</option>
                <?php
                    $participationProgrammes = \App\Models\ParticipationProgramme::orderBy('title')->get();
                ?>
                <?php $__currentLoopData = $participationProgrammes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($pp->id); ?>" 
                            <?php echo e($program->participation_programme_id == $pp->id ? 'selected' : ''); ?>>
                        <?php echo e($pp->title); ?>

                        <?php if($pp->start_date): ?>
                            (<?php echo e(\Carbon\Carbon::parse($pp->start_date)->format('d/m/Y')); ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small>Link this programme to a participation registration to display approved participants in the Participant List section</small>
        </div>

        <?php if($program->participation_programme_id): ?>
            <div style="background: #f0f9f7; border: 2px solid #d4ebe5; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                <h4 style="color: #00542A; margin: 0 0 10px 0;">Currently Linked</h4>
                <p style="margin: 0 0 10px 0; color: #374151;">
                    <strong>Participation Programme:</strong> <?php echo e($program->participationProgramme->title ?? 'Unknown'); ?>

                </p>
                <a href="<?php echo e(route('admin.participations.participant_list', $program->participation_programme_id)); ?>" 
                   target="_blank"
                   class="btn-preview"
                   style="display: inline-block; margin-top: 10px;">
                    View Participant List in Admin
                </a>
            </div>
        <?php else: ?>
            <div style="background: #fef9f0; border: 2px solid #fde68a; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                <h4 style="color: #92400e; margin: 0 0 10px 0;"> Not Linked</h4>
                <p style="margin: 0; color: #78350f;">
                    This programme is not linked to any participation registration. Link it to display participants in the Participant List section.
                </p>
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn-save">SAVE LINK</button>
            <button type="button" class="btn-preview" onclick="previewProgram()">PREVIEW</button>
        </div>
    </form>
</div>
</div>

<script>
    
// ========== GLOBAL VARIABLES ==========
window.programId = <?php echo e($program->id); ?>;
window.selectedProgrammeImages = [];
window.scheduleCounter = <?php echo e($program->schedules ? count($program->schedules) : 1); ?>;
window.vipCounter = <?php echo e($program->vip_list ? count($program->vip_list) : 1); ?>;
window.priceCounter = <?php echo e($program->participation_prices ? count($program->participation_prices) : 1); ?>;
window.packageCounter = <?php echo e($program->sponsorship_packages ? count($program->sponsorship_packages) : 1); ?>;
window.programmeCounter = 0;

// ========== SECTION NAVIGATION ==========
function openSection(sectionName) {
    console.log('openSection called:', sectionName);
    
    // Hide all section editors
    document.querySelectorAll('.section-editor').forEach(function(el) {
        el.style.display = 'none';
    });
    
    // Hide section navigation
    var sectionNav = document.querySelector('.section-nav');
    if (sectionNav) {
        sectionNav.style.display = 'none';
    }
    
    // Show the requested section
    var sectionElement = document.getElementById(sectionName + '-section');
    if (sectionElement) {
        sectionElement.style.display = 'block';
        
        // Load programme items if opening programme section
        if (sectionName === 'programme' && typeof loadProgrammeItems === 'function') {
            loadProgrammeItems();
        }
        
        // Load photo items if opening photo section
        if (sectionName === 'photo' && typeof loadPhotoItems === 'function') {
            loadPhotoItems();
        }
    } else {
        console.error('Section not found:', sectionName + '-section');
    }
    
    return false;
}

// Keep this as backup for showSection function
window.showSection = function(sectionName) {
    return openSection(sectionName);
}

window.closeSection = function() {
    console.log('closeSection called');
    
    document.querySelectorAll('.section-editor').forEach(el => {
        el.style.display = 'none';
    });
    
    const sectionNav = document.querySelector('.section-nav');
    if (sectionNav) {
        sectionNav.style.display = 'grid';
    }
}

// ========== TOGGLE SECTION VISIBILITY ==========
window.toggleSectionDisplay = function(checkbox) {
    if (!checkbox) return;
    
    const sectionName = checkbox.getAttribute('data-section');
    const isVisible = checkbox.checked;
    
    const toggleSlider = checkbox.nextElementSibling;
    if (toggleSlider) toggleSlider.style.opacity = '0.5';
    checkbox.disabled = true;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        checkbox.disabled = false;
        if (toggleSlider) toggleSlider.style.opacity = '1';
        checkbox.checked = !isVisible;
        return;
    }
    
    fetch(`/admin/programs/${window.programId}/toggle-section`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            section: sectionName,
            is_visible: isVisible
        })
    })
    .then(response => response.json())
    .then(data => {
        checkbox.disabled = false;
        if (toggleSlider) toggleSlider.style.opacity = '1';
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: `Section is now ${isVisible ? 'visible' : 'hidden'}`,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            checkbox.checked = !isVisible;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to update section visibility',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        checkbox.disabled = false;
        if (toggleSlider) toggleSlider.style.opacity = '1';
        checkbox.checked = !isVisible;
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update section visibility',
            confirmButtonColor: '#dc3545'
        });
    });
}


// ========== SAVE FUNCTIONS - COMPLETE FIX ==========

window.saveOverview = function(event) {
    event.preventDefault();
    
    console.log('=== SAVE OVERVIEW STARTED ===');
    console.log('Program ID:', window.programId);

    // Find textareas
    const introTextareas = document.querySelectorAll('textarea[name="introduction[]"]');
    const backgroundTextareas = document.querySelectorAll('textarea[name="background[]"]');
    const objectivesTextareas = document.querySelectorAll('textarea[name="objectives[]"]');

    console.log('Textareas found:', introTextareas.length, backgroundTextareas.length, objectivesTextareas.length);

    // Collect data
    const introduction = [];
    const background = [];
    const objectives = [];

    introTextareas.forEach((textarea) => {
        const value = textarea.value.trim();
        if (value !== '') {
            introduction.push(value);
        }
    });

    backgroundTextareas.forEach((textarea) => {
        const value = textarea.value.trim();
        if (value !== '') {
            background.push(value);
        }
    });

    objectivesTextareas.forEach((textarea) => {
        const value = textarea.value.trim();
        if (value !== '') {
            objectives.push(value);
        }
    });

    console.log('Data collected:', {
        introduction: introduction.length,
        background: background.length,
        objectives: objectives.length
    });

    if (introduction.length === 0 && background.length === 0 && objectives.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Data',
            text: 'Please enter at least one field',
            confirmButtonColor: '#dc3545'
        });
        return;
    }

    // Create FormData
    const formData = new FormData();
    
    // CRITICAL FIX: Add _method for Laravel PUT method spoofing
    formData.append('_method', 'PUT');
    
    introduction.forEach((text, i) => {
        formData.append(`introduction[${i}]`, text);
    });

    background.forEach((text, i) => {
        formData.append(`background[${i}]`, text);
    });

    objectives.forEach((text, i) => {
        formData.append(`objectives[${i}]`, text);
    });

    // Show loading
    Swal.fire({
        title: 'Saving...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // CRITICAL FIX: Use POST instead of PUT when sending FormData
    fetch(`/admin/programs/${window.programId}/overview`, {
        method: 'POST',  // Changed from PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Overview updated successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save overview',
            confirmButtonColor: '#dc3545'
        });
    });
}

window.saveTentative = function(event) {
    event.preventDefault();
    
    console.log('=== SAVE TENTATIVE STARTED ===');
    console.log('Program ID:', window.programId);

    // Get schedules directly from form
    const scheduleItems = document.querySelectorAll('.schedule-item');
    const schedules = [];
    
    scheduleItems.forEach((item) => {
        const timeInput = item.querySelector('input[name$="[time]"]');
        const descInput = item.querySelector('input[name$="[description]"]');
        
        const time = timeInput ? timeInput.value.trim() : '';
        const description = descInput ? descInput.value.trim() : '';
        
        // Only include if at least one field has content
        if (time || description) {
            schedules.push({
                time: time,
                description: description
            });
        }
    });
    
    console.log('Schedules collected:', schedules.length, schedules);
    
    // Create FormData
    const formData = new FormData();
    
    // CRITICAL FIX: Add _method for Laravel PUT method spoofing
    formData.append('_method', 'PUT');
    
    // Add schedules as JSON string
    formData.append('schedules', JSON.stringify(schedules));
    
    // Show loading
    Swal.fire({
        title: 'Saving...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // CRITICAL FIX: Use POST instead of PUT when sending FormData
    fetch(`/admin/programs/${window.programId}/tentative`, {
        method: 'POST',  // Changed from PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Tentative updated successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save tentative',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== VIP SECTION - COMPLETE WORKING FIX ==========

// Initialize VIP counter on page load
document.addEventListener('DOMContentLoaded', function() {
    const vipItems = document.querySelectorAll('#vipContainer .vip-item');
    window.vipCounter = vipItems.length;
    console.log(' VIP Section Initialized. Current items:', window.vipCounter);
});

// ========== ADD VIP FUNCTION ==========
window.addVip = function() {
    const container = document.getElementById('vipContainer');
    if (!container) {
        console.error(' VIP container not found');
        return;
    }
    
    const newIndex = window.vipCounter;
    console.log(' Adding VIP item with index:', newIndex);
    
    const newVip = document.createElement('div');
    newVip.className = 'vip-item';
    newVip.setAttribute('data-index', newIndex);
    newVip.innerHTML = `
        <div class="vip-image-upload">
            <div class="vip-placeholder" id="vipPlaceholder${newIndex}">
                <img src="/assets/icons/upload.png" alt="Upload">
                <span>Upload Image</span>
            </div>
            <input type="file" 
                   name="vip_list[${newIndex}][image]" 
                   class="vip-file-input" 
                   accept="image/*" 
                   onchange="previewVipImage(this, ${newIndex})">
        </div>
        <div class="vip-details">
            <input type="text" 
                   name="vip_list[${newIndex}][name]" 
                   class="form-control" 
                   placeholder="NAME" 
                   required>
            <input type="text" 
                   name="vip_list[${newIndex}][position]" 
                   class="form-control" 
                   placeholder="POSITION" 
                   required>
        </div>
        <button type="button" 
                class="btn-remove" 
                onclick="removeVip(this)" 
                title="Remove VIP">x</button>
    `;
    
    container.appendChild(newVip);
    window.vipCounter++;
    
    console.log(' VIP item added. New counter:', window.vipCounter);
}

// ========== REMOVE VIP FUNCTION ==========
window.removeVip = function(button) {
    const item = button.closest('.vip-item');
    const container = document.getElementById('vipContainer');
    
    if (!container || !item) {
        console.error(' Cannot find container or item');
        return;
    }
    
    // Don't allow removing if it's the last item
    const itemCount = container.querySelectorAll('.vip-item').length;
    if (itemCount <= 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Remove',
            text: 'At least one VIP entry is required',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Confirm deletion
    Swal.fire({
        title: 'Remove VIP?',
        text: 'Are you sure you want to remove this VIP entry?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Removing VIP item');
            item.remove();
            
            // Re-index all remaining items
            reindexVipItems();
            
            Swal.fire({
                icon: 'success',
                title: 'Removed!',
                text: 'VIP entry removed',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// ========== REINDEX VIP ITEMS ==========
function reindexVipItems() {
    const container = document.getElementById('vipContainer');
    if (!container) return;
    
    const vipItems = container.querySelectorAll('.vip-item');
    console.log('ðŸ”„ Reindexing', vipItems.length, 'VIP items');
    
    vipItems.forEach((item, newIndex) => {
        item.setAttribute('data-index', newIndex);
        
        // Update all input names
        const nameInput = item.querySelector('input[name*="[name]"]');
        const positionInput = item.querySelector('input[name*="[position]"]');
        const fileInput = item.querySelector('input[type="file"]');
        const existingImageInput = item.querySelector('input[name*="[existing_image]"]');
        
        if (nameInput) nameInput.name = `vip_list[${newIndex}][name]`;
        if (positionInput) positionInput.name = `vip_list[${newIndex}][position]`;
        if (fileInput) {
            fileInput.name = `vip_list[${newIndex}][image]`;
            fileInput.setAttribute('onchange', `previewVipImage(this, ${newIndex})`);
        }
        if (existingImageInput) existingImageInput.name = `vip_list[${newIndex}][existing_image]`;
        
        // Update IDs
        const placeholder = item.querySelector('.vip-placeholder');
        if (placeholder) placeholder.id = `vipPlaceholder${newIndex}`;
        
        const preview = item.querySelector('.vip-preview');
        if (preview) preview.id = `vipPreview${newIndex}`;
    });
    
    console.log(' Reindexing complete');
}

// ========== PREVIEW VIP IMAGE ==========
window.previewVipImage = function(input, index) {
    console.log(' Preview image for index:', index);
    
    if (!input.files || input.files.length === 0) {
        console.log('âš ï¸ No file selected');
        return;
    }
    
    const file = input.files[0];
    console.log(' File:', file.name, '(', (file.size / 1024).toFixed(2), 'KB)');
    
    // Validate file size (10MB max)
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
        Swal.fire({
            icon: 'warning',
            title: 'File Too Large',
            text: 'Image size must be less than 10MB',
            confirmButtonColor: '#0d5c3c'
        });
        input.value = '';
        return;
    }
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid File Type',
            text: 'Please select a valid image file (JPG, PNG, GIF, WEBP)',
            confirmButtonColor: '#0d5c3c'
        });
        input.value = '';
        return;
    }
    
    // Read and preview image
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const placeholder = document.getElementById('vipPlaceholder' + index);
        const preview = document.getElementById('vipPreview' + index);
        
        if (preview) {
            // Update existing preview
            preview.src = e.target.result;
            console.log(' Updated existing preview');
        } else if (placeholder) {
            // Replace placeholder with preview
            placeholder.outerHTML = `<img src="${e.target.result}" class="vip-preview" id="vipPreview${index}">`;
            console.log(' Created new preview');
        } else {
            console.error('âŒ Neither placeholder nor preview found for index:', index);
        }
    };
    
    reader.onerror = function(error) {
        console.error(' FileReader error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to read image file',
            confirmButtonColor: '#dc3545'
        });
    };
    
    reader.readAsDataURL(file);
}

// ========== SAVE VIP FUNCTION - COMPLETE FIX ==========
window.saveVip = function(event) {
    event.preventDefault();
    
    console.log(' === SAVE VIP STARTED ===');
    console.log('Program ID:', window.programId);
    
    const formElement = document.getElementById('vipForm');
    if (!formElement) {
        console.error(' VIP form not found');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Form not found',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    // Get all VIP items
    const vipItems = document.querySelectorAll('#vipContainer .vip-item');
    console.log(' VIP items found:', vipItems.length);
    
    if (vipItems.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No VIP Data',
            text: 'Please add at least one VIP entry',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Validate all VIP entries
    let hasError = false;
    let errorMessage = '';
    
    vipItems.forEach((item, index) => {
        const nameInput = item.querySelector('input[name*="[name]"]');
        const positionInput = item.querySelector('input[name*="[position]"]');
        
        if (!nameInput || !nameInput.value.trim()) {
            hasError = true;
            errorMessage = `VIP #${index + 1}: Name is required`;
        }
        if (!positionInput || !positionInput.value.trim()) {
            hasError = true;
            errorMessage = `VIP #${index + 1}: Position is required`;
        }
    });
    
    if (hasError) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            text: errorMessage,
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Create FormData
    const formData = new FormData();
    
    // Process each VIP item
    vipItems.forEach((item, index) => {
        const nameInput = item.querySelector('input[name*="[name]"]');
        const positionInput = item.querySelector('input[name*="[position]"]');
        const fileInput = item.querySelector('input[type="file"]');
        const existingImageInput = item.querySelector('input[name*="[existing_image]"]');
        
        const name = nameInput.value.trim();
        const position = positionInput.value.trim();
        
        console.log(`VIP ${index}:`, {
            name: name,
            position: position,
            has_new_file: fileInput && fileInput.files.length > 0,
            has_existing: existingImageInput && existingImageInput.value
        });
        
        // Add VIP data
        formData.append(`vip_list[${index}][name]`, name);
        formData.append(`vip_list[${index}][position]`, position);
        
        // Handle existing image
        if (existingImageInput && existingImageInput.value) {
            formData.append(`vip_list[${index}][existing_image]`, existingImageInput.value);
        }
        
        // Handle new image file
        if (fileInput && fileInput.files.length > 0) {
            formData.append(`vip_list[${index}][image]`, fileInput.files[0]);
        }
    });
    
    // Log FormData contents
    console.log(' FormData contents:');
    for (let pair of formData.entries()) {
        if (pair[1] instanceof File) {
            console.log(pair[0], '= [FILE]', pair[1].name);
        } else {
            console.log(pair[0], '=', pair[1]);
        }
    }
    
    // Show loading
    Swal.fire({
        title: 'Saving VIP Section...',
        html: 'Please wait while we update the VIP information',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error(' CSRF token not found');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Security token not found. Please refresh the page.',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    // Send request
    fetch(`/admin/programs/${window.programId}/vip`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log(' Response status:', response.status);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error(' Error response:', text);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'VIP section updated successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                console.log(' Reloading page...');
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save VIP section');
        }
    })
    .catch(error => {
        console.error(' Save error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Save Failed',
            text: error.message || 'Failed to save VIP section. Please try again.',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== UPDATED SAVE PARTICIPATION FUNCTION ==========

window.saveParticipation = function(event) {
    event.preventDefault();
    
    console.log('=== PARTICIPATION SAVE STARTED ===');
    console.log('Program ID:', window.programId);
    
    const formElement = document.getElementById('participationForm');
    if (!formElement) {
        console.error('Participation form not found');
        return;
    }
    
    const formData = new FormData(formElement);

    // ========== HANDLE DESCRIPTIONS ==========
    const descItems = document.querySelectorAll('#participationDescContainer textarea[name="participation_description[]"]');
    formData.delete('participation_description[]'); // Clear existing
    
    descItems.forEach((textarea, index) => {
        const value = textarea.value.trim();
        if (value) {
            formData.append(`participation_description[${index}]`, value);
        }
    });
    
    console.log('Descriptions collected');

    // ========== HANDLE PRICES ==========
    const priceItems = document.querySelectorAll('#priceContainer .price-item');
    formData.delete('participation_prices[]'); // Clear existing
    
    let priceIndex = 0;
    priceItems.forEach((item) => {
        const descInput = item.querySelector('input[name$="[description]"]');
        const amountInput = item.querySelector('input[name$="[amount]"]');
        
        const desc = descInput ? descInput.value.trim() : '';
        const amount = amountInput ? amountInput.value.trim() : '';
        
        // Only add if at least one field has content
        if (desc || amount) {
            formData.append(`participation_prices[${priceIndex}][description]`, desc);
            formData.append(`participation_prices[${priceIndex}][amount]`, amount);
            priceIndex++;
        }
    });
    
    console.log('Prices collected:', priceIndex);

    // ========== HANDLE FORM TYPE ==========
    const formTypeInput = document.getElementById('participationFormType');
    if (formTypeInput) {
        formData.set('participation_form_type', formTypeInput.value);
        console.log('Form type:', formTypeInput.value);
    }

    // ========== DEBUG: LOG FORMDATA ==========
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        if (pair[1] instanceof File) {
            console.log(pair[0], '= [FILE]', pair[1].name);
        } else {
            console.log(pair[0], '=', pair[1]);
        }
    }

    // ========== SHOW LOADING ==========
    Swal.fire({
        title: 'Saving...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // ========== SEND REQUEST ==========
    const url = `/admin/programs/${window.programId}/participation`;
    console.log('Sending to:', url);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                throw new Error(`HTTP ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Success response:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Participation section updated successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save participation section',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== SPONSORSHIP SECTION - CONDITIONAL DISPLAY ==========

window.saveSponsorship = function(event) {
    event.preventDefault();
    
    console.log('=== SPONSORSHIP SAVE STARTED ===');
    
    const formElement = document.getElementById('sponsorshipForm');
    if (!formElement) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(formElement);
    
    // ========== HANDLE DESCRIPTIONS ==========
    const descItems = document.querySelectorAll('#sponsorshipDescContainer textarea[name="sponsorship_description[]"]');
    formData.delete('sponsorship_description[]'); // Clear existing
    
    descItems.forEach((textarea, index) => {
        const value = textarea.value.trim();
        if (value) {
            formData.append(`sponsorship_description[${index}]`, value);
        }
    });
    
    console.log('Descriptions collected');
    
    // ========== HANDLE PACKAGES ==========
    const packageItems = document.querySelectorAll('#packageContainer .price-item');
    formData.delete('sponsorship_packages[]'); // Clear existing
    
    let packageIndex = 0;
    packageItems.forEach((item) => {
        const descInput = item.querySelector('input[name$="[description]"]');
        const amountInput = item.querySelector('input[name$="[amount]"]');
        
        const desc = descInput ? descInput.value.trim() : '';
        const amount = amountInput ? amountInput.value.trim() : '';
        
        // Only add if at least one field has content
        if (desc || amount) {
            formData.append(`sponsorship_packages[${packageIndex}][description]`, desc);
            formData.append(`sponsorship_packages[${packageIndex}][amount]`, amount);
            packageIndex++;
        }
    });
    
    console.log('Packages collected:', packageIndex);
    
    // ========== HANDLE FORM TYPE ==========
    const formTypeInput = document.getElementById('sponsorshipFormType');
    if (formTypeInput) {
        formData.set('sponsorship_form_type', formTypeInput.value);
        console.log('Form type:', formTypeInput.value);
    }
    
    // ========== DEBUG: LOG FORMDATA ==========
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        if (pair[1] instanceof File) {
            console.log(pair[0], '= [FILE]', pair[1].name);
        } else {
            console.log(pair[0], '=', pair[1]);
        }
    }
    
    // ========== SHOW LOADING ==========
    Swal.fire({
        title: 'Saving...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // ========== SEND REQUEST ==========
    const url = `/admin/programs/${window.programId}/sponsorship`;
    console.log('Sending to:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                throw new Error(`HTTP ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Success response:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Sponsorship section updated successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save sponsorship section',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== REMOVE FILE FUNCTIONS ==========

window.removeAdditionalFile = function(fieldName, filePath) {
    Swal.fire({
        title: 'Remove File?',
        text: 'Are you sure you want to remove this additional file?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Removing...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send AJAX request to remove file from server
            fetch(`/admin/programs/${window.programId}/remove-file`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    field_name: fieldName,
                    file_path: filePath
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        text: 'File removed successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Remove the file container from DOM
                        const container = event.target.closest('.current-file-container');
                        if (container) {
                            container.remove();
                        }
                    });
                } else {
                    throw new Error(data.message || 'Failed to remove file');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to remove file',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

window.removeFormFile = function(fieldName, filePath) {
    Swal.fire({
        title: 'Remove Form File?',
        text: 'Are you sure you want to remove this form file?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Removing...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send AJAX request to remove file from server
            fetch(`/admin/programs/${window.programId}/remove-file`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    field_name: fieldName,
                    file_path: filePath
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        text: 'Form file removed successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Remove the file container from DOM
                        const container = event.target.closest('.current-file-container');
                        if (container) {
                            container.remove();
                        }
                    });
                } else {
                    throw new Error(data.message || 'Failed to remove file');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to remove file',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}


// ========== PROGRAMME FUNCTIONS ==========

window.saveProgramme = function(event) {
    event.preventDefault();
    
    const formElement = document.getElementById('programmeForm');
    const formData = new FormData(formElement);
    
    // Handle description array
    const descItems = document.querySelectorAll('#programmeDescContainer textarea[name="programme_description[]"]');
    descItems.forEach((textarea, index) => {
        if (textarea.value.trim()) {
            formData.set(`programme_description[${index}]`, textarea.value);
        }
    });
    
    // Handle images
    const imageInput = document.getElementById('programmeImageInput');
    if (imageInput && imageInput.files) {
        for (let i = 0; i < imageInput.files.length; i++) {
            formData.append('programme_images[]', imageInput.files[i]);
        }
    }
    
    Swal.fire({
        title: 'Saving Programme...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/programs/${window.programId}/programme`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Programme added successfully',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Reset form
            formElement.reset();
            document.getElementById('programmeImagePreviewGrid').innerHTML = '';
            window.selectedProgrammeImages = [];
            
            // Reload programme items
            if (typeof loadProgrammeItems === 'function') {
                loadProgrammeItems();
            }
        } else {
            throw new Error(data.message || 'Failed to save programme');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save programme',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== PREVIEW PROGRAM ==========

window.previewProgram = function() {
    let currentSection = 'overview';
    
    if (document.getElementById('overview-section').style.display === 'block') {
        currentSection = 'overview';
    } else if (document.getElementById('tentative-section').style.display === 'block') {
        currentSection = 'tentative';
    } else if (document.getElementById('vip-section').style.display === 'block') {
        currentSection = 'vip';
    } else if (document.getElementById('participation-section').style.display === 'block') {
        currentSection = 'participation';
    } else if (document.getElementById('sponsorship-section').style.display === 'block') {
        currentSection = 'sponsorship';
    } else if (document.getElementById('programme-section').style.display === 'block') {
        currentSection = 'programme';
    } else if (document.getElementById('photo-section').style.display === 'block') {
        currentSection = 'photo';    
    }
    
    window.open(`/programs/${window.programId}?section=${currentSection}`, '_blank');
}

// ========== HELPER FUNCTIONS ==========

// IMPROVED PREVIEW PROGRAMME IMAGES FUNCTION
window.previewProgrammeImages = function(input) {
    const previewGrid = document.getElementById('programmeImagePreviewGrid');
    
    if (!input.files || input.files.length === 0) return;
    
    // Check max 3 images
    if (input.files.length > 3) {
        Swal.fire({
            icon: 'warning',
            title: 'Too Many Images',
            text: 'Maximum 3 images allowed',
            confirmButtonColor: '#0d5c3c'
        });
        input.value = '';
        return;
    }
    
    // Clear previous previews
    previewGrid.innerHTML = '';
    window.selectedProgrammeImages = [];
    
    // Create preview for each image
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgDiv = document.createElement('div');
            imgDiv.className = 'programme-img-preview-item';
            imgDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <button type="button" class="delete-preview-btn" onclick="removeProgrammePreviewImage(${index})">x</button>
            `;
            previewGrid.appendChild(imgDiv);
        };
        reader.readAsDataURL(file);
        window.selectedProgrammeImages.push(file);
    });
}

// REMOVE PREVIEW IMAGE
window.removeProgrammePreviewImage = function(index) {
    const input = document.getElementById('programmeImageInput');
    const dt = new DataTransfer();
    
    // Rebuild file list without the removed file
    window.selectedProgrammeImages.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    window.selectedProgrammeImages.splice(index, 1);
    
    // Re-render previews
    const previewGrid = document.getElementById('programmeImagePreviewGrid');
    previewGrid.innerHTML = '';
    
    window.selectedProgrammeImages.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgDiv = document.createElement('div');
            imgDiv.className = 'programme-img-preview-item';
            imgDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${i + 1}">
                <button type="button" class="delete-preview-btn" onclick="removeProgrammePreviewImage(${i})">x</button>
            `;
            previewGrid.appendChild(imgDiv);
        };
        reader.readAsDataURL(file);
    });
}

// ADD PARAGRAPH FUNCTION
window.addParagraph = function(fieldName) {
    let containerId = fieldName + 'Container';
    let inputName = fieldName + '[]';
    
    // Handle special cases
    if (fieldName === 'programmeDesc') {
        containerId = 'programmeDescContainer';
        inputName = 'programme_description[]';
    } else if (fieldName === 'participation') {
        containerId = 'participationDescContainer';
        inputName = 'participation_description[]';
    } else if (fieldName === 'sponsorship') {
        containerId = 'sponsorshipDescContainer';
        inputName = 'sponsorship_description[]';
    } else if (fieldName === 'editProgrammeDesc') {
        containerId = 'editProgrammeDescContainer';
        inputName = 'programme_description[]';
    }
    
    const container = document.getElementById(containerId);
    
    if (!container) {
        console.error('Container not found:', containerId);
        return;
    }
    
    const newParagraph = document.createElement('div');
    newParagraph.className = 'paragraph-item';
    newParagraph.innerHTML = `
        <textarea name="${inputName}" class="form-control paragraph-input" 
                  rows="3" placeholder="Write paragraph here"></textarea>
        <button type="button" class="remove-paragraph-btn" onclick="removeParagraph(this, '${fieldName}')">x</button>
    `;
    container.appendChild(newParagraph);
}

// REMOVE PARAGRAPH
window.removeParagraph = function(button, fieldName) {
    const item = button.closest('.paragraph-item');
    let containerId = fieldName + 'Container';
    
    // Handle special cases
    if (fieldName === 'programmeDesc') {
        containerId = 'programmeDescContainer';
    } else if (fieldName === 'participation') {
        containerId = 'participationDescContainer';
    } else if (fieldName === 'sponsorship') {
        containerId = 'sponsorshipDescContainer';
    } else if (fieldName === 'editProgrammeDesc') {
        containerId = 'editProgrammeDescContainer';
    }
    
    const container = document.getElementById(containerId);
    
    if (container && container.querySelectorAll('.paragraph-item').length > 1) {
        item.remove();
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Remove',
            text: 'At least one paragraph is required',
            confirmButtonColor: '#0d5c3c'
        });
    }
}

// ADD POINT
window.addPoint = function(fieldName) {
    const containerId = fieldName + 'Container';
    const container = document.getElementById(containerId);
    
    if (!container) return;
    
    const newPoint = document.createElement('div');
    newPoint.className = 'point-item';
    newPoint.innerHTML = `
        <textarea name="${fieldName}[]" class="form-control point-input" 
                  rows="2" placeholder="Write objective point here"></textarea>
        <button type="button" class="remove-point-btn" onclick="removePoint(this, '${fieldName}')">x</button>
    `;
    container.appendChild(newPoint);
}

// REMOVE POINT
window.removePoint = function(button, fieldName) {
    const item = button.closest('.point-item');
    const container = item.closest(`#${fieldName}Container`);
    
    if (container && container.querySelectorAll('.point-item').length > 1) {
        item.remove();
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Remove',
            text: 'At least one point is required',
            confirmButtonColor: '#0d5c3c'
        });
    }
}

// SCHEDULE FUNCTIONS
window.addSchedule = function() {
    const container = document.getElementById('schedulesContainer');
    const newSchedule = document.createElement('div');
    newSchedule.className = 'schedule-item';
    newSchedule.innerHTML = `
        <input type="text" name="schedules[${window.scheduleCounter}][time]" class="form-control schedule-time" placeholder="XX:XX a.m/p.m">
        <input type="text" name="schedules[${window.scheduleCounter}][description]" class="form-control schedule-desc" placeholder="Description">
        <button type="button" class="remove-schedule-btn" onclick="removeSchedule(this)">x</button>
    `;
    container.appendChild(newSchedule);
    window.scheduleCounter++;
}

window.removeSchedule = function(button) {
    const item = button.closest('.schedule-item');
    const container = document.getElementById('schedulesContainer');
    if (container.children.length > 1) {
        item.remove();
    }
}

// PRICE FUNCTIONS
window.addPrice = function(type) {
    const container = type === 'participation' ? document.getElementById('priceContainer') : document.getElementById('packageContainer');
    const counter = type === 'participation' ? window.priceCounter++ : window.packageCounter++;
    const fieldName = type === 'participation' ? 'participation_prices' : 'sponsorship_packages';
    const placeholder = type === 'participation' ? 'ex: Per person' : 'ex: Platinum';
    
    const newPrice = document.createElement('div');
    newPrice.className = 'price-item';
    newPrice.innerHTML = `
        <input type="text" name="${fieldName}[${counter}][description]" class="form-control price-desc" placeholder="${placeholder}">
        <input type="text" name="${fieldName}[${counter}][amount]" class="form-control price-amount" placeholder="RM xxxx">
        <button type="button" class="remove-price-btn" onclick="${type === 'participation' ? 'removePrice' : 'removePackage'}(this)">x</button>
    `;
    container.appendChild(newPrice);
}

window.removePrice = function(button) {
    const item = button.closest('.price-item');
    const container = document.getElementById('priceContainer');
    if (container.children.length > 1) {
        item.remove();
    }
}

window.removePackage = function(button) {
    const item = button.closest('.price-item');
    const container = document.getElementById('packageContainer');
    if (container.children.length > 1) {
        item.remove();
    }
}

// FORM TYPE TOGGLE
window.toggleFormType = function(section, type) {
    const fileInput = document.getElementById(`${section}FileInput`);
    const linkInput = document.getElementById(`${section}LinkInput`);
    const typeInput = document.getElementById(`${section}FormType`);
    const buttons = document.querySelectorAll(`#${section}-section .form-type-toggle .toggle-btn`);
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (type === 'file') {
        fileInput.style.display = 'block';
        linkInput.style.display = 'none';
    } else {
        fileInput.style.display = 'none';
        linkInput.style.display = 'block';
    }
    
    typeInput.value = type;
}

// ========== LOAD PROGRAMME ITEMS WITH EDIT/DELETE BUTTONS ==========
window.loadProgrammeItems = function() {
    const container = document.getElementById('programmeItemsList');
    if (!container) return;
    
    fetch(`/admin/programs/${window.programId}/programme-items`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        container.innerHTML = '';
        
        if (!data || data.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #6b7280;">
                    <p>No programme items added yet.</p>
                </div>
            `;
            return;
        }
        
        data.forEach(item => {
            const itemHTML = `
                <div class="programme-item-card" data-id="${item.id}">
                    <div class="programme-item-header">
                        <h4>${item.title}</h4>
                        <div class="programme-item-actions">
                            <button type="button" class="btn-edit-programme" onclick="editProgrammeItem(${item.id})" title="Edit">
                                <img src="/assets/icons/update.png" alt="Edit">
                            </button>
                            <button type="button" class="btn-delete-programme" onclick="deleteProgrammeItem(${item.id}, '${item.title.replace(/'/g, "\\'")}')" title="Delete">
                                <img src="/assets/icons/delete.png" alt="Delete">
                            </button>
                        </div>
                    </div>
                    ${item.images && item.images.length > 0 ? `
                        <div class="programme-item-images">
                            ${item.images.map((img) => `
                                <div class="programme-img-thumb">
                                    <img src="/storage/${img}" alt="Programme Image">
                                    <button type="button" class="delete-img-btn" onclick="deleteProgrammeImage(${item.id}, '${img}')">x</button>
                                </div>
                            `).join('')}
                        </div>
                    ` : ''}
                    ${item.description && item.description.length > 0 ? `
                        <div class="programme-item-description">
                            ${item.description.map(para => `<p>${para}</p>`).join('')}
                        </div>
                    ` : ''}
                </div>
            `;
            container.insertAdjacentHTML('beforeend', itemHTML);
        });
    })
    .catch(error => {
        console.error('Error loading programme items:', error);
        container.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #dc3545;">
                <p>Failed to load programme items.</p>
            </div>
        `;
    });
}

// ========== EDIT PROGRAMME ITEM ==========
window.editProgrammeItem = function(itemId) {
    // Show loading
    Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Fetch item data
    fetch(`/admin/programs/${window.programId}/programme-items/${itemId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success && data.item) {
            showEditProgrammeModal(data.item);
        } else {
            throw new Error(data.message || 'Failed to load item data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to load programme item',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== SHOW EDIT MODAL ==========
function showEditProgrammeModal(item) {
    // Create modal HTML
    const modalHTML = `
        <div id="editProgrammeModal" class="modal-overlay" style="display: flex;">
            <div class="modal-box modal-large">
                <button class="modal-close-btn" onclick="closeEditProgrammeModal()" title="Close">x</button>
                <h2 class="modal-title">EDIT PROGRAMME</h2>
                <div class="modal-content">
                    <form id="editProgrammeForm" onsubmit="handleEditProgrammeSubmit(event, ${item.id})">
                        
                        <div class="form-group">
                            <label>PROGRAMME TITLE <span style="color: red;">*</span></label>
                            <input type="text" 
                                   name="programme_title" 
                                   id="editProgrammeTitle"
                                   class="form-control" 
                                   placeholder="Enter programme title"
                                   value="${item.title}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>CURRENT IMAGES (${item.images ? item.images.length : 0}/3)</label>
                            <div class="current-images-grid" id="editCurrentImages">
                                ${item.images && item.images.length > 0 ? item.images.map((img, idx) => `
                                    <div class="edit-img-item" data-image="${img}">
                                        <img src="/storage/${img}" alt="Image ${idx + 1}">
                                        <button type="button" class="delete-edit-img-btn" onclick="removeEditImage('${img}', ${item.id})">x</button>
                                    </div>
                                `).join('') : '<p style="color: #999;">No images</p>'}
                            </div>
                        </div>

                        <div class="form-group">
                            <label>ADD NEW IMAGES (Max 3 total)</label>
                            <div class="programme-images-upload-wrapper">
                                <div class="programme-image-preview-grid" id="editImagePreviewGrid"></div>
                                <div class="upload-button-wrapper">
                                    <button type="button" class="btn-add-image" onclick="document.getElementById('editProgrammeImageInput').click()">
                                        <img src="/assets/icons/upload.png" alt="Upload" style="width: 24px; margin-right: 8px;">
                                        Add More Images
                                    </button>
                                    <input type="file" 
                                           name="programme_images[]" 
                                           id="editProgrammeImageInput" 
                                           class="hidden-file-input" 
                                           accept="image/*" 
                                           multiple 
                                           onchange="previewEditImages(this, ${item.images ? item.images.length : 0})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>DESCRIPTION</label>
                            <div id="editProgrammeDescContainer">
                                ${item.description && item.description.length > 0 ? 
                                    item.description.map((para, idx) => `
                                        <div class="paragraph-item">
                                            <textarea name="programme_description[]" class="form-control paragraph-input" rows="3">${para}</textarea>
                                            ${idx > 0 ? '<button type="button" class="remove-paragraph-btn" onclick="removeParagraph(this, \'editProgrammeDesc\')">x</button>' : ''}
                                        </div>
                                    `).join('') :
                                    '<div class="paragraph-item"><textarea name="programme_description[]" class="form-control paragraph-input" rows="3"></textarea></div>'
                                }
                            </div>
                            <small>Write by paragraph</small>
                            <button type="button" class="add-paragraph-btn" onclick="addParagraph('editProgrammeDesc')">+ Add Paragraph</button>
                        </div>

                    </form>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn" onclick="closeEditProgrammeModal()">CANCEL</button>
                    <button type="submit" class="save-btn" form="editProgrammeForm">UPDATE</button>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// ========== CLOSE EDIT MODAL ==========
window.closeEditProgrammeModal = function() {
    const modal = document.getElementById('editProgrammeModal');
    if (modal) {
        modal.remove();
    }
}

// ========== REMOVE IMAGE IN EDIT MODE ==========
window.removeEditImage = function(imagePath, itemId) {
    Swal.fire({
        title: 'Delete Image?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/admin/programs/${window.programId}/programme-items/${itemId}/delete-image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ image_path: imagePath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Image deleted successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    // Remove from DOM
                    const imgItem = document.querySelector(`.edit-img-item[data-image="${imagePath}"]`);
                    if (imgItem) {
                        imgItem.remove();
                    }
                    
                    // Update count display
                    const currentImagesDiv = document.getElementById('editCurrentImages');
                    const remaining = currentImagesDiv.querySelectorAll('.edit-img-item').length;
                    currentImagesDiv.previousElementSibling.textContent = `CURRENT IMAGES (${remaining}/3)`;
                } else {
                    throw new Error(data.message || 'Failed to delete image');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to delete image',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

// ========== PREVIEW IMAGES IN EDIT MODE ==========
window.previewEditImages = function(input, currentCount) {
    const previewGrid = document.getElementById('editImagePreviewGrid');
    const maxAllowed = 3 - currentCount;
    
    if (!input.files || input.files.length === 0) return;
    
    if (input.files.length > maxAllowed) {
        Swal.fire({
            icon: 'warning',
            title: 'Too Many Images',
            text: `You can only add ${maxAllowed} more image(s)`,
            confirmButtonColor: '#0d5c3c'
        });
        input.value = '';
        return;
    }
    
    previewGrid.innerHTML = '';
    
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgDiv = document.createElement('div');
            imgDiv.className = 'programme-img-preview-item';
            imgDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <button type="button" class="delete-preview-btn" onclick="removeEditPreview(${index})">x</button>
            `;
            previewGrid.appendChild(imgDiv);
        };
        reader.readAsDataURL(file);
    });
}

// ========== REMOVE PREVIEW IN EDIT MODE ==========
window.removeEditPreview = function(index) {
    const input = document.getElementById('editProgrammeImageInput');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    
    // Rebuild previews
    const previewGrid = document.getElementById('editImagePreviewGrid');
    previewGrid.innerHTML = '';
    
    Array.from(input.files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgDiv = document.createElement('div');
            imgDiv.className = 'programme-img-preview-item';
            imgDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${i + 1}">
                <button type="button" class="delete-preview-btn" onclick="removeEditPreview(${i})">x</button>
            `;
            previewGrid.appendChild(imgDiv);
        };
        reader.readAsDataURL(file);
    });
}

// ========== HANDLE EDIT SUBMIT ==========
window.handleEditProgrammeSubmit = function(event, itemId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    // Validate title
    const title = formData.get('programme_title');
    if (!title || !title.trim()) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Field',
            text: 'Programme title is required',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: 'Updating Programme...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/programs/${window.programId}/programme-items/${itemId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Programme item updated successfully',
                timer: 2000,
                showConfirmButton: false
            });
            
            closeEditProgrammeModal();
            loadProgrammeItems();
        } else {
            throw new Error(data.message || 'Failed to update');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to update programme item',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ========== DELETE PROGRAMME ITEM ==========
window.deleteProgrammeItem = function(itemId, title) {
    Swal.fire({
        title: 'DELETE PROGRAMME?',
        html: `Are you sure you want to delete<br><strong>${title}</strong>?<br><small>This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'YES, DELETE',
        cancelButtonText: 'CANCEL',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/admin/programs/${window.programId}/programme-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Programme item deleted successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    loadProgrammeItems();
                } else {
                    throw new Error(data.message || 'Failed to delete');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to delete programme item',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

// ========== DELETE SINGLE IMAGE FROM PROGRAMME ITEM ==========
window.deleteProgrammeImage = function(itemId, imagePath) {
    Swal.fire({
        title: 'Delete Image?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/admin/programs/${window.programId}/programme-items/${itemId}/delete-image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ image_path: imagePath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Image deleted successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    loadProgrammeItems();
                } else {
                    throw new Error(data.message || 'Failed to delete image');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to delete image',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

// ========== PHOTO SECTION FUNCTIONS ==========

// Load all photo items when section opens
window.loadPhotoItems = function() {
    const container = document.getElementById('photoGalleryList');
    const countElement = document.getElementById('photoCount');
    const bulkActions = document.getElementById('photoBulkActions');
    
    if (!container) return;
    
    fetch(`/admin/programs/${window.programId}/photo-items`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        container.innerHTML = '';
        
        if (!data || data.length === 0) {
            countElement.textContent = '(0)';
            bulkActions.style.display = 'none';
            container.innerHTML = `
                <p style="text-align: center; color: #9ca3af; padding: 20px; grid-column: 1/-1;">No photos uploaded yet</p>
            `;
            return;
        }
        
        countElement.textContent = `(${data.length})`;
        bulkActions.style.display = 'flex';
        
        data.forEach(item => {
            const itemHTML = `
                <div class="photo-card-item" data-id="${item.id}">
                    <div class="photo-checkbox-wrapper">
                        <input type="checkbox" class="photo-select-checkbox" data-photo-id="${item.id}" onchange="updateSelectedCount()">
                    </div>
                    <div class="photo-card-image-wrapper">
                        <img src="/storage/${item.image}" alt="${item.title}">
                        <div class="photo-card-actions">
                            <button type="button" class="photo-card-action-btn" onclick="editPhotoItem(${item.id})" title="Edit">
                                <img src="/assets/icons/update.png" alt="Edit">
                            </button>
                            <button type="button" class="photo-card-action-btn" onclick="deletePhotoItem(${item.id}, '${item.title.replace(/'/g, "\\'")}')" title="Delete">
                                <img src="/assets/icons/delete.png" alt="Delete">
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', itemHTML);
        });
    })
    .catch(error => {
        console.error('Error loading photo items:', error);
        container.innerHTML = `
            <p style="text-align: center; color: #dc3545; padding: 20px; grid-column: 1/-1;">Failed to load photos</p>
        `;
    });
}

window.previewMultiplePhotos = function(input) {
    const container = document.getElementById('photoPreviewContainer');
    const placeholder = document.getElementById('photoUploadPlaceholder');
    
    if (!input.files || input.files.length === 0) return;
    
    // Clear previous previews
    container.innerHTML = '';
    
    // Hide placeholder
    if (placeholder) {
        placeholder.style.display = 'none';
    }
    
    // Create preview grid
    const previewGrid = document.createElement('div');
    previewGrid.style.cssText = 'display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; padding: 15px;';
    previewGrid.id = 'photoPreviewGrid';
    
    // Show total count
    const countBadge = document.createElement('div');
    countBadge.style.cssText = 'grid-column: 1 / -1; padding: 10px; background: #e8f5e9; border-radius: 6px; text-align: center; color: #2e7d32; font-weight: 600; font-size: 14px;';
    countBadge.textContent = `${input.files.length} photo(s) selected`;
    previewGrid.appendChild(countBadge);
    
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.style.cssText = 'position: relative; width: 100%; aspect-ratio: 1; border-radius: 6px; overflow: hidden; background: #f3f4f6; border: 1px solid #e5e7eb;';
            previewItem.id = `photo-preview-${index}`;
            
            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'photo-delete-btn';
            deleteBtn.innerHTML = 'x';
            deleteBtn.style.cssText = 'position: absolute; top: 4px; right: 4px; width: 24px; height: 24px; background: #ef4444; color: white; border: none; border-radius: 50%; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s; z-index: 10;';
            deleteBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                removePhotoPreview(index);
            };
            
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}" style="width: 100%; height: 100%; object-fit: cover;">
            `;
            previewItem.appendChild(deleteBtn);
            
            previewItem.onmouseover = () => deleteBtn.style.opacity = '1';
            previewItem.onmouseout = () => deleteBtn.style.opacity = '0';
            
            previewGrid.appendChild(previewItem);
        };
        
        reader.readAsDataURL(file);
    });
    
    container.appendChild(previewGrid);
}

// Toggle select all checkboxes
window.toggleSelectAll = function() {
    const selectAllCheckbox = document.getElementById('selectAllPhotos');
    const photoCheckboxes = document.querySelectorAll('.photo-select-checkbox');
    
    photoCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

// Update selected count display
window.updateSelectedCount = function() {
    const selectedCheckboxes = document.querySelectorAll('.photo-select-checkbox:checked');
    const countElement = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAllPhotos');
    const totalCheckboxes = document.querySelectorAll('.photo-select-checkbox');
    
    countElement.textContent = selectedCheckboxes.length;
    
    // Update "Select All" checkbox state
    if (selectedCheckboxes.length === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (selectedCheckboxes.length === totalCheckboxes.length) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }
}

// Bulk delete photos
window.bulkDeletePhotos = function() {
    const selectedCheckboxes = document.querySelectorAll('.photo-select-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Photos Selected',
            text: 'Please select at least one photo to delete',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    const photoIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.photoId);
    
    Swal.fire({
        title: 'DELETE SELECTED PHOTOS?',
        html: `Are you sure you want to delete <strong>${photoIds.length}</strong> photo(s)?<br><small>This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'YES, DELETE',
        cancelButtonText: 'CANCEL',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Delete photos one by one
            const deletePromises = photoIds.map(photoId => {
                return fetch(`/admin/programs/${window.programId}/photo-items/${photoId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
            });
            
            Promise.all(deletePromises)
                .then(responses => Promise.all(responses.map(r => r.json())))
                .then(results => {
                    const successCount = results.filter(r => r.success).length;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: `${successCount} photo(s) deleted successfully`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Uncheck select all
                    document.getElementById('selectAllPhotos').checked = false;
                    
                    // Reload photos
                    loadPhotoItems();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete some photos',
                        confirmButtonColor: '#dc3545'
                    });
                });
        }
    });
}

// Remove photo preview and update file input
window.removePhotoPreview = function(index) {
    const input = document.getElementById('photoImageInput');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    
    // Remove the preview item directly without reopening file dialog
    const previewItem = document.getElementById(`photo-preview-${index}`);
    if (previewItem) {
        previewItem.remove();
    }
    
    // If no files left, show placeholder again
    if (input.files.length === 0) {
        const container = document.getElementById('photoPreviewContainer');
        const placeholder = document.getElementById('photoUploadPlaceholder');
        container.innerHTML = '';
        if (placeholder) {
            const clonedPlaceholder = placeholder.cloneNode(true);
            clonedPlaceholder.style.display = 'flex';
            container.appendChild(clonedPlaceholder);
        }
    } else {
        // Update count badge
        const countBadge = document.querySelector('#photoPreviewGrid > div:first-child');
        if (countBadge) {
            countBadge.textContent = `${input.files.length} photo(s) selected`;
        }
    }
}

window.savePhoto = function(event) {
    event.preventDefault();
    
    const formElement = document.getElementById('photoForm');
    const formData = new FormData(formElement);
    
    const imageInput = document.getElementById('photoImageInput');
    const images = imageInput.files;
    
    console.log('=== PHOTO SAVE DEBUG ===');
    console.log('Program ID:', window.programId);
    console.log('Number of Images:', images.length);
    
    if (!images || images.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Field',
            text: 'Please select at least one photo',
            confirmButtonColor: '#0d5c3c'
        });
        return;
    }
    
    // Clear existing photo_image entries and add all files
    formData.delete('photo_image');
    
    Array.from(images).forEach((file) => {
        formData.append('photo_images[]', file);
    });
    
    Swal.fire({
        title: `Uploading ${images.length} photo(s)...`,
        html: 'Please wait while photos are being uploaded',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/programs/${window.programId}/photo`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Photos added successfully',
                timer: 2000,
                showConfirmButton: false
            });
            
            formElement.reset();
            const container = document.getElementById('photoPreviewContainer');
            const placeholder = document.getElementById('photoUploadPlaceholder');
            container.innerHTML = '';
            if (placeholder) {
                const clonedPlaceholder = placeholder.cloneNode(true);
                clonedPlaceholder.style.display = 'flex';
                container.appendChild(clonedPlaceholder);
            }
            
            loadPhotoItems();
        } else {
            throw new Error(data.message || 'Failed to save photos');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save photo',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Edit photo item - fetch and show modal
window.editPhotoItem = function(itemId) {
    Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/programs/${window.programId}/photo-items/${itemId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success && data.item) {
            showEditPhotoModal(data.item);
        } else {
            throw new Error(data.message || 'Failed to load photo data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to load photo',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Show edit photo modal
function showEditPhotoModal(item) {
    const modalHTML = `
        <div id="editPhotoModal" class="modal-overlay" style="display: flex;">
            <div class="modal-box">
                <button class="modal-close-btn" onclick="closeEditPhotoModal()" title="Close">x</button>
                <h2 class="modal-title">EDIT PHOTO</h2>
                <div class="modal-content">
                    <form id="editPhotoForm" onsubmit="handleEditPhotoSubmit(event, ${item.id})" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label>CURRENT PHOTO</label>
                            <div class="photo-preview-container" id="editPhotoPreviewContainer">
                                <img src="/storage/${item.image}" alt="Photo">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>CHANGE PHOTO (Optional)</label>
                            <div class="photo-upload-area">
                                <div id="editPhotoNewPreview"></div>
                                <button type="button" class="btn-save-photo" style="width: 100%; margin-top: 10px;" onclick="document.getElementById('editPhotoImageInput').click(); return false;">
                                    Choose New Photo
                                </button>
                                <input type="file" 
                                       name="photo_image" 
                                       id="editPhotoImageInput" 
                                       class="hidden-file-input" 
                                       accept="image/*" 
                                       onchange="previewEditPhotoImage(this)">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn" onclick="closeEditPhotoModal()">CANCEL</button>
                    <button type="submit" class="save-btn" form="editPhotoForm">UPDATE</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Close edit photo modal
window.closeEditPhotoModal = function() {
    const modal = document.getElementById('editPhotoModal');
    if (modal) {
        modal.remove();
    }
}

// Preview edit photo image
window.previewEditPhotoImage = function(input) {
    const container = document.getElementById('editPhotoNewPreview');
    
    if (!input.files || input.files.length === 0) {
        container.innerHTML = '';
        return;
    }
    
    const file = input.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        container.innerHTML = `
            <div class="photo-preview-container" style="margin-bottom: 15px;">
                <img src="${e.target.result}" alt="New Preview">
            </div>
        `;
    };
    
    reader.readAsDataURL(file);
}

// Handle edit photo submit
window.handleEditPhotoSubmit = function(event, itemId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    Swal.fire({
        title: 'Updating Photo...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/programs/${window.programId}/photo-items/${itemId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Photo updated successfully',
                timer: 2000,
                showConfirmButton: false
            });
            
            closeEditPhotoModal();
            loadPhotoItems();
        } else {
            throw new Error(data.message || 'Failed to update');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to update photo',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Delete photo item
window.deletePhotoItem = function(itemId, title) {
    Swal.fire({
        title: 'DELETE PHOTO?',
        html: `Are you sure you want to delete<br><strong>${title}</strong>?<br><small>This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'YES, DELETE',
        cancelButtonText: 'CANCEL',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/admin/programs/${window.programId}/photo-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Photo deleted successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    loadPhotoItems();
                } else {
                    throw new Error(data.message || 'Failed to delete');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to delete photo',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

// ========== DOM INITIALIZATION ==========
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Show section nav, hide editors
    const sectionNav = document.querySelector('.section-nav');
    if (sectionNav) {
        sectionNav.style.display = 'grid';
    }
    
    document.querySelectorAll('.section-editor').forEach(el => {
        el.style.display = 'none';
    });
    
    // Initialize datepicker
    flatpickr('.date-picker', {
        dateFormat: 'd/m/Y',
        allowInput: true
    });
    
    // ========== SIMPLIFIED SECTION CARD HANDLING ==========
    document.querySelectorAll('.section-card').forEach(card => {
        const sectionName = card.getAttribute('data-section');
        if (!sectionName) return;
        
        console.log('Setting up card:', sectionName);
        
        // Handle edit button - FIRST priority
        const editButton = card.querySelector('.section-edit-btn');
        if (editButton) {
            // Remove any existing listeners
            const newEditBtn = editButton.cloneNode(true);
            editButton.parentNode.replaceChild(newEditBtn, editButton);
            
            // Add fresh listener
            newEditBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Edit button clicked for:', sectionName);
                openSection(sectionName);
                return false;
            }, true); // Use capture phase
        }
        
        // Handle toggle checkbox
        const toggleCheckbox = card.querySelector('.section-toggle input[type="checkbox"]');
        if (toggleCheckbox) {
            toggleCheckbox.addEventListener('change', function(e) {
                e.stopPropagation();
                console.log('Toggle changed for:', sectionName);
                window.toggleSectionDisplay(this);
            });
        }
        
        // Handle card click (only if not clicking buttons)
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on edit button or toggle
            if (e.target.closest('.section-edit-btn') || 
                e.target.closest('.section-toggle') ||
                e.target.tagName === 'INPUT') {
                return;
            }
            
            console.log('Card clicked, opening section:', sectionName);
            openSection(sectionName);
        });
    });
    
    // Setup close buttons
    document.querySelectorAll('.close-section-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            closeSection();
        });
    });
    
    // Make programme upload area clickable
    const programmeInput = document.getElementById('programmeImageInput');
    const programmeBtn = document.querySelector('.btn-add-image');
    if (programmeBtn && programmeInput) {
        programmeBtn.addEventListener('click', function() {
            programmeInput.click();
        });
    }
    
    console.log('Initialization complete');
});

// ========== DEBUGGING HELPER ==========
window.debugVipForm = function() {
    console.log('ðŸ” === VIP FORM DEBUG ===');
    
    const form = document.getElementById('vipForm');
    console.log('Form exists:', !!form);
    
    const container = document.getElementById('vipContainer');
    console.log('Container exists:', !!container);
    
    if (container) {
        const items = container.querySelectorAll('.vip-item');
        console.log('VIP items count:', items.length);
        
        items.forEach((item, index) => {
            const nameInput = item.querySelector('input[name*="[name]"]');
            const positionInput = item.querySelector('input[name*="[position]"]');
            const fileInput = item.querySelector('input[type="file"]');
            const existingImageInput = item.querySelector('input[name*="[existing_image]"]');
            
            console.log(`VIP ${index}:`, {
                dataIndex: item.getAttribute('data-index'),
                name: nameInput ? nameInput.value : 'NO INPUT',
                nameInputName: nameInput ? nameInput.name : 'N/A',
                position: positionInput ? positionInput.value : 'NO INPUT',
                positionInputName: positionInput ? positionInput.name : 'N/A',
                fileInputExists: !!fileInput,
                fileInputName: fileInput ? fileInput.name : 'N/A',
                hasFile: fileInput && fileInput.files.length > 0,
                existingImage: existingImageInput ? existingImageInput.value : 'NO INPUT'
            });
        });
    }
    
    console.log('Current vipCounter:', window.vipCounter);
    console.log('=== END DEBUG ===');
}

console.log(' Complete detail.blade.php JavaScript loaded successfully');

/**
 * Load participant list from participation module
 */
window.loadParticipantList = function() {
    const container = document.getElementById('participantListContainer');
    const statsContainer = document.getElementById('participantStats');
    
    if (!container) {
        console.error('Participant list container not found');
        return;
    }
    
    // Show loading
    container.innerHTML = `
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <div style="font-size: 48px; margin-bottom: 16px;">â³</div>
            <h4 style="margin: 0 0 8px 0; color: #6b7280;">Loading participant data...</h4>
            <p style="margin: 0; font-size: 14px;">Please wait</p>
        </div>
    `;
    
    fetch(`/admin/programs/${window.programId}/participant-list`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (!data.submissions || data.submissions.length === 0) {
                // No data - show helpful message
                const message = data.message || 'No approved participants found';
                
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #9ca3af;">
                        <div style="font-size: 64px; margin-bottom: 20px;">ðŸ”—</div>
                        <h3 style="margin: 0 0 12px 0; color: #6b7280;">No Participants Yet</h3>
                        <p style="margin: 0 0 20px 0; font-size: 14px; color: #9ca3af;">
                            ${message}
                        </p>
                        <button type="button" 
                                class="btn-save" 
                                onclick="openSection('link-participation')"
                                style="margin-top: 10px;">
                            Link Participation Programme â†’
                        </button>
                    </div>
                `;
                statsContainer.style.display = 'none';
                return;
            }
            
            // Show stats
            document.getElementById('totalCompanies').textContent = data.total_companies || 0;
            document.getElementById('totalParticipants').textContent = data.total_participants || 0;
            statsContainer.style.display = 'grid';
            
            // Build table
            let html = `
                <div class="participant-table-wrapper">
                    <table class="participant-table">
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
            `;
            
            data.submissions.forEach((submission, index) => {
                const rowCount = submission.participants.length;
                
                submission.participants.forEach((participant, pIndex) => {
                    html += `
                        <tr>
                            ${pIndex === 0 ? `
                                <td rowspan="${rowCount}" class="table-center table-index">${index + 1}</td>
                                <td rowspan="${rowCount}" class="table-company">${submission.company_name}</td>
                            ` : ''}
                            <td class="table-name">${participant.name}</td>
                            <td class="table-position">${participant.position || '-'}</td>
                            <td class="table-center table-table-no">${participant.table_number || '-'}</td>
                        </tr>
                    `;
                });
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            container.innerHTML = html;
        } else {
            throw new Error(data.message || 'Failed to load participants');
        }
    })
    .catch(error => {
        console.error('Error loading participants:', error);
        container.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #dc3545;">
                <div style="font-size: 48px; margin-bottom: 16px;">âš ï¸</div>
                <h4 style="margin: 0 0 8px 0;">Error Loading Data</h4>
                <p style="margin: 0; font-size: 14px;">${error.message}</p>
                <button type="button" 
                        class="btn-save" 
                        onclick="openSection('link-participation')"
                        style="margin-top: 20px;">
                    Check Link Settings â†’
                </button>
            </div>
        `;
        statsContainer.style.display = 'none';
    });
}

// Update openSection to load participant list when opened
const originalOpenSection = window.openSection;
window.openSection = function(sectionName) {
    originalOpenSection(sectionName);
    
    // Load participant list when section is opened
    if (sectionName === 'participant-list' && typeof loadParticipantList === 'function') {
        loadParticipantList();
    }
}
/**
 * Preview participant list on public site
 */
window.previewParticipantList = function() {
    const programId = window.programId;
    window.open(`/programs/${programId}?section=participant-list`, '_blank');
}
window.saveLinkParticipation = function(event) {
    event.preventDefault();
    
    const formData = new FormData(document.getElementById('linkParticipationForm'));
    
    // Show loading
    Swal.fire({
        title: 'Saving...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/programs/${window.programId}/link-participation`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: data.message || 'Link updated successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to save link',
            confirmButtonColor: '#dc3545'
        });
    });
}
// Add this at the END of your detail.blade.php file, after all other scripts
// Or add to admin-template.blade.php in the <?php echo $__env->yieldPushContent('scripts'); ?> section

document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing sidebar toggle...');
    
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const toggleBtn = document.getElementById("toggleSidebar");
    
    // Check if elements exist
    if (!sidebar) {
        console.error('Sidebar element not found!');
        return;
    }
    if (!overlay) {
        console.error('Overlay element not found!');
        return;
    }
    if (!toggleBtn) {
        console.error('Toggle button not found!');
        return;
    }
    
    console.log('All sidebar elements found');
    
    function openSidebar() {
        console.log('Opening sidebar');
        sidebar.classList.add("open");
        overlay.classList.add("show");
        document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
    }
    
    function closeSidebar() {
        console.log('Closing sidebar');
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
        document.body.style.overflow = ''; // Re-enable scrolling
    }
    
    // Remove any existing listeners to prevent duplicates
    const newToggleBtn = toggleBtn.cloneNode(true);
    toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);
    
    const newOverlay = overlay.cloneNode(true);
    overlay.parentNode.replaceChild(newOverlay, overlay);
    
    // Add fresh event listeners
    newToggleBtn.addEventListener("click", function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Toggle button clicked');
        
        const currentSidebar = document.getElementById("sidebar");
        if (currentSidebar.classList.contains("open")) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });
    
    newOverlay.addEventListener("click", function(e) {
        e.preventDefault();
        console.log('Overlay clicked');
        closeSidebar();
    });
    
    // Close sidebar on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const currentSidebar = document.getElementById("sidebar");
            if (currentSidebar.classList.contains("open")) {
                closeSidebar();
            }
        }
    });
    
    console.log('Sidebar toggle initialized successfully');
});
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.admin-template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\dashboard\kedahforward\resources\views/admin/programs/detail.blade.php ENDPATH**/ ?>