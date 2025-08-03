@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Supervisors</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Supervisors</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="student-group-form">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <input type="text" class="form-control" placeholder="Search by ID ...">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <input type="text" class="form-control" placeholder="Search by Name ...">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <input type="text" class="form-control" placeholder="Search by Phone ...">
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Supervisors</h3>
                                    </div>
                                    <div class="col-auto text-end">
                                        <a href="{{ route('supervisor/grid/page') }}" class="btn btn-outline-gray me-2"><i class="feather-grid"></i></a>
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                        <a href="{{ route('supervisor/add') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="DataList" class="table table-hover datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th><input class="form-check-input" type="checkbox" value=""></th>
                                            <th>ID</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Country</th>
                                            <th>Mobile Number</th>
                                            <th>Programme</th>
                                            <th>Hospital</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listSupervisor as $list)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox" value="{{ $list->id }}">
                                                </td>
                                                <td>{{ $list->user_id }}</td>
                                                <td>
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle"
                                                             src="{{ $list->avatar ? asset('storage/' . $list->avatar) : asset('images/photo_defaults.jpg') }}"
                                                             alt="{{ $list->name }}" width="40" height="40">
                                                    </a>
                                                </td>
                                                <td>{{ $list->name }}</td>
                                                <td>{{ $list->gender }}</td>
                                                <td>{{ $list->country ?? 'N/A' }}</td>
                                                <td>{{ $list->mobile }}</td>
                                                <td>{{ $list->programme_name ?? 'N/A' }}</td>
                                                <td>{{ $list->hospital_name ?? 'N/A' }}</td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{ url('supervisor/edit/' . $list->id) }}" class="btn btn-sm bg-danger-light">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-sm bg-danger-light teacher_delete" data-bs-toggle="modal" data-bs-target="#teacherDelete" data-id="{{ $list->id }}">
                                                            <i class="feather-trash-2 me-1"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> {{-- table-responsive --}}
                        </div> {{-- card-body --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade contentmodal" id="teacherDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content doctor-profile">
                <div class="modal-header pb-0 border-bottom-0 justify-content-end">
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="feather-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('supervisor/delete') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="deleteSupervisorId">
                        <div class="delete-wrap text-center">
                            <div class="del-icon">
                                <i class="feather-x-circle"></i>
                            </div>
                            <h2>Sure you want to delete?</h2>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-success me-2">Yes</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.teacher_delete', function () {
            const id = $(this).data('id');
            $('#deleteSupervisorId').val(id);
        });
    </script>
@endsection
