@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Rotation</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('rotations.list') }}">Rotations</a></li>
                        <li class="breadcrumb-item active">Edit Rotation</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('rotations.update', $rotation->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Rotation Name <span class="login-danger">*</span></label>
                                        <input type="text" name="rotation_name" class="form-control @error('rotation_name') is-invalid @enderror" value="{{ old('rotation_name', $rotation->rotation_name) }}">
                                        @error('rotation_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Training Programme <span class="login-danger">*</span></label>
                                        <select name="programme_id" class="form-control @error('programme_id') is-invalid @enderror">
                                            @foreach($programmes as $programme)
                                                <option value="{{ $programme->id }}" {{ $rotation->programme_id == $programme->id ? 'selected' : '' }}>{{ $programme->programme_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('programme_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Update Rotation</button>
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