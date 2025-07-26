@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header"><h3>Operation Details</h3></div>
        <div class="row">
            <div class="col-md-6"><strong>Trainee:</strong> {{ $operation->trainee->name ?? 'N/A' }}</div>
            <div class="col-md-6"><strong>Supervisor:</strong> {{ $operation->supervisor_name ?? 'N/A' }}</div>
            <div class="col-md-6"><strong>Rotation:</strong> {{ $operation->rotation->rotation_name }}</div>
            <div class="col-md-6"><strong>Objective:</strong> {{ $operation->objective->objective_code }} - {{ $operation->objective->description }}</div>
            <div class="col-md-6"><strong>Date:</strong> {{ $operation->procedure_date }}</div>
            <div class="col-md-6"><strong>Time:</strong> {{ $operation->procedure_time }}</div>
            <div class="col-md-6"><strong>Duration:</strong> {{ $operation->duration }} mins</div>
            <div class="col-md-6"><strong>Type:</strong> {{ $operation->participation_type }}</div>
            <div class="col-12"><strong>Notes:</strong> {{ $operation->procedure_notes }}</div>
            <div class="col-12 mt-2"><strong>Status:</strong>
                <span class="badge bg-{{ $operation->status == 'approved' ? 'success' : ($operation->status == 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($operation->status) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection