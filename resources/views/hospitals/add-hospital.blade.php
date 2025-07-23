@extends('layouts.master')
@section('content')

{{-- Flash message --}}
{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Hospital</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('hospital/list/page') }}">Hospitals</a></li>
                        <li class="breadcrumb-item active">Add Hospital</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('hospital/store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Hospital Details</span></h5>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Hospital Name <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('hospital_name') is-invalid @enderror" name="hospital_name" value="{{ old('hospital_name') }}" placeholder="Enter Hospital Name">
                                        @error('hospital_name')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Director <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('director') is-invalid @enderror" name="director" value="{{ old('director') }}" placeholder="Enter Director's Name">
                                        @error('director')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Address <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="Enter Address">
                                        @error('address')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Country <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" placeholder="Enter Country">
                                        @error('country')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Status <span class="login-danger">*</span></label>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Add Hospital</button>
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
