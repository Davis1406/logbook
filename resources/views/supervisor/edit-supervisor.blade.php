@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Supervisor</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supervisors/list') }}">Supervisors</a></li>
                        <li class="breadcrumb-item active">Edit Supervisor</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- message --}}
        {!! Toastr::message() !!}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('supervisor/update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $supervisor->id }}">

                            <div class="row">
                                {{-- Name --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $supervisor->name }}">
                                    </div>
                                </div>

                                {{-- Gender --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control select">
                                            <option value="Male" {{ $supervisor->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $supervisor->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Others" {{ $supervisor->gender == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Country --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Country</label>
                                        <input type="text" name="country" class="form-control" value="{{ $supervisor->country }}">
                                    </div>
                                </div>

                                {{-- Mobile --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Mobile Number</label>
                                        <input type="text" name="mobile" class="form-control" value="{{ $supervisor->mobile }}">
                                    </div>
                                </div>

                                {{-- Programme --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Programme</label>
                                        <select name="programme_id" class="form-control select">
                                            @foreach ($programmes as $programme)
                                                <option value="{{ $programme->id }}" {{ $supervisor->programme_id == $programme->id ? 'selected' : '' }}>
                                                    {{ $programme->programme_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Hospital --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Hospital</label>
                                        <select name="hospital_id" class="form-control select">
                                            @foreach ($hospitals as $hospital)
                                                <option value="{{ $hospital->id }}" {{ $supervisor->hospital_id == $hospital->id ? 'selected' : '' }}>
                                                    {{ $hospital->hospital_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Profile Photo --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Change Photo</label>
                                        <input type="file" name="avatar" class="form-control">
                                        @if($supervisor->avatar)
                                            <img src="{{ Storage::url('app/public/' .$supervisor->avatar) }}" class="mt-2" width="120" height="120">
                                        @endif
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12">
                                    <div class="student-submit text-end">
                                        <button type="submit" class="btn btn-primary">Update Supervisor</button>
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
