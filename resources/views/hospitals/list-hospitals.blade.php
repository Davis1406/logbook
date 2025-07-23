@extends('layouts.master')
@section('content')

{{-- Flash Messages --}}
{!! Toastr::message() !!}

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Hospitals</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Hospitals</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="student-group-form mb-3">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <input type="text" class="form-control" placeholder="Search by Name ...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <input type="text" class="form-control" placeholder="Search by Country ...">
                </div>
                <div class="col-lg-4 col-md-6">
                    <input type="text" class="form-control" placeholder="Search by Director ...">
                </div>
                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="btn" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hospital Table --}}
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Hospital List</h3>
                                </div>
                                <div class="col-auto text-end ms-auto">
                                    <a href="{{ route('hospital/add/page') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Hospital
                                    </a>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover table-center datatable table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Country</th>
                                    <th>Director</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hospitals as $hospital)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $hospital->hospital_name }}</td>
                                    <td>{{ $hospital->address }}</td>
                                    <td>{{ $hospital->country }}</td>
                                    <td>{{ $hospital->director }}</td>
                                    <td>
                                        <span class="badge bg-{{ $hospital->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($hospital->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('hospital/edit', $hospital->id) }}" class="btn btn-sm bg-danger-light me-2">
                                            <i class="feather-edit"></i>
                                        </a>
                                        <a href="{{ route('hospital/delete', $hospital->id) }}"
                                           class="btn btn-sm bg-danger"
                                           onclick="return confirm('Are you sure you want to delete this hospital?')">
                                            <i class="feather-trash-2 text-white"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hospitals found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
