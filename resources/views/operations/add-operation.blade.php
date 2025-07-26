@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Log Operation</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('operations/list') }}">Operations</a></li>
                        <li class="breadcrumb-item active">Add Operation</li>
                    </ul>
                </div>
            </div>
        </div>
        <form action="{{ route('operations/store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <label>Rotation <span class="text-danger">*</span></label>
                    <select name="rotation_id" class="form-control" required>
                        <option value="">Select Rotation</option>
                        @foreach($rotations as $rotation)
                            <option value="{{ $rotation->id }}">{{ $rotation->rotation_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Objective <span class="text-danger">*</span></label>
                    <select name="objective_id" class="form-control" required>
                        <option value="">Select Objective</option>
                        @foreach($objectives as $objective)
                            <option value="{{ $objective->id }}">{{ $objective->objective_code }} - {{ $objective->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Procedure Date</label>
                    <input type="date" name="procedure_date" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <label>Procedure Time</label>
                    <input type="time" name="procedure_time" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <label>Duration (minutes)</label>
                    <input type="number" name="duration" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <label>Participation Type</label>
                    <select name="participation_type" class="form-control" required>
                        <option value="">Select Type</option>
                        <option value="Assist">Assist</option>
                        <option value="Perform">Perform</option>
                        <option value="Observe">Observe</option>
                        <option value="Perform with Assist">Perform with Assist</option>
                    </select>
                </div>
                <div class="col-12">
                    <label>Procedure Notes</label>
                    <textarea name="procedure_notes" class="form-control" rows="4" placeholder="Enter notes here..."></textarea>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Submit Operation</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection