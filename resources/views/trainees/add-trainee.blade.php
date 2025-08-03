@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Add Trainee</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('trainees/list') }}">Trainees</a></li>
                                <li class="breadcrumb-item active">Add Trainee</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Message --}}
            {!! Toastr::message() !!}

            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('trainee/add/save') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    {{-- Personal Info --}}
                                    <div class="col-12">
                                        <h5 class="form-title student-info">Personal Information</h5>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>First Name <span class="login-danger">*</span></label>
                                            <input type="text" name="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                value="{{ old('first_name') }}" placeholder="Enter First Name">
                                            @error('first_name')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Last Name <span class="login-danger">*</span></label>
                                            <input type="text" name="last_name"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                value="{{ old('last_name') }}" placeholder="Enter Last Name">
                                            @error('last_name')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Gender <span class="login-danger">*</span></label>
                                            <select class="form-control select @error('gender') is-invalid @enderror"
                                                name="gender">
                                                <option selected disabled>Select Gender</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Country <span class="login-danger">*</span></label>
                                            <input type="text" name="country"
                                                class="form-control @error('country') is-invalid @enderror"
                                                value="{{ old('country') }}" placeholder="Enter Country">
                                            @error('country')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" placeholder="Enter Email">
                                            @error('email')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Phone Number <span class="login-danger">*</span></label>
                                            <input type="text" name="phone_number"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                value="{{ old('phone_number') }}" placeholder="Enter Phone Number">
                                            @error('phone_number')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Study Year <span class="login-danger">*</span></label>
                                            <input type="number" name="study_year"
                                                class="form-control @error('study_year') is-invalid @enderror"
                                                placeholder="e.g. 1, 2, 3" value="{{ old('study_year') }}">
                                            @error('study_year')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Programme <span class="login-danger">*</span></label>
                                            <select class="form-control select @error('programme_id') is-invalid @enderror"
                                                name="programme_id">
                                                <option selected disabled>Select Programme</option>
                                                @foreach ($programmes as $programme)
                                                    <option value="{{ $programme->id }}"
                                                        {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                                                        {{ $programme->programme_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('programme_id')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- NEW: Hospital Dropdown --}}
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Hospital <span class="login-danger">*</span></label>
                                            <select class="form-control select @error('hospital_id') is-invalid @enderror"
                                                name="hospital_id">
                                                <option selected disabled>Select Hospital</option>
                                                @foreach ($hospitals as $hospital)
                                                    <option value="{{ $hospital->id }}"
                                                        {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                                        {{ $hospital->hospital_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('hospital_id')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Admission ID <span class="login-danger">*</span></label>
                                            <input type="text" name="admission_id"
                                                class="form-control @error('admission_id') is-invalid @enderror"
                                                value="{{ old('admission_id') }}" placeholder="Enter Admission ID">
                                            @error('admission_id')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group students-up-files">
                                            <label>Upload Trainee Photo</label>
                                            <div class="uplod">
                                                <label
                                                    class="file-upload image-upbtn mb-0 @error('upload') is-invalid @enderror"
                                                    for="traineePhoto">
                                                    <span id="uploadText">Choose File</span>
                                                    <input type="file" name="upload" id="traineePhoto"
                                                        accept="image/*" style="display: none;"
                                                        onchange="handleFileUpload(this)">
                                                </label>
                                                @error('upload')
                                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>

                                            <!-- File information display -->
                                            <div id="fileInfoContainer" class="mt-3" style="display: none;">
                                                <div class="card border-light">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="file-icon me-2">
                                                                <i class="fas fa-image text-primary"></i>
                                                            </div>
                                                            <div class="file-details flex-grow-1">
                                                                <div class="file-name font-weight-bold"
                                                                    id="selectedFileName"></div>
                                                                <div class="file-size text-muted small"
                                                                    id="selectedFileSize"></div>
                                                            </div>
                                                            <div class="file-actions">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    onclick="removeFile()">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Image preview -->
                                            <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                                <div class="text-center">
                                                    <img id="imagePreview" src="" alt="Preview"
                                                        class="img-thumbnail"
                                                        style="max-width: 120px; max-height: 120px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Login Info --}}
                                    <div class="col-12">
                                        <h5 class="form-title mt-4"><span>Login Credentials</span></h5>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Password <span class="login-danger">*</span></label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Enter Password">
                                            @error('password')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Confirm Password <span class="login-danger">*</span></label>
                                            <input type="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Repeat Password">
                                            @error('password_confirmation')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary w-100">Save Trainee</button>
                                        </div>
                                    </div>
                                </div> <!-- /.row -->
                            </form>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function handleFileUpload(input) {
            const file = input.files[0];
            const fileInfoContainer = document.getElementById('fileInfoContainer');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const selectedFileName = document.getElementById('selectedFileName');
            const selectedFileSize = document.getElementById('selectedFileSize');
            const imagePreview = document.getElementById('imagePreview');
            const uploadText = document.getElementById('uploadText');

            if (file) {
                // Display file information
                selectedFileName.textContent = file.name;
                selectedFileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                fileInfoContainer.style.display = 'block';

                // Show the image preview if the file is an image
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreviewContainer.style.display = 'none';
                }

                // Update the upload button text
                uploadText.textContent = 'Change File';
            } else {
                fileInfoContainer.style.display = 'none';
                imagePreviewContainer.style.display = 'none';
                uploadText.textContent = 'Choose File';
            }
        }

        function removeFile() {
            const traineePhotoInput = document.getElementById('traineePhoto');
            traineePhotoInput.value = '';
            handleFileUpload(traineePhotoInput);
        }
    </script>
@endpush

@section('styles')
    <style>
        .file-upload {
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
        }

        .card.border-light {
            border: 1px solid #e9ecef !important;
        }

        .file-icon i {
            font-size: 1.2rem;
        }

        .file-name {
            font-size: 0.9rem;
            color: #495057;
        }

        .file-size {
            font-size: 0.8rem;
        }
    </style>
@endsection
