@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Welcome Dr. {{ $supervisor->name ?? 'Supervisor' }}!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Supervisor Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Activities to Review</h6>
                                    <h3 class="text-warning">{{ $pendingOperations }}</h3>
                                    <small class="text-muted">Awaiting your review</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-01.svg') }}" alt="Review Icon">
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
                                    <h6>Supervised Trainees</h6>
                                    <h3 class="text-primary">{{ $uniqueTrainees }}</h3>
                                    <small class="text-muted">Active trainees</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/dash-icon-01.svg') }}" alt="Trainees Icon">
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
                                    <h6>Approved Activities</h6>
                                    <h3 class="text-success">{{ $approvedOperations }}</h3>
                                    <small class="text-muted">{{ $totalOperations > 0 ? round(($approvedOperations / $totalOperations) * 100, 1) : 0 }}% approval rate</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-02.svg') }}" alt="Approved Icon">
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
                                    <h6>This Month</h6>
                                    <h3 class="text-info">{{ $thisMonthOperations->count() }}</h3>
                                    <small class="text-muted">Activities reviewed</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-03.svg') }}" alt="Monthly Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Main Dashboard Content --}}
                <div class="col-12 col-lg-12 col-xl-8">
                    <div class="row">
                        {{-- Operations Requiring Review --}}
                        <div class="col-12 col-lg-8 col-xl-8 d-flex">
                            <div class="card flex-fill comman-shadow">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <h5 class="card-title">Activities Pending Review</h5>
                                        </div>
                                        <div class="col-6">
                                            <span class="float-end view-link">
                                                <a href="{{ route('operations/list') }}">View All Activities</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-3 pb-3">
                                    <div class="table-responsive lesson">
                                        <table class="table table-center">
                                            <tbody>
                                                @forelse($pendingReviews as $operation)
                                                <tr>
                                                    <td>
                                                        <div class="date">
                                                            <b>{{ $operation->objective->objective_code ?? 'Operation' }}</b>
                                                            <p>{{ $operation->trainee->first_name ?? 'Unknown' }} {{ $operation->trainee->last_name ?? 'Trainee' }}</p>
                                                            <ul class="teacher-date-list">
                                                                <li><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($operation->procedure_date)->format('M d, Y') }}</li>
                                                                <li>|</li>
                                                                <li><i class="fas fa-clock me-2"></i>{{ $operation->procedure_time }} ({{ $operation->duration }}min)</li>
                                                            </ul>
                                                            <small class="text-muted">{{ $operation->rotation->rotation_name ?? 'N/A' }} • {{ ucfirst($operation->participation_type) }}</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="lesson-confirm">
                                                            <a href="#" class="text-warning">Pending Review</a>
                                                        </div>
                                                        <a href="{{ route('operations/list') }}" class="btn btn-warning btn-sm">Review Now</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                                            <h6>All caught up!</h6>
                                                            <p>No operations pending your review.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Review Progress --}}
                        <div class="col-12 col-lg-4 col-xl-4 d-flex">
                            <div class="card flex-fill comman-shadow">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h5 class="card-title">Review Progress</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="dash-widget">
                                    <div class="circle-bar circle-bar1">
                                        <div class="circle-graph1" data-percent="{{ $totalOperations > 0 ? round((($approvedOperations + $rejectedOperations) / $totalOperations) * 100) : 0 }}">
                                            <div class="progress-less">
                                                <b>{{ $approvedOperations + $rejectedOperations }}/{{ $totalOperations }}</b>
                                                <p>Activities Reviewed</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Trainee Performance Overview --}}
                    <div class="row">
                        <div class="col-12 col-lg-12 col-xl-12 d-flex">
                            <div class="card flex-fill comman-shadow">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="card-title">Trainee Performance Overview</h5>
                                    <ul class="chart-list-out student-ellips">
                                        <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="teaching-card">
                                        @if($traineePerformance->count() > 0)
                                            <ul class="activity-feed">
                                                @foreach($traineePerformance as $performance)
                                                <li class="feed-item d-flex align-items-center">
                                                    <div class="dolor-activity flex-grow-1">
                                                        <span class="feed-text1">
                                                            <a href="#">{{ $performance['trainee']->first_name }} {{ $performance['trainee']->last_name }}</a>
                                                        </span>
                                                        <ul class="teacher-date-list">
                                                            <li><i class="fas fa-chart-bar me-2"></i>{{ $performance['total'] }} Activities total</li>
                                                            <li>|</li>
                                                            <li><i class="fas fa-percentage me-2"></i>{{ $performance['approval_rate'] }}% approval rate</li>
                                                        </ul>
                                                        <div class="mt-1">
                                                            <span class="badge bg-success me-1">{{ $performance['approved'] }} approved</span>
                                                            <span class="badge bg-warning me-1">{{ $performance['pending'] }} pending</span>
                                                            <span class="badge bg-danger">{{ $performance['rejected'] }} rejected</span>
                                                        </div>
                                                    </div>
                                                    <div class="activity-btns ms-auto">
                                                        @if($performance['approval_rate'] >= 80)
                                                            <button type="button" class="btn btn-sm btn-success">Excellent</button>
                                                        @elseif($performance['approval_rate'] >= 60)
                                                            <button type="button" class="btn btn-sm btn-info">Good</button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-warning">Needs Improvement</button>
                                                        @endif
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">No trainee data available</h6>
                                                <p class="text-muted">Trainee performance will appear here once operations are submitted.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Sidebar - Quick Stats & Actions --}}
                <div class="col-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-body">
                            {{-- Quick Statistics --}}
                            <div class="mb-4">
                                <h5 class="card-title mb-3">Quick Statistics</h5>
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-primary mb-1">{{ $observedOperations }}</h4>
                                            <small class="text-muted">Observed</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-info mb-1">{{ $assistedOperations }}</h4>
                                            <small class="text-muted">Assisted</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-success mb-1">{{ $performedOperations }}</h4>
                                            <small class="text-muted">Performed</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-warning mb-1">{{ $uniqueRotations }}</h4>
                                            <small class="text-muted">Rotations</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Recent Reviews --}}
                            <div class="mb-4">
                                <h5 class="card-title mb-3">Recent Reviews</h5>
                                @forelse($recentlyReviewed as $operation)
                                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $operation->objective->objective_code ?? 'Operation' }}</h6>
                                            <small class="text-muted">{{ $operation->trainee->first_name }} {{ $operation->trainee->last_name }}</small>
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($operation->procedure_date)->format('M d') }}</small>
                                        </div>
                                        <div>
                                            @if($operation->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center">No recent reviews</p>
                                @endforelse
                            </div>

                            {{-- Quick Actions --}}
                            <div>
                                <h5 class="card-title mb-3">Quick Actions</h5>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('operations/list') }}" class="btn btn-primary">
                                        <i class="fas fa-clipboard-list me-2"></i>Review Activities
                                    </a>
                                    <a href="{{ route('user/profile/page') }}" class="btn btn-secondary">
                                        <i class="fas fa-user me-2"></i>Update Profile
                                    </a>
                                    <button class="btn btn-outline-primary" onclick="printSupervisorReport()">
                                        <i class="fas fa-print me-2"></i>Generate Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Review Activity Chart
    const ctx = document.getElementById('reviewActivityChart').getContext('2d');
    const monthlyReviewData = @json($monthlyReviewData);
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyReviewData.map(item => item.month),
            datasets: [
                {
                    label: 'Total Operations',
                    data: monthlyReviewData.map(item => item.total),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Approved',
                    data: monthlyReviewData.map(item => item.approved),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Rejected',
                    data: monthlyReviewData.map(item => item.rejected),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Progress circle animation
    document.addEventListener('DOMContentLoaded', function() {
        const progressCircle = document.querySelector('.circle-graph1');
        if (progressCircle) {
            const percent = progressCircle.getAttribute('data-percent');
            // You may need to add CSS for this circular progress
            progressCircle.style.background = `conic-gradient(#007bff 0% ${percent}%, #e9ecef ${percent}% 100%)`;
        }
    });

    // Print supervisor report function
    function printSupervisorReport() {
        const printContent = `
            <div style="font-family: Arial, sans-serif; padding: 20px;">
                <h2>Supervisor Dashboard Report</h2>
                <p><strong>Supervisor:</strong> Dr. {{ $supervisor->name ?? 'Unknown' }}</p>
                <p><strong>Generated:</strong> ${new Date().toLocaleDateString(A)}</p>
                
                <h3>Summary Statistics</h3>
                <ul>
                    <li>Total Activities Supervised: {{ $totalOperations }}</li>
                    <li>Activities Approved: {{ $approvedOperations }}</li>
                    <li>Activity Pending Review: {{ $pendingOperations }}</li>
                    <li>Activities Rejected: {{ $rejectedOperations }}</li>
                    <li>Active Trainees: {{ $uniqueTrainees }}</li>
                    <li>Approval Rate: {{ $totalOperations > 0 ? round(($approvedOperations / $totalOperations) * 100, 1) : 0 }}%</li>
                </ul>
                
                <h3>Activity by Type</h3>
                <ul>
                    <li>Observed: {{ $observedOperations }}</li>
                    <li>Assisted: {{ $assistedOperations }}</li>
                    <li>Performed: {{ $performedOperations }}</li>
                </ul>
            </div>
        `;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    }
</script>

<style>
    .circle-bar1 {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .circle-graph1 {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: conic-gradient(#007bff 0% 0%, #e9ecef 0% 100%);
        position: relative;
    }
    
    .circle-graph1::before {
        content: '';
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        position: absolute;
    }
    
    .progress-less {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    
    .progress-less b {
        font-weight: bold;
        color: #333;
        font-size: 16px;
    }
    
    .progress-less p {
        margin: 0;
        font-size: 12px;
        color: #666;
    }
    
    .activity-feed .feed-item {
        padding: 15px 0;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .activity-feed .feed-item:last-child {
        border-bottom: none;
    }
    
    #reviewActivityChart {
        height: 300px !important;
    }
    
    .teacher-date-list {
        list-style: none;
        padding: 0;
        margin: 5px 0;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        font-size: 12px;
        color: #666;
    }
    
    .teacher-date-list li {
        margin: 0;
    }
    
    .badge {
        font-size: 10px;
        padding: 3px 6px;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }

    .circle-blue, .circle-green, .circle-red {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .circle-blue { background-color: #007bff; }
    .circle-green { background-color: #28a745; }
    .circle-red { background-color: #dc3545; }
</style>
@endsection