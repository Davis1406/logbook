@extends('layouts.master')
@section('content')

{{-- Flash Messages --}}
{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Objective</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('objectives.list') }}">Objectives</a></li>
                        <li class="breadcrumb-item active">Edit Objective</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('objectives.update', $objective->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Objective Code</label>
                                        <input type="text" name="objective_code" value="{{ $objective->objective_code }}" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Rotation</label>
                                        <select name="rotation_id" class="form-control" required>
                                            @foreach($rotations as $rotation)
                                                <option value="{{ $rotation->id }}" {{ $objective->rotation_id == $rotation->id ? 'selected' : '' }}>
                                                    {{ $rotation->rotation_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="4" required>{{ $objective->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Objective</button>
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