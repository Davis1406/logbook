@extends('layouts.master')
@section('content')

{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="page-title">Operation Logs</h3>
            @if(Session::get('role_name') !== 'Admin')
                <a href="{{ route('operations/create') }}" class="btn btn-primary">
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
                            <th>Trainee</th>
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
                            <td>{{ $op->trainee->name ?? 'N/A' }}</td>
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
                            <td>
                                @if(Session::get('role_name') === 'Supervisor')
                                    <a href="{{ route('operations/edit', $op->id) }}" class="btn btn-sm btn-warning">Review</a>
                                @elseif(Session::get('role_name') === 'Trainee' && $op->trainee_id == auth()->id())
                                    <a href="{{ route('operations/edit', $op->id) }}" class="btn btn-sm btn-info">Edit</a>
                                @endif
                                <a href="{{ route('operations/view', $op->id) }}" class="btn btn-sm btn-primary">View</a>
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
