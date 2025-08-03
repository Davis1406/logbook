@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Edit Trainee</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('trainees/list') }}">Trainees</a></li>
                                <li class="breadcrumb-item active">Edit Trainee</li>
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
                            <form action="{{ route('trainee/update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $trainee->id }}">
                                <input type="hidden" name="image_hidden" value="{{ $trainee->upload }}">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title student-info">Personal Information</h5>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>First Name <span class="login-danger">*</span></label>
                                            <input type="text" name="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                value="{{ old('first_name', $trainee->first_name) }}">
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
                                                value="{{ old('last_name', $trainee->last_name) }}">
                                            @error('last_name')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Gender <span class="login-danger">*</span></label>
                                            <select name="gender"
                                                class="form-control select @error('gender') is-invalid @enderror">
                                                <option selected disabled>Select Gender</option>
                                                <option value="Male" {{ $trainee->gender == 'Male' ? 'selected' : '' }}>
                                                    Male</option>
                                                <option value="Female" {{ $trainee->gender == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="Others"
                                                    {{ $trainee->gender == 'Others' ? 'selected' : '' }}>Others</option>
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
                                                value="{{ old('country', $trainee->country) }}">
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
                                                value="{{ old('email', $trainee->email) }}">
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
                                                value="{{ old('phone_number', $trainee->phone_number) }}">
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
                                                value="{{ old('study_year', $trainee->study_year) }}">
                                            @error('study_year')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Programme <span class="login-danger">*</span></label>
                                            <select name="programme_id"
                                                class="form-control select @error('programme_id') is-invalid @enderror">
                                                <option selected disabled>Select Programme</option>
                                                @foreach ($programmes as $programme)
                                                    <option value="{{ $programme->id }}"
                                                        {{ $trainee->programme_id == $programme->id ? 'selected' : '' }}>
                                                        {{ $programme->programme_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('programme_id')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Hospital <span class="login-danger">*</span></label>
                                            <select name="hospital_id"
                                                class="form-control select @error('hospital_id') is-invalid @enderror">
                                                <option selected disabled>Select Hospital</option>
                                                @foreach ($hospitals as $hospital)
                                                    <option value="{{ $hospital->id }}"
                                                        {{ $trainee->hospital_id == $hospital->id ? 'selected' : '' }}>
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
                                                value="{{ old('admission_id', $trainee->admission_id) }}">
                                            @error('admission_id')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Upload --}}
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group students-up-files">
                                            <label>Upload Trainee Photo</label>
                                            <div class="uplod">
                                                <label
                                                    class="file-upload image-upbtn mb-0 @error('upload') is-invalid @enderror"
                                                    for="traineePhotoEdit">
                                                    <span id="uploadText">Choose File</span>
                                                    <input type="file" name="upload" id="traineePhotoEdit"
                                                        accept="image/*" style="display: none;"
                                                        onchange="handleFileUpload(this)">
                                                </label>
                                                @error('upload')
                                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>

                                            @if ($trainee->upload)
                                                <div class="mt-3">
                                                    <img src="{{ Storage::url('app/public/'.$trainee->upload) }}" class="img-thumbnail"
                                                        style="max-width: 120px;">
                                                </div>
                                            @endif


                                            {{-- JS preview --}}
                                            <div id="fileInfoContainer" class="mt-3" style="display: none;">
                                                <div class="card border-light">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="file-icon me-2">
                                                                <i class="fas fa-image text-primary"></i>
                                                            </div>
                                                            <div class="file-details flex-grow-1">
                                                                <div class="file-name" id="selectedFileName"></div>
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

                                            <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                                <img id="imagePreview" src="" alt="Preview"
                                                    class="img-thumbnail" style="max-width: 120px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary w-100">Update Trainee</button>
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
                uploadText.textContent = 'Choose File';
            }
        }

        function removeFile() {
            const fileInput = document.getElementById('traineePhotoEdit');
            fileInput.value = '';
            handleFileUpload(fileInput);
        }
    </script>
@endpush
