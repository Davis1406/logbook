@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Trainees</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('trainees/list') }}">Trainee</a></li>
                            <li class="breadcrumb-item active">All Trainees</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Message --}}
        {!! Toastr::message() !!}

        <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <input type="text" class="form-control" placeholder="Search by Admission ID...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <input type="text" class="form-control" placeholder="Search by Name...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <input type="text" class="form-control" placeholder="Search by Phone...">
                </div>
                <div class="col-lg-3">
                    <button type="btn" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Trainees</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('trainees/list') }}" class="btn btn-outline-gray me-2 active"><i class="feather-list"></i></a>
                                    <a href="{{ route('trainees/grid') }}" class="btn btn-outline-gray me-2"><i class="feather-grid"></i></a>
                                    <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                    <a href="{{ route('trainee/add') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-center datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Country</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Study Year</th>
                                        <th>Programme</th>
                                        <th>Hospital</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($traineesList as $key => $trainee)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td hidden class="id">{{ $trainee->id }}</td>
                                        <td hidden class="avatar">{{ $trainee->upload }}</td>
                                        <td>
                                            <img src="{{ $trainee->image_url }}" class="avatar-img rounded-circle" width="40" height="40" alt="Photo">
                                        </td>
                                        <td>{{ $trainee->first_name }} {{ $trainee->last_name }}</td>
                                        <td>{{ $trainee->gender }}</td>
                                        <td>{{ $trainee->country }}</td>
                                        <td>{{ $trainee->email }}</td>
                                        <td>{{ $trainee->phone_number }}</td>
                                        <td>Year {{ $trainee->study_year }}</td>
                                        <td>{{ optional($trainee->programme)->programme_name ?? 'N/A' }}</td>
                                        <td>{{ optional($trainee->hospital)->hospital_name ?? 'N/A' }}</td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="{{ url('trainees/edit-trainee/'.$trainee->id) }}" class="btn btn-sm bg-success-light me-1">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                <a class="btn btn-sm bg-danger-light trainee_delete" data-bs-toggle="modal" data-bs-target="#traineeDeleteModal">
                                                    <i class="feather-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade contentmodal" id="traineeDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('trainees.delete') }}" method="POST">
            @csrf
            <div class="modal-content doctor-profile">
                <div class="modal-header pb-0 border-bottom-0 justify-content-end">
                    <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="feather-x-circle"></i></button>
                </div>
                <div class="modal-body text-center">
                    <div class="del-icon mb-2">
                        <i class="feather-x-circle"></i>
                    </div>
                    <input type="hidden" name="id" class="e_id">
                    <input type="hidden" name="avatar" class="e_avatar">
                    <h4>Are you sure you want to delete this trainee?</h4>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success me-2">Yes</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).on('click', '.trainee_delete', function() {
        var row = $(this).closest('tr');
        $('.e_id').val(row.find('.id').text());
        $('.e_avatar').val(row.find('.avatar').text());
    });
</script>
@endsection
