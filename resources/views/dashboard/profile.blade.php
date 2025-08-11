@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Profile</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-auto profile-image">
                                <a href="#">
                                    <img class="rounded-circle" alt="{{ Session::get('name') }}"
                                        src="/images/{{ Session::get('avatar') ?: 'default-avatar.png' }}"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                </a>
                            </div>
                            <div class="col ms-md-n2 profile-user-info">
                                @if (Session::get('role_name') == 'Trainee')
                                    <h4 class="user-name mb-0">{{ $trainee->first_name ?? '' }}
                                        {{ $trainee->last_name ?? '' }}</h4>
                                    <h6 class="text-muted">{{ $trainee->programme->name ?? 'Training Programme' }}</h6>
                                    <div class="user-Location">
                                        <i class="fas fa-hospital"></i> {{ $trainee->hospital->name ?? 'Hospital' }}
                                    </div>
                                    <div class="about-text">
                                        <span class="badge badge-info">Year {{ $trainee->study_year ?? 'N/A' }}</span>
                                        <span class="badge badge-secondary">{{ $trainee->country ?? '' }}</span>
                                    </div>
                                @elseif(Session::get('role_name') == 'Supervisor')
                                    <h4 class="user-name mb-0">{{ $supervisor->name ?? Session::get('name') }}</h4>
                                    <h6 class="text-muted">{{ Session::get('position') ?? 'Supervisor' }}</h6>
                                    <div class="user-Location">
                                        <i class="fas fa-hospital"></i> {{ $supervisor->hospital->name ?? 'Hospital' }}
                                    </div>
                                    <div class="about-text">
                                        <span
                                            class="badge badge-success">{{ $supervisor->programme->name ?? 'Programme' }}</span>
                                        <span class="badge badge-secondary">{{ $supervisor->country ?? '' }}</span>
                                    </div>
                                @else
                                    <h4 class="user-name mb-0">{{ Session::get('name') }}</h4>
                                    <h6 class="text-muted">{{ Session::get('position') ?? Session::get('role_name') }}</h6>
                                    <div class="user-Location">
                                        {{-- <i class="fas fa-building"></i> {{ Session::get('department') ?? 'Administration' }} --}}
                                    </div>
                                    <div class="about-text">
                                        <span class="badge badge-primary">{{ Session::get('role_name') }}</span>
                                    </div>
                                @endif
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">Profile Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Security</a>
                        </li>
                        @if (Session::get('role_name') == 'Trainee')
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#training_tab">Training Info</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="tab-content profile-tab-cont">
                    <!-- Profile Details Tab -->
                    <div class="tab-pane fade show active" id="per_details_tab">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>Personal Information</span>
                                            <a class="edit-link" data-bs-toggle="modal" href="#edit_personal_details">
                                                <i class="far fa-edit me-1"></i>Edit
                                            </a>
                                        </h5>

                                        @if (Session::get('role_name') == 'Trainee')
                                            <!-- Trainee Specific Fields -->
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Trainee ID</p>
                                                <p class="col-sm-9"><strong>{{ $trainee->trainee_id ?? 'N/A' }}</strong>
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Full Name</p>
                                                <p class="col-sm-9">{{ $trainee->first_name ?? '' }}
                                                    {{ $trainee->last_name ?? '' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email</p>
                                                <p class="col-sm-9">{{ $trainee->email ?? 'Not provided' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Phone</p>
                                                <p class="col-sm-9">{{ $trainee->phone_number ?? 'Not provided' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Gender</p>
                                                <p class="col-sm-9">{{ $trainee->gender ?? 'Not specified' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Country</p>
                                                <p class="col-sm-9">{{ $trainee->country ?? 'Not specified' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Study Year</p>
                                                <p class="col-sm-9">
                                                    <span class="badge badge-primary">Year
                                                        {{ $trainee->study_year ?? 'N/A' }}</span>
                                                </p>
                                            </div>
                                        @elseif(Session::get('role_name') == 'Supervisor')
                                            <!-- Supervisor Specific Fields -->
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Supervisor ID</p>
                                                <p class="col-sm-9">
                                                    <strong>{{ $supervisor->supervisor_id ?? 'N/A' }}</strong></p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
                                                <p class="col-sm-9">{{ $supervisor->name ?? Session::get('name') }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile</p>
                                                <p class="col-sm-9">{{ $supervisor->mobile ?? 'Not provided' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Gender</p>
                                                <p class="col-sm-9">{{ $supervisor->gender ?? 'Not specified' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Country</p>
                                                <p class="col-sm-9">{{ $supervisor->country ?? 'Not specified' }}</p>
                                            </div>
                                        @else
                                            <!-- Admin/User Fields -->
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">User ID</p>
                                                <p class="col-sm-9">
                                                    <strong>{{ Session::get('user_id') ?? 'N/A' }}</strong></p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
                                                <p class="col-sm-9">{{ Session::get('name') }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email</p>
                                                <p class="col-sm-9">{{ Session::get('email') }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Phone</p>
                                                <p class="col-sm-9">{{ Session::get('phone_number') ?? 'Not provided' }}
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Department</p>
                                                <p class="col-sm-9">{{ Session::get('department') ?? 'Not specified' }}
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Join Date</p>
                                                <p class="col-sm-9">
                                                    {{ Session::get('join_date') ? \Carbon\Carbon::parse(Session::get('join_date'))->format('M d, Y') : 'Not available' }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                @if (Session::get('role_name') == 'Trainee')
                                    <!-- Training Progress for Trainee - Moved to top -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Training Progress</h5>
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <div class="border-end">
                                                        <h4 class="text-primary mb-1">
                                                            {{ $trainee->operations->count() ?? 0 }}</h4>
                                                        <p class="text-muted mb-0">Operations</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <h4 class="text-success mb-1">{{ $trainee->study_year ?? 0 }}</h4>
                                                    <p class="text-muted mb-0">Current Year</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Account Status Card -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>Account Status</span>
                                            @if (Session::get('role_name') == 'Admin')
                                                <a class="edit-link" href="#"><i
                                                        class="far fa-edit me-1"></i>Edit</a>
                                            @endif
                                        </h5>
                                        <button
                                            class="btn btn-{{ Session::get('status') == 'Active' ? 'success' : 'warning' }}"
                                            type="button">
                                            <i class="fe fe-check-verified"></i> {{ Session::get('status') ?? 'Active' }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Role Information Card -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Role Information</h5>
                                        <div class="mb-3">
                                            <span
                                                class="badge badge-lg badge-{{ Session::get('role_name') == 'Admin' ? 'danger' : (Session::get('role_name') == 'Supervisor' ? 'success' : 'primary') }}">
                                                {{ Session::get('role_name') }}
                                            </span>
                                        </div>
                                        @if (Session::get('role_name') != 'Admin')
                                            <p class="text-muted mb-2"><strong>Programme:</strong></p>
                                            <p class="mb-2">
                                                {{ (Session::get('role_name') == 'Trainee' ? $trainee->programme->name : $supervisor->programme->name) ?? 'Not assigned' }}
                                            </p>
                                            <p class="text-muted mb-2"><strong>Hospital:</strong></p>
                                            <p class="mb-0">
                                                {{ (Session::get('role_name') == 'Trainee' ? $trainee->hospital->name : $supervisor->hospital->name) ?? 'Not assigned' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Training Info Tab (Trainee Only) -->
                    @if (Session::get('role_name') == 'Trainee')
                        <div id="training_tab" class="tab-pane fade">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Training Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <p class="col-sm-5 text-muted mb-2"><strong>Admission ID:</strong></p>
                                                <p class="col-sm-7 mb-2">{{ $trainee->admission_id ?? 'Not provided' }}
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-5 text-muted mb-2"><strong>Programme:</strong></p>
                                                <p class="col-sm-7 mb-2">{{ $trainee->programme->name ?? 'Not assigned' }}
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-5 text-muted mb-2"><strong>Hospital:</strong></p>
                                                <p class="col-sm-7 mb-2">{{ $trainee->hospital->name ?? 'Not assigned' }}
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-5 text-muted mb-2"><strong>Current Year:</strong></p>
                                                <p class="col-sm-7 mb-2">
                                                    <span class="badge badge-info">Year
                                                        {{ $trainee->study_year ?? 'N/A' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <p class="col-sm-5 text-muted mb-2"><strong>Total Operations:</strong></p>
                                                <p class="col-sm-7 mb-2">
                                                    <span
                                                        class="badge badge-success">{{ $trainee->operations->count() ?? 0 }}</span>
                                                </p>
                                            </div>
                                            @if ($trainee->upload)
                                                <div class="row">
                                                    <p class="col-sm-5 text-muted mb-2"><strong>Documents:</strong></p>
                                                    <p class="col-sm-7 mb-2">
                                                        <a href="/uploads/{{ $trainee->upload }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-file"></i> View Document
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Password Tab -->
                    <div id="password_tab" class="tab-pane fade">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Change Password</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form action="{{ route('change/password') }}" method="POST">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label class="form-label">Current Password</label>
                                                <input type="password"
                                                    class="form-control @error('current_password') is-invalid @enderror"
                                                    name="current_password" placeholder="Enter current password">
                                                @error('current_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">New Password</label>
                                                <input type="password"
                                                    class="form-control @error('new_password') is-invalid @enderror"
                                                    name="new_password" placeholder="Enter new password">
                                                @error('new_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Password must be at least 8 characters long.
                                                </small>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">Confirm New Password</label>
                                                <input type="password"
                                                    class="form-control @error('new_confirm_password') is-invalid @enderror"
                                                    name="new_confirm_password" placeholder="Confirm new password">
                                                @error('new_confirm_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i>Update Password
                                                </button>
                                                <button type="reset" class="btn btn-outline-secondary ms-2">
                                                    <i class="fas fa-undo me-1"></i>Reset
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Edit Personal Details Modal -->
    <div class="modal fade" id="edit_personal_details" tabindex="-1" role="dialog"
        aria-labelledby="editPersonalDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPersonalDetailsLabel">Edit Personal Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-profile-form"
                        action="@if (Session::get('role_name') == 'Trainee') {{ route('trainee/update') }}@elseif(Session::get('role_name') == 'Supervisor'){{ route('supervisor/update') }}@else{{ route('user/update') }} @endif"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <form
                            action="@if (Session::get('role_name') == 'Trainee') {{ route('trainee/update') }}@elseif(Session::get('role_name') == 'Supervisor'){{ route('supervisor/update') }}@else{{ route('user/update') }} @endif"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            @if (Session::get('role_name') == 'Trainee')
                                <input type="hidden" name="trainee_id" value="{{ $trainee->id ?? '' }}">
                            @elseif(Session::get('role_name') == 'Supervisor')
                                <input type="hidden" name="id" value="{{ $supervisor->id ?? '' }}">
                            @else
                                <input type="hidden" name="user_id" value="{{ Session::get('user_id') }}">
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Profile Picture</label>
                                        <input type="file" class="form-control" name="avatar" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            @if (Session::get('role_name') == 'Trainee')
                                                First Name
                                            @else
                                                Name
                                            @endif
                                        </label>
                                        <input type="text" class="form-control"
                                            name="@if (Session::get('role_name') == 'Trainee') first_name@else{{ Session::get('role_name') == 'Supervisor' ? 'name' : 'name' }} @endif"
                                            value="@if (Session::get('role_name') == 'Trainee') {{ $trainee->first_name ?? '' }}@elseif(Session::get('role_name') == 'Supervisor'){{ $supervisor->name ?? '' }}@else{{ Session::get('name') }} @endif"
                                            required>
                                    </div>
                                </div>
                            </div>

                            @if (Session::get('role_name') == 'Trainee')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                value="{{ $trainee->last_name ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $trainee->email ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (Session::get('role_name') != 'Admin')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control"
                                                name="@if (Session::get('role_name') == 'Trainee') phone_number@else{{ 'mobile' }} @endif"
                                                value="@if (Session::get('role_name') == 'Trainee') {{ $trainee->phone_number ?? '' }}@else{{ $supervisor->mobile ?? '' }} @endif">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Gender</label>
                                            <select class="form-control" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male"
                                                    @if (Session::get('role_name') == 'Trainee') {{ ($trainee->gender ?? '') == 'Male' ? 'selected' : '' }}@else{{ ($supervisor->gender ?? '') == 'Male' ? 'selected' : '' }} @endif>
                                                    Male</option>
                                                <option value="Female"
                                                    @if (Session::get('role_name') == 'Trainee') {{ ($trainee->gender ?? '') == 'Female' ? 'selected' : '' }}@else{{ ($supervisor->gender ?? '') == 'Female' ? 'selected' : '' }} @endif>
                                                    Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (Session::get('role_name') != 'Admin')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Country</label>
                                            <input type="text" class="form-control" name="country"
                                                value="@if (Session::get('role_name') == 'Trainee') {{ $trainee->country ?? '' }}@else{{ $supervisor->country ?? '' }} @endif">
                                        </div>
                                    </div>
                                    @if (Session::get('role_name') == 'Trainee')
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Study Year</label>
                                                <select class="form-control" name="study_year">
                                                    <option value="">Select Year</option>
                                                    <option value="1"
                                                        {{ ($trainee->study_year ?? '') == '1' ? 'selected' : '' }}>Year 1
                                                    </option>
                                                    <option value="2"
                                                        {{ ($trainee->study_year ?? '') == '2' ? 'selected' : '' }}>Year 2
                                                    </option>
                                                    <option value="3"
                                                        {{ ($trainee->study_year ?? '') == '3' ? 'selected' : '' }}>Year 3
                                                    </option>
                                                    <option value="4"
                                                        {{ ($trainee->study_year ?? '') == '4' ? 'selected' : '' }}>Year 4
                                                    </option>
                                                    <option value="5"
                                                        {{ ($trainee->study_year ?? '') == '5' ? 'selected' : '' }}>Year 5
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="edit-profile-form" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection