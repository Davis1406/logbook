@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <h3>Edit Operation</h3>
        </div>
        <form action="{{ route('operations/update', $operation->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $operation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $operation->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $operation->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection