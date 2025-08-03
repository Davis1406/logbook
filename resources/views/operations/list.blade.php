@extends('layouts.master')
@section('content')

{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="page-title">Operation Logs</h3>
            @if(Session::get('role_name') !== 'Admin')
                <a href="{{ route('operations/add') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Operation
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
                                @endphp

                                @if($canEditDelete || $isSupervisor)
                                    <a href="{{ route('operations/edit', $op->id) }}" class="btn btn-sm btn-outline-info me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif

                                <a href="{{ route('operation/view', $op->id) }}" class="btn btn-sm btn-outline-primary me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($canEditDelete)
                                    <a href="{{ route('operations/delete', $op->id) }}"
                                       onclick="return confirm('Are you sure you want to delete this operation?')"
                                       class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
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
