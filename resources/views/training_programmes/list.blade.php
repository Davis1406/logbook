@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Training Programmes</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Programmes</li>
                    </ul>
                </div>
                <div class="col-auto text-end ms-auto">
                    <a href="{{ route('training-programmes.add') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Programme</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <table class="table table-hover table-center datatable table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Programme Name</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($programmes as $programme)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $programme->programme_name }}</td>
                                    <td>{{ $programme->duration }} years</td>
                                    <td><span class="badge bg-{{ $programme->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($programme->status) }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('training-programmes.edit', $programme->id) }}" class="btn btn-sm bg-success-light me-2"><i class="feather-edit"></i></a>
                                        <form action="{{ route('training-programmes.delete', $programme->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm bg-danger" onclick="return confirm('Are you sure?')"><i class="feather-trash-2 text-white"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No programmes found.</td>
                                </tr>
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
