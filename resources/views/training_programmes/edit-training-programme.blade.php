@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Training Programme</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('training-programmes.list') }}">Training Programmes</a></li>
                        <li class="breadcrumb-item active">Edit Programme</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('training-programmes.update', $programme->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Programme Details</span></h5>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Programme Name <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control" name="programme_name" value="{{ old('programme_name', $programme->programme_name) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Duration (Years) <span class="login-danger">*</span></label>
                                        <input type="number" class="form-control" name="duration" value="{{ old('duration', $programme->duration) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Status <span class="login-danger">*</span></label>
                                        <select class="form-control" name="status">
                                            <option value="active" {{ $programme->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $programme->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Update Programme</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
