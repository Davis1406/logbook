@extends('layouts.master')
@section('content')

{{-- Flash message --}}
{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Hospital</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('hospital/list/page') }}">Hospitals</a></li>
                        <li class="breadcrumb-item active">Edit Hospital</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('hospital/update', $hospital->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Hospital Details</span></h5>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Hospital Name <span class="login-danger">*</span></label>
                                        <input type="text" name="hospital_name" class="form-control @error('hospital_name') is-invalid @enderror"
                                               value="{{ old('hospital_name', $hospital->hospital_name) }}" placeholder="Enter Hospital Name">
                                        @error('hospital_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Director <span class="login-danger">*</span></label>
                                        <input type="text" name="director" class="form-control @error('director') is-invalid @enderror"
                                               value="{{ old('director', $hospital->director) }}" placeholder="Enter Director's Name">
                                        @error('director')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Address <span class="login-danger">*</span></label>
                                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                               value="{{ old('address', $hospital->address) }}" placeholder="Enter Address">
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Country <span class="login-danger">*</span></label>
                                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                                               value="{{ old('country', $hospital->country) }}" placeholder="Enter Country">
                                        @error('country')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Status <span class="login-danger">*</span></label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="active" {{ $hospital->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $hospital->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Update Hospital</button>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
