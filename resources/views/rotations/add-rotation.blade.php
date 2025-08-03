@extends('layouts.master')

@section('content')
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Log Operation</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('operations/list') }}">Operations</a></li>
                        <li class="breadcrumb-item active">Add Operation</li>
                    </ul>
                </div>
            </div>
        </div>

        <form id="operationForm" action="{{ route('operations/store') }}" method="POST">
            @csrf

            <!-- Step Indicator -->
            <div class="step-indicator mb-4 d-flex justify-content-between">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="step" data-step="{{ $i }}">
                        <div class="step-number">{{ $i }}</div>
                        <div class="step-label">
                            @if($i === 1) Rotation & Objective
                            @elseif($i === 2) Procedure Details
                            @else Notes & Submit @endif
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Step 1: Rotation & Objective -->
            <div class="step-content active" data-step="1">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Rotation <span class="text-danger">*</span></label>
                        <select name="rotation_id" class="form-select" required>
                            <option value="">Select Rotation</option>
                            @foreach($rotations as $rotation)
                                <option value="{{ $rotation->id }}">{{ $rotation->rotation_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Objective <span class="text-danger">*</span></label>
                        <select name="objective_id" class="form-select" required>
                            <option value="">Select Objective</option>
                            @foreach($objectives as $objective)
                                <option value="{{ $objective->id }}">{{ $objective->objective_code }} - {{ $objective->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2: Procedure Details -->
            <div class="step-content" data-step="2">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Procedure Date <span class="text-danger">*</span></label>
                        <input type="date" name="procedure_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Procedure Time <span class="text-danger">*</span></label>
                        <input type="time" name="procedure_time" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" name="duration" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Participation Type <span class="text-danger">*</span></label>
                        <select name="participation_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Assist">Assist</option>
                            <option value="Perform">Perform</option>
                            <option value="Observe">Observe</option>
                            <option value="Perform with Assist">Perform with Assist</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 3: Notes & Submit -->
            <div class="step-content" data-step="3">
                <div class="mb-3">
                    <label>Procedure Notes</label>
                    <textarea name="procedure_notes" class="form-control" rows="4" placeholder="Enter notes here..."></textarea>
                </div>

                <!-- Summary -->
                <div class="card bg-light">
                    <div class="card-header">Operation Summary</div>
                    <div class="card-body row">
                        <div class="col-md-6">
                            <p><strong>Rotation:</strong> <span id="summary-rotation">Not selected</span></p>
                            <p><strong>Objective:</strong> <span id="summary-objective">Not selected</span></p>
                            <p><strong>Date:</strong> <span id="summary-date">Not set</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Time:</strong> <span id="summary-time">Not set</span></p>
                            <p><strong>Duration:</strong> <span id="summary-duration">Not set</span></p>
                            <p><strong>Participation:</strong> <span id="summary-participation">Not selected</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="prevBtn" onclick="changeStep(-1)">Previous</button>
                <button type="button" class="btn btn-success" id="nextBtn" onclick="changeStep(1)">Next</button>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="display:none;">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 3;

    function changeStep(direction) {
        const steps = document.querySelectorAll('.step-content');
        if (direction === 1 && !validateStep(currentStep)) return;

        steps[currentStep - 1].classList.remove('active');
        currentStep += direction;
        steps[currentStep - 1].classList.add('active');

        document.getElementById('prevBtn').style.display = currentStep > 1 ? 'inline-block' : 'none';
        document.getElementById('nextBtn').style.display = currentStep < totalSteps ? 'inline-block' : 'none';
        document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-block' : 'none';

        if (currentStep === totalSteps) updateSummary();
    }

    function validateStep(step) {
        const inputs = document.querySelectorAll(`.step-content[data-step="${step}"] input, .step-content[data-step="${step}"] select`);
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                return false;
            }
            input.classList.remove('is-invalid');
        }
        return true;
    }

    function updateSummary() {
        document.getElementById('summary-rotation').innerText = document.querySelector('[name="rotation_id"]')?.selectedOptions[0]?.text || 'Not selected';
        document.getElementById('summary-objective').innerText = document.querySelector('[name="objective_id"]')?.selectedOptions[0]?.text || 'Not selected';
        document.getElementById('summary-date').innerText = document.querySelector('[name="procedure_date"]').value || 'Not set';
        document.getElementById('summary-time').innerText = document.querySelector('[name="procedure_time"]').value || 'Not set';
        document.getElementById('summary-duration').innerText = document.querySelector('[name="duration"]').value + ' mins' || 'Not set';
        document.getElementById('summary-participation').innerText = document.querySelector('[name="participation_type"]')?.selectedOptions[0]?.text || 'Not selected';
    }

    document.addEventListener('DOMContentLoaded', () => {
        changeStep(0);
        document.querySelector('[name="procedure_date"]').valueAsDate = new Date();
    });
</script>
@endpush
