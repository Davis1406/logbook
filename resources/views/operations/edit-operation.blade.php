@extends('layouts.master')

@section('content')

{{-- Flash Messages --}}
{!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Breadcrumb -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Edit Activity</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Indicator -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="step-indicator mb-4">
                                <div class="step active" data-step="1">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Rotation & Objective</div>
                                </div>
                                <div class="step" data-step="2">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Procedure Details</div>
                                </div>
                                <div class="step" data-step="3">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Notes & Update</div>
                                </div>
                            </div>

                            <form id="operationForm" action="{{ route('operations/update', $operation->id) }}" method="POST">
                                @csrf  
                                <!-- Step 1: Rotation & Objective -->
                                <div class="step-content active" data-step="1">
                                    <div class="form-section">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                            <h4 class="mb-0">Rotation & Objective</h4>
                                        </div>
                                        <p class="text-muted mb-4">Update your rotation and the objective for this operation</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Rotation <span class="text-danger">*</span></label>
                                                    <select name="rotation_id" id="rotation_id"
                                                        class="form-control select @error('rotation_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Select Rotation</option>
                                                        @foreach ($rotations as $rotation)
                                                            <option value="{{ $rotation->id }}"
                                                                {{ (old('rotation_id', $operation->rotation_id) == $rotation->id) ? 'selected' : '' }}>
                                                                {{ $rotation->rotation_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('rotation_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Objective <span class="text-danger">*</span></label>
                                                    <select name="objective_id" id="objective_id"
                                                        class="form-control select @error('objective_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Select Objective</option>
                                                        @foreach ($objectives as $objective)
                                                            <option value="{{ $objective->id }}"
                                                                {{ (old('objective_id', $operation->objective_id) == $objective->id) ? 'selected' : '' }}>
                                                                {{ $objective->objective_code }} - {{ $objective->description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('objective_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Procedure Details -->
                                <div class="step-content" data-step="2">
                                    <div class="form-section">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-stethoscope me-2 text-primary"></i>
                                            <h4 class="mb-0">Procedure Details</h4>
                                        </div>
                                        <p class="text-muted mb-4">Update specific details about the procedure</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Procedure Date <span class="text-danger">*</span></label>
                                                    <input type="date" name="procedure_date"
                                                        class="form-control @error('procedure_date') is-invalid @enderror"
                                                        value="{{ old('procedure_date', $operation->procedure_date) }}" required>
                                                    @error('procedure_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Procedure Time <span class="text-danger">*</span></label>
                                                    <input type="time" name="procedure_time"
                                                        class="form-control @error('procedure_time') is-invalid @enderror"
                                                        value="{{ old('procedure_time', $operation->procedure_time) }}" required>
                                                    @error('procedure_time')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                                    <input type="number" name="duration"
                                                        class="form-control @error('duration') is-invalid @enderror"
                                                        min="1" placeholder="60" 
                                                        value="{{ old('duration', $operation->duration) }}" required>
                                                    @error('duration')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Participation Type <span class="text-danger">*</span></label>
                                                    <select name="participation_type"
                                                        class="form-control select @error('participation_type') is-invalid @enderror"
                                                        required>
                                                        <option value="">Select Type</option>
                                                        <option value="Assist"
                                                            {{ (old('participation_type', $operation->participation_type) == 'Assist') ? 'selected' : '' }}>
                                                            Assist</option>
                                                        <option value="Perform"
                                                            {{ (old('participation_type', $operation->participation_type) == 'Perform') ? 'selected' : '' }}>
                                                            Perform</option>
                                                        <option value="Observe"
                                                            {{ (old('participation_type', $operation->participation_type) == 'Observe') ? 'selected' : '' }}>
                                                            Observe</option>
                                                        <option value="Perform with Assist"
                                                            {{ (old('participation_type', $operation->participation_type) == 'Perform with Assist') ? 'selected' : '' }}>
                                                            Perform with Assist</option>
                                                    </select>
                                                    @error('participation_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Supervisor</label>
                                                    <select name="supervisor_id" id="supervisor_id" class="form-control select" required>
                                                        <option value="">Select Supervisor</option>
                                                        @foreach ($supervisors as $supervisor)
                                                            @php
                                                                // Check if this supervisor matches the current operation's supervisor
                                                                $isSelected = false;
                                                                if ($operation->supervisor_id) {
                                                                    // If operation has supervisor_id, match against supervisor.id
                                                                    $currentSupervisor = \App\Models\Supervisor::find($operation->supervisor_id);
                                                                    $isSelected = $currentSupervisor && $currentSupervisor->supervisor_id == $supervisor->supervisor_id;
                                                                }
                                                                // Also check old input
                                                                $isSelected = $isSelected || old('supervisor_id') == $supervisor->supervisor_id;
                                                            @endphp
                                                            <option value="{{ $supervisor->supervisor_id }}"
                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                {{ $supervisor->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">
                                                        Please select your assigned supervisor from the list.
                                                    </small>
                                                    @error('supervisor_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Notes & Submit -->
                                <div class="step-content" data-step="3">
                                    <div class="form-section">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-file-medical me-2 text-primary"></i>
                                            <h4 class="mb-0">Procedure Notes</h4>
                                        </div>
                                        <p class="text-muted mb-4">Update any additional notes or observations about the procedure</p>

                                        <div class="form-group mb-4">
                                            <label class="form-label">Procedure Notes</label>
                                            <textarea name="procedure_notes" class="form-control @error('procedure_notes') is-invalid @enderror" rows="6"
                                                placeholder="Enter notes here...">{{ old('procedure_notes', $operation->procedure_notes) }}</textarea>
                                            <small class="form-text text-muted">Include any relevant observations, learning points, complications, or other important details about the procedure.</small>
                                            @error('procedure_notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Summary Box -->
                                        <div class="card border-left-primary">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-clipboard-list me-2"></i>
                                                    Operation Summary
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Rotation:</strong> <span id="summary-rotation" class="text-muted">{{ $operation->rotation->rotation_name ?? 'Not selected' }}</span></p>
                                                        <p><strong>Objective:</strong> <span id="summary-objective" class="text-muted">{{ $operation->objective ? $operation->objective->objective_code . ' - ' . $operation->objective->description : 'Not selected' }}</span></p>
                                                        <p><strong>Date:</strong> <span id="summary-date" class="text-muted">{{ $operation->procedure_date ?? 'Not set' }}</span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Time:</strong> <span id="summary-time" class="text-muted">{{ $operation->procedure_time ?? 'Not set' }}</span></p>
                                                        <p><strong>Duration:</strong> <span id="summary-duration" class="text-muted">{{ $operation->duration ? $operation->duration . ' minutes' : 'Not set' }}</span></p>
                                                        <p><strong>Participation:</strong> <span id="summary-participation" class="text-muted">{{ $operation->participation_type ?? 'Not selected' }}</span></p>
                                                        <p><strong>Supervisor:</strong> <span id="summary-supervisor" class="text-muted">
                                                            @if($operation->supervisor_id)
                                                                {{ $operation->supervisor->name ?? 'Unknown Supervisor' }}
                                                            @elseif($operation->supervisor_name)
                                                                {{ $operation->supervisor_name }}
                                                            @else
                                                                Not selected
                                                            @endif
                                                        </span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" id="prevBtn"
                                        onclick="changeStep(-1)" style="display: none;">
                                        <i class="fas fa-arrow-left me-2"></i>Previous
                                    </button>
                                    <div></div>
                                    <button type="button" class="btn btn-primary" id="nextBtn"
                                        onclick="changeStep(1)">
                                        Continue <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                        <i class="fas fa-save me-2"></i>Update Operation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e3e6f0;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            border: 2px solid #e3e6f0;
            background: white;
            color: #858796;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: #5a5c69;
            color: white;
            border-color: #5a5c69;
        }

        .step.completed .step-number {
            background: #fe5067;
            color: white;
            border-color: #fe5067;
        }

        .step.completed .step-number i {
            font-size: 16px;
        }

        .step-label {
            font-size: 0.875rem;
            color: #858796;
            text-align: center;
            font-weight: 500;
        }

        .step.active .step-label {
            color: #5a5c69;
            font-weight: 600;
        }

        .step.completed .step-label {
            color: #fe5067;
            font-weight: 600;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
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

        .form-section {
            padding: 0;
        }

        .card.border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .text-primary {
            color: #5a5c69 !important;
        }

        /* Dark mode support */
        .dark-theme .step-number {
            background: #2d3748;
            border-color: #4a5568;
            color: #a0aec0;
        }

        .dark-theme .step.active .step-number {
            background: #4299e1;
            border-color: #4299e1;
        }

        .dark-theme .step.completed .step-number {
            background: #fe5067;
            border-color: #fe5067;
        }

        .dark-theme .step-label {
            color: #a0aec0;
        }

        .dark-theme .step.active .step-label {
            color: #4299e1;
        }

        .dark-theme .step.completed .step-label {
            color: #38a169;
        }

        .dark-theme .step-indicator::before {
            background: #4a5568;
        }
    </style>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function changeStep(direction) {
            if (direction === 1) {
                // Validate current step before proceeding - SHOW ERRORS HERE
                if (!validateStep(currentStep, true)) { // Pass true to show errors
                    return;
                }

                if (currentStep < totalSteps) {
                    currentStep++;
                }
            } else {
                if (currentStep > 1) {
                    currentStep--;
                }
            }

            updateStepDisplay();
            if (currentStep === totalSteps) {
                updateSummary();
            }
        }

        function validateStep(step, showErrors = false) {
            switch (step) {
                case 1:
                    const rotation = document.querySelector('[name="rotation_id"]').value;
                    const objective = document.querySelector('[name="objective_id"]').value;
                    if (!rotation || !objective) {
                        if (showErrors) {
                            toastr.error('Please select both rotation and objective to continue.');
                        }
                        return false;
                    }
                    return true;
                case 2:
                    const date = document.querySelector('[name="procedure_date"]').value;
                    const time = document.querySelector('[name="procedure_time"]').value;
                    const duration = document.querySelector('[name="duration"]').value;
                    const participation = document.querySelector('[name="participation_type"]').value;
                    const supervisor = document.querySelector('[name="supervisor_id"]').value;
                    if (!date || !time || !duration || !participation || !supervisor) {
                        if (showErrors) {
                            toastr.error('Please fill in all required procedure details.');
                        }
                        return false;
                    }
                    return true;
                case 3:
                    return true; // Notes are optional
                default:
                    return true;
            }
        }

        function updateStepDisplay() {
            // Update step indicators
            document.querySelectorAll('.step').forEach((step, index) => {
                const stepNum = index + 1;
                step.classList.remove('active', 'completed');

                if (stepNum < currentStep) {
                    step.classList.add('completed');
                    step.querySelector('.step-number').innerHTML = '<i class="fas fa-check"></i>';
                } else if (stepNum === currentStep) {
                    step.classList.add('active');
                    step.querySelector('.step-number').textContent = stepNum;
                } else {
                    step.querySelector('.step-number').textContent = stepNum;
                }
            });

            // Update step content
            document.querySelectorAll('.step-content').forEach((content, index) => {
                content.classList.remove('active');
                if (index + 1 === currentStep) {
                    content.classList.add('active');
                }
            });

            // Update navigation buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            prevBtn.style.display = currentStep > 1 ? 'block' : 'none';

            if (currentStep === totalSteps) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'block';
            } else {
                nextBtn.style.display = 'block';
                submitBtn.style.display = 'none';
                // Don't show errors when just checking if button should be enabled
                nextBtn.disabled = !validateStep(currentStep, false);
            }
        }

        function updateSummary() {
            const rotationSelect = document.querySelector('[name="rotation_id"]');
            const objectiveSelect = document.querySelector('[name="objective_id"]');
            const date = document.querySelector('[name="procedure_date"]').value;
            const time = document.querySelector('[name="procedure_time"]').value;
            const duration = document.querySelector('[name="duration"]').value;
            const participationSelect = document.querySelector('[name="participation_type"]');
            const supervisorSelect = document.querySelector('[name="supervisor_id"]');

            document.getElementById('summary-rotation').textContent =
                rotationSelect.selectedOptions[0]?.text || 'Not selected';
            document.getElementById('summary-objective').textContent =
                objectiveSelect.selectedOptions[0]?.text || 'Not selected';
            document.getElementById('summary-date').textContent = date || 'Not set';
            document.getElementById('summary-time').textContent = time || 'Not set';
            document.getElementById('summary-duration').textContent =
                duration ? duration + ' minutes' : 'Not set';
            document.getElementById('summary-participation').textContent =
                participationSelect.selectedOptions[0]?.text || 'Not selected';
            
            // Handle supervisor display
            let supervisorDisplay = 'Not selected';
            if (supervisorSelect.selectedOptions[0] && supervisorSelect.value) {
                supervisorDisplay = supervisorSelect.selectedOptions[0].text;
            }
            document.getElementById('summary-supervisor').textContent = supervisorDisplay;
        }

        // Add AJAX functionality for rotation change
        $('#rotation_id').on('change', function() {
            const rotationId = $(this).val();
            const objectiveSelect = $('#objective_id');
            
            // Clear and disable objective select
            objectiveSelect.empty().append('<option value="">Loading objectives...</option>');
            
            if (rotationId) {
                // Fetch objectives for selected rotation using the correct route
                $.ajax({
                    url: `{{ url('/objectives/by-rotation') }}/${rotationId}`,
                    type: 'GET',
                    success: function(objectives) {
                        objectiveSelect.empty().append('<option value="">Select Objective</option>');
                        
                        objectives.forEach(function(objective) {
                            objectiveSelect.append(
                                `<option value="${objective.id}">${objective.objective_code} - ${objective.description}</option>`
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        objectiveSelect.empty().append('<option value="">Error loading objectives</option>');
                        toastr.error('Failed to load objectives for the selected rotation.');
                    }
                });
            } else {
                objectiveSelect.empty().append('<option value="">Select Objective</option>');
            }
        });

        // Initialize when document is ready
        $(document).ready(function() {
            // Initialize Select2 for dropdowns
            $('.select').select2({
                width: '100%'
            });

            // Add change listeners to form inputs to enable/disable next button
            $('input, select, textarea').on('change input', function() {
                const nextBtn = document.getElementById('nextBtn');
                if (currentStep < totalSteps) {
                    // Only check validation silently to enable/disable button
                    nextBtn.disabled = !validateStep(currentStep, false);
                }
            });

            // Form submission
            $('#operationForm').on('submit', function(e) {
                e.preventDefault();

                // Validate all steps before submission
                let allValid = true;
                for (let i = 1; i <= totalSteps; i++) {
                    if (!validateStep(i, false)) {
                        allValid = false;
                        break;
                    }
                }

                if (!allValid) {
                    toastr.error('Please fill in all required fields before submitting.');
                    return;
                }

                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
                submitBtn.disabled = true;

                // Submit the form
                this.submit();
            });

            // Initialize the form
            updateStepDisplay();

            // Show success message if redirected back with success
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            // Show validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}');
                @endforeach
            @endif
        });
    </script>
@endsection