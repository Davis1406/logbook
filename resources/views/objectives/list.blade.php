@extends('layouts.master')
@section('content')

{{-- Flash Messages --}}
{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="page-title">Objectives List</h3>
            <a href="{{ route('objectives.add') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Objective
            </a>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Objective Code</th>
                                    <th>Description</th>
                                    <th>Rotation</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($objectives as $objective)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $objective->objective_code }}</td>
                                        <td>{{ Str::limit($objective->description, 50) }}</td>
                                        <td>{{ $objective->rotation->rotation_name ?? 'N/A' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('objectives.edit', $objective->id) }}" class="btn btn-sm bg-success-light me-2">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('objectives.delete', $objective->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm bg-danger-light" onclick="return confirm('Are you sure you want to delete this objective?')">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No objectives found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection