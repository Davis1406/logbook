@extends('layouts.master')
@section('content')

{{-- Flash Messages --}}
{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Objective</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('objectives.list') }}">Objectives</a></li>
                        <li class="breadcrumb-item active">Add Objective</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('objectives.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Objective Code</label>
                                        <input type="text" name="objective_code" class="form-control" placeholder="Enter Objective Code" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Rotation</label>
                                        <select name="rotation_id" class="form-control" required>
                                            <option value="">Select Rotation</option>
                                            @foreach($rotations as $rotation)
                                                <option value="{{ $rotation->id }}">{{ $rotation->rotation_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="4" placeholder="Enter Description" required></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Add Objective</button>
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