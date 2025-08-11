@extends('layouts.master')
@section('content')

{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="page-title">Activity Logs</h3>
            @if(Session::get('role_name') === 'Trainee')
                <a href="{{ route('operations/add') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Activity
                </a>
            @endif
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if(Session::get('role_name') !== 'Trainee')
                                <th>Trainee</th>
                            @endif
                            <th>Rotation</th>
                            <th>Objective</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Supervisor</th>
                            <th>Actions</th>
                        </tr> 
                    </thead>
                    <tbody>
                        @forelse($operations as $op)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            @if(Session::get('role_name') !== 'Trainee')
                                <td>{{ $op->trainee->first_name . ' ' . $op->trainee->last_name ?? 'N/A' }}</td>
                            @endif

                            <td>{{ $op->rotation->rotation_name ?? 'N/A' }}</td>
                            <td>{{ $op->objective->objective_code ?? 'N/A' }}</td>
                            <td>{{ $op->procedure_date }}</td>
                            <td>{{ $op->procedure_time }}</td>
                            <td>{{ $op->duration }} mins</td>
                            <td>{{ $op->participation_type }}</td>
                            <td>
                                <span class="badge bg-{{ $op->status == 'approved' ? 'success' : ($op->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($op->status) }}
                                </span>
                            </td>
                            <td>{{ $op->supervisor_name ?? 'N/A' }}</td>
                            <td class="text-nowrap">
                                @php
                                    $canEditDelete = Session::get('role_name') === 'Trainee' && isset($currentTraineeId) && $op->trainee_id == $currentTraineeId;
                                    $isSupervisor = Session::get('role_name') === 'Supervisor';
                                    $isAdmin = Session::get('role_name') === 'Admin';
                                @endphp

                                {{-- Trainee Edit Button --}}
                                @if($canEditDelete)
                                    <a href="{{ route('operations/edit', $op->id) }}" class="btn btn-sm btn-outline-info me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif

                                {{-- Supervisor Update Status Button --}}
                                @if($isSupervisor)
                                    <button type="button" class="btn btn-sm btn-warning me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#statusModal{{ $op->id }}" 
                                            title="Update Status">
                                        <i class="fas fa-clipboard-check me-1"></i>Review
                                    </button>
                                @endif

                                {{-- Admin Edit Button --}}
                                @if($isAdmin)
                                    <a href="{{ route('operations/edit', $op->id) }}" class="btn btn-sm btn-outline-info me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif

                                {{-- View Button (All Roles) --}}
                                <a href="{{ route('operation/view', $op->id) }}" class="btn btn-sm btn-outline-primary me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Delete Button (Only Trainee) --}}
                                @if($canEditDelete)
                                    <a href="{{ route('operations/delete', $op->id) }}"
                                       onclick="return confirm('Are you sure you want to delete this operation?')"
                                       class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>

                        {{-- Status Update Modal for Supervisors --}}
                        @if($isSupervisor)
                        <div class="modal fade" id="statusModal{{ $op->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $op->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel{{ $op->id }}">
                                            <i class="fas fa-clipboard-check me-2"></i>Review & Update Operation Status
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('operations/update', $op->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <h6 class="text-muted">Operation Details:</h6>
                                                <p class="mb-1"><strong>Trainee:</strong> {{ $op->trainee->first_name . ' ' . $op->trainee->last_name ?? 'N/A' }}</p>
                                                <p class="mb-1"><strong>Objective:</strong> {{ $op->objective->objective_code ?? 'N/A' }}</p>
                                                <p class="mb-1"><strong>Date:</strong> {{ $op->procedure_date }}</p>
                                                <p class="mb-3"><strong>Current Status:</strong> 
                                                    <span class="badge bg-{{ $op->status == 'approved' ? 'success' : ($op->status == 'rejected' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($op->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Update Status <span class="text-danger">*</span></label>
                                                <select name="status" class="form-control" required>
                                                    <option value="">Select Status</option>
                                                    <option value="pending" {{ $op->status == 'pending' ? 'selected' : '' }}>
                                                        🕐 Pending Review
                                                    </option>
                                                    <option value="approved" {{ $op->status == 'approved' ? 'selected' : '' }}>
                                                        ✅ Approved
                                                    </option>
                                                    <option value="rejected" {{ $op->status == 'rejected' ? 'selected' : '' }}>
                                                        ❌ Rejected
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group mt-3">
                                                <label class="form-label">Supervisor Remarks</label>
                                                <textarea name="supervisor_remarks" 
                                                         class="form-control" 
                                                         rows="4"
                                                         placeholder="Enter your comments, feedback, or reasons for approval/rejection...">{{ old('supervisor_remarks', $op->supervisor_remarks ?? '') }}</textarea>
                                                <small class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Provide constructive feedback to help the trainee improve their performance.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check-circle me-1"></i>Update Status & Save Remarks
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No operations logged.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<style>
    .modal-content {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
    }
    
    .modal-header .btn-close {
        filter: invert(1);
    }
    
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.8em;
    }
    
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
        font-weight: 600;
    }
    
    .btn-warning:hover {
        background-color: #ffca2c;
        border-color: #ffc720;
        color: #212529;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
    }
</style>

<script>
    // Add some interactivity to the status modals
    $(document).ready(function() {
        // Handle form submission with loading state
        $('form[action*="operations/update"]').on('submit', function() {
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...').prop('disabled', true);
            
            // Re-enable after a delay in case of errors
            setTimeout(function() {
                submitBtn.html(originalText).prop('disabled', false);
            }, 3000);
        });
        
        // Auto-close modals on successful update
        @if(session('success'))
            $('.modal').modal('hide');
        @endif
    });
</script>
@endsection