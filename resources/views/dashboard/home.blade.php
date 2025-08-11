@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header" style="margin-top: 10px;">
                        <h3 class="page-title">Welcome {{ Session::get('name') }}!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Admin Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Statistics Cards --}}
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Total Users</h6>
                                <h3>{{ $totalUsers ?? 0 }}</h3>
                                <small class="text-muted">Active: {{ $activeUsers ?? 0 }}</small>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/dash-icon-01.svg" alt="Users Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Total Operations</h6>
                                <h3>{{ $totalOperations ?? 0 }}</h3>
                                <small class="text-muted">This Month: {{ $thisMonthOperations ?? 0 }}</small>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/dash-icon-02.svg" alt="Operations Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Hospitals</h6>
                                <h3>{{ $totalHospitals ?? 0 }}</h3>
                                <small class="text-muted">Active: {{ $activeHospitals ?? 0 }}</small>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/dash-icon-03.svg" alt="Hospitals Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Trainees</h6>
                                <h3>{{ $totalTrainees ?? 0 }}</h3>
                                <small class="text-muted">Supervisors: {{ $totalSupervisors ?? 0 }}</small>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/dash-icon-04.svg" alt="Trainees Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Operation Status Overview --}}
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card w-100" style="border-left: 4px solid #28a745;">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6 class="text-dark">Approved Operations</h6>
                                <h3 class="text-dark">{{ $approvedOperations ?? 0 }}</h3>
                            </div>
                            <div class="db-icon">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card w-100" style="border-left: 4px solid #ffc107;">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6 class="text-dark">Pending Operations</h6>
                                <h3 class="text-dark">{{ $pendingOperations ?? 0 }}</h3>
                            </div>
                            <div class="db-icon">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card w-100" style="border-left: 4px solid #dc3545;">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6 class="text-dark">Rejected Operations</h6>
                                <h3 class="text-dark">{{ $rejectedOperations ?? 0 }}</h3>
                            </div>
                            <div class="db-icon">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card w-100" style="border-left: 4px solid #17a2b8;">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6 class="text-dark">Total Duration</h6>
                                <h3 class="text-dark">{{ $totalDuration ?? 0 }}h</h3>
                            </div>
                            <div class="db-icon">
                                <i class="fas fa-stopwatch fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Monthly Operations Chart --}}
            <div class="col-md-12 col-lg-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="card-title">Monthly Operations Overview</h5>
                            </div>
                            <div class="col-6">
                                <ul class="chart-list-out">
                                    <li><span class="circle-blue"></span>Approved</li>
                                    <li><span class="circle-green"></span>Pending</li>
                                    <li><span class="circle-red"></span>Rejected</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="monthlyOperationsChart"></div>
                    </div>
                </div>
            </div>

            {{-- User Roles Distribution --}}
            <div class="col-md-12 col-lg-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="card-title">User Distribution</h5>
                            </div>
                            <div class="col-6">
                                <ul class="chart-list-out">
                                    <li><span class="circle-blue"></span>Trainees</li>
                                    <li><span class="circle-green"></span>Supervisors</li>
                                    <li><span class="circle-orange"></span>Admins</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="userRolesChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Recent Operations Table --}}
            <div class="col-xl-6 d-flex">
                <div class="card flex-fill student-space comman-shadow">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title">Recent Operations</h5>
                        <ul class="chart-list-out student-ellips">
                            <li class="star-menus">
                                <a href="{{ route('operations/list') }}">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-center table-borderless table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Trainee</th>
                                        <th>Procedure</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOperations ?? [] as $operation)
                                    <tr>
                                        <td class="text-nowrap">
                                            <a href="#">
                                                @if($operation->trainee && $operation->trainee->avatar)
                                                    <img class="rounded-circle" src="{{ URL::to('images/'.$operation->trainee->avatar) }}" width="25" alt="Trainee">
                                                @else
                                                    <img class="rounded-circle" src="{{ URL::to('assets/img/profiles/avatar-01.jpg') }}" width="25" alt="Trainee">
                                                @endif
                                                {{ $operation->trainee->name ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td class="text-nowrap">{{ $operation->objective->objective_code ?? 'N/A' }}</td>
                                        <td>
                                            @if($operation->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($operation->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($operation->procedure_date)->format('M d, Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No recent operations</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- System Activity Feed --}}
            <div class="col-xl-6 d-flex">
                <div class="card flex-fill comman-shadow">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title">System Activity</h5>
                        <ul class="chart-list-out student-ellips">
                            <li class="star-menus">
                                <a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="activity-groups">
                            @forelse($systemActivities ?? [] as $activity)
                            <div class="activity-awards">
                                <div class="award-boxs">
                                    @if($activity['type'] == 'user_registered')
                                        <img src="assets/img/icons/award-icon-01.svg" alt="User">
                                    @elseif($activity['type'] == 'operation_logged')
                                        <img src="assets/img/icons/award-icon-02.svg" alt="Operation">
                                    @elseif($activity['type'] == 'hospital_added')
                                        <img src="assets/img/icons/award-icon-03.svg" alt="Hospital">
                                    @else
                                        <img src="assets/img/icons/award-icon-04.svg" alt="Activity">
                                    @endif
                                </div>
                                <div class="award-list-outs">
                                    <h4>{{ $activity['title'] }}</h4>
                                    <h5>{{ $activity['description'] }}</h5>
                                </div>
                                <div class="award-time-list">
                                    <span>{{ $activity['time'] }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="activity-awards">
                                <div class="award-boxs">
                                    <img src="assets/img/icons/award-icon-01.svg" alt="Activity">
                                </div>
                                <div class="award-list-outs">
                                    <h4>No Recent Activity</h4>
                                    <h5>System activity will appear here</h5>
                                </div>
                                <div class="award-time-list">
                                    <span>-</span>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats Row --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Participation Types</h5>
                        <div class="row">
                            <div class="col-4">
                                <h6>{{ $observedOperations ?? 0 }}</h6>
                                <small class="text-muted">Observed</small>
                            </div>
                            <div class="col-4">
                                <h6>{{ $assistedOperations ?? 0 }}</h6>
                                <small class="text-muted">Assisted</small>
                            </div>
                            <div class="col-4">
                                <h6>{{ $performedOperations ?? 0 }}</h6>
                                <small class="text-muted">Performed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Hospitals by Status</h5>
                        <div class="row">
                            <div class="col-6">
                                <h6>{{ $activeHospitals ?? 0 }}</h6>
                                <small class="text-success">Active</small>
                            </div>
                            <div class="col-6">
                                <h6>{{ $inactiveHospitals ?? 0 }}</h6>
                                <small class="text-danger">Inactive</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Average Duration</h5>
                        <h3>{{ $averageOperationDuration ?? 0 }}h</h3>
                        <small class="text-muted">Per Operation</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Success Rate</h5>
                        <h3>{{ $approvalRate ?? 0 }}%</h3>
                        <small class="text-muted">Operations Approved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Monthly Operations Chart
@if(isset($monthlyOperationsData))
var monthlyOptions = {
    series: [{
        name: 'Approved',
        data: [{{ implode(',', array_column($monthlyOperationsData, 'approved')) }}]
    }, {
        name: 'Pending',
        data: [{{ implode(',', array_column($monthlyOperationsData, 'pending')) }}]
    }, {
        name: 'Rejected',
        data: [{{ implode(',', array_column($monthlyOperationsData, 'rejected')) }}]
    }],
    chart: {
        type: 'area',
        height: 350,
        stacked: false,
    },
    colors: ['#28a745', '#ffc107', '#dc3545'],
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        categories: ['{!! implode("','", array_column($monthlyOperationsData, 'month')) !!}']
    },
    legend: {
        position: 'top'
    }
};

var monthlyChart = new ApexCharts(document.querySelector("#monthlyOperationsChart"), monthlyOptions);
monthlyChart.render();
@endif

// User Roles Donut Chart
@if(isset($userRolesData))
var userRolesOptions = {
    series: [{{ implode(',', array_values($userRolesData)) }}],
    chart: {
        type: 'donut',
        height: 350
    },
    labels: ['{!! implode("','", array_keys($userRolesData)) !!}'],
    colors: ['#007bff', '#28a745', '#fd7e14'],
    legend: {
        position: 'bottom'
    }
};

var userRolesChart = new ApexCharts(document.querySelector("#userRolesChart"), userRolesOptions);
userRolesChart.render();
@endif
</script>
@endsection