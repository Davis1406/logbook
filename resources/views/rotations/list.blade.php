@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Rotations</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rotations</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Rotation List</h3>
                                </div>
                                <div class="col-auto text-end ms-auto">
                                    <a href="{{ route('rotations.add') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Rotation
                                    </a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover table-center datatable table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Rotation Name</th>
                                    <th>Training Programme</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rotations as $rotation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rotation->rotation_name }}</td>
                                    <td>{{ $rotation->programme->programme_name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('rotations.edit', $rotation->id) }}" class="btn btn-sm bg-danger-light me-2">
                                            <i class="feather-edit"></i>
                                        </a>
                                        <form action="{{ route('rotations.delete', $rotation->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm bg-danger" onclick="return confirm('Are you sure?')">
                                                <i class="feather-trash-2 text-white"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No rotations found.</td>
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