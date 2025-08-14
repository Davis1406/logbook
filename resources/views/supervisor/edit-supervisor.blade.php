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
                        <form action="{{ route('supervisor/update', $supervisor->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                {{-- Name --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Name <span class="login-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $supervisor->name) }}" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Gender --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Gender <span class="login-danger">*</span></label>
                                        <select name="gender" class="form-control form-select" required>
                                            <option value="Male" {{ old('gender', $supervisor->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender', $supervisor->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Others" {{ old('gender', $supervisor->gender) == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                        @error('gender')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Country --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Country <span class="login-danger">*</span></label>
                                        <input type="text" name="country" class="form-control" value="{{ old('country', $supervisor->country) }}" required>
                                        @error('country')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Mobile --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Mobile Number <span class="login-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $supervisor->mobile) }}" required>
                                        @error('mobile')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Programme --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Programme <span class="login-danger">*</span></label>
                                        <select name="programme_id" class="form-control form-select" required>
                                            <option value="" disabled>Select Programme</option>
                                            @foreach ($programmes as $programme)
                                                <option value="{{ $programme->id }}" {{ old('programme_id', $supervisor->programme_id) == $programme->id ? 'selected' : '' }}>
                                                    {{ $programme->programme_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('programme_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Hospital --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Hospital <span class="login-danger">*</span></label>
                                        <select name="hospital_id" class="form-control form-select" required>
                                            <option value="" disabled>Select Hospital</option>
                                            @foreach ($hospitals as $hospital)
                                                <option value="{{ $hospital->id }}" {{ old('hospital_id', $supervisor->hospital_id) == $hospital->id ? 'selected' : '' }}>
                                                    {{ $hospital->hospital_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('hospital_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Profile Photo --}}
                                <div class="col-md-4">
                                    <div class="form-group students-up-files">
                                        <label>Profile Photo</label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0" for="supervisorAvatar">
                                                <span id="uploadText">Change File</span>
                                                <input type="file" name="avatar" id="supervisorAvatar"
                                                    accept="image/*" style="display: none;"
                                                    onchange="handleFileUpload(this)">
                                            </label>
                                        </div>

                                        {{-- Current Photo Display --}}
                                        @if($supervisor->avatar)
                                            <div class="mt-2">
                                                <label class="form-label">Current Photo:</label>
                                                <div class="text-center">
                                                    <img src="{{ Storage::url('app/public/' .$supervisor->avatar) }}" 
                                                         alt="Current Photo" 
                                                         class="img-thumbnail" 
                                                         style="max-width: 120px; max-height: 120px;">
                                                </div>
                                            </div>
                                        @endif

                                        {{-- File Info --}}
                                        <div id="fileInfoContainer" class="mt-3" style="display: none;">
                                            <div class="card border-light">
                                                <div class="card-body p-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="file-icon me-2">
                                                            <i class="fas fa-image text-primary"></i>
                                                        </div>
                                                        <div class="file-details flex-grow-1">
                                                            <div class="file-name font-weight-bold" id="selectedFileName"></div>
                                                            <div class="file-size text-muted small" id="selectedFileSize"></div>
                                                        </div>
                                                        <div class="file-actions">
                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="removeFile()">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- New Image Preview --}}
                                        <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                            <label class="form-label">New Photo Preview:</label>
                                            <div class="text-center">
                                                <img id="imagePreview" src="" alt="New Preview"
                                                    class="img-thumbnail" style="max-width: 120px; max-height: 120px;">
                                            </div>
                                        </div>

                                        @error('avatar')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Login Info Section --}}
                                <div class="col-12">
                                    <h5 class="form-title mt-4"><span>Login Credentials</span></h5>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Email <span class="login-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" 
                                               value="{{ old('email', $user->email ?? '') }}" 
                                               required placeholder="Enter Email">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Password <span class="text-muted">(Leave blank to keep current password)</span></label>
                                        <input type="password" name="password" class="form-control" 
                                               placeholder="Enter New Password (optional)">
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Confirm Password --}}
                                <div class="col-md-4">
                                    <div class="form-group local-forms">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                               placeholder="Confirm New Password">
                                        @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12">
                                    <div class="student-submit text-end">
                                        <button type="submit" class="btn btn-primary">Update Supervisor</button>
                                        <a href="{{ route('supervisors/list') }}" class="btn btn-secondary ms-2">Cancel</a>
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
                selectedFileName.textContent = file.name;
                selectedFileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                fileInfoContainer.style.display = 'block';

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

                uploadText.textContent = 'Change File';
            } else {
                fileInfoContainer.style.display = 'none';
                imagePreviewContainer.style.display = 'none';
                uploadText.textContent = 'Change File';
            }
        }

        function removeFile() {
            const input = document.getElementById('supervisorAvatar');
            input.value = '';
            handleFileUpload(input);
        }
    </script>
@endpush

@push('styles')
    <style>
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

        .text-muted {
            font-size: 0.875rem;
        }
    </style>
@endpush