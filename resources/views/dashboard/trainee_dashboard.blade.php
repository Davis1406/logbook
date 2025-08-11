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
                            <h3 class="page-title">Welcome {{ $trainee->first_name ?? 'Trainee' }}!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
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
                                    <h6>Total Activities</h6>
                                    <h3>{{ $totalOperations }}</h3>
                                    <small class="text-muted">{{ $totalDuration }} mins total</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-01.svg') }}" alt="Operations Icon">
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
                                    <h6>Approved</h6>
                                    <h3 class="text-success">{{ $approvedOperations }}</h3>
                                    <small class="text-muted">{{ $approvedDuration }} mins</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{URL::to('assets/img/icons/student-icon-02.svg')}}" alt="Approved Icon">
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
                                    <h6>Pending Review</h6>
                                    <h3 class="text-warning">{{ $pendingOperations }}</h3>
                                    <small class="text-muted">Awaiting approval</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{URL::to('assets/img/icons/student-icon-01.svg')}}" alt="Pending Icon">
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
                                    <small class="text-muted">{{ $thisMonthOperations->sum('duration') }} mins</small>
                                </div>
                                <div class="db-icon">
                                    <img src="{{URL::to('assets/img/icons/teacher-icon-02.svg')}}" alt="Monthly Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Training Progress Section --}}
                <div class="col-12 col-lg-12 col-xl-8">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title">Training Progress</h5>
                                </div>
                                <div class="col-6">
                                    <ul class="chart-list-out">
                                        <li>
                                            <span class="circle-blue"></span>
                                            <span class="circle-green"></span>
                                            <span class="circle-gray"></span>
                                        </li>
                                        <li class="lesson-view-all"><a href="{{ route('operations/list') }}">View All Activities</a></li>
                                        <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="dash-circle">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 dash-widget1">
                                    <div class="circle-bar circle-bar2">
                                        <div class="circle-graph2" data-percent="{{ $totalOperations > 0 ? round(($approvedOperations / $totalOperations) * 100) : 0 }}">
                                            <b>{{ $totalOperations > 0 ? round(($approvedOperations / $totalOperations) * 100) : 0 }}%</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="dash-details">
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{URL::to('assets/img/icons/lesson-icon-01.svg')}}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Rotations</h5>
                                                <h4>{{ $uniqueRotations }} Active</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{URL::to('assets/img/icons/lesson-icon-02.svg')}}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Objectives</h5>
                                                <h4>{{ $uniqueObjectives }} Types</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{URL::to('assets/img/icons/lesson-icon-03.svg')}}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Total Time</h5>
                                                <h4>{{ round($totalDuration / 60, 1) }}h</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="dash-details">
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{URL::to('assets/img/icons/lesson-icon-04.svg')}}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Observed</h5>
                                                <h4>{{ $observedOperations }}</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{URL::to('assets/img/icons/lesson-icon-05.svg')}}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Assisted</h5>
                                                <h4>{{ $assistedOperations }}</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{URL::to('assets/img/icons/lesson-icon-06.svg')}}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Performed</h5>
                                                <h4>{{ $performedOperations }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 d-flex align-items-center justify-content-center">
                                    <div class="skip-group">
                                        <a href="{{ route('operations/add') }}" class="btn btn-info continue-btn">Log Activity</a>
                                        <a href="{{ route('operations/list') }}" class="btn btn-outline-info skip-btn">View History</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Operations & Status --}}
                <div class="col-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">Recent Activities</h5>
                            <ul class="chart-list-out student-ellips">
                                <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="teaching-card">
                                @if($recentOperations->count() > 0)
                                    <ul class="activity-feed">
                                        @foreach($recentOperations as $operation)
                                            <li class="feed-item d-flex align-items-center">
                                                <div class="dolor-activity">
                                                    <span class="feed-text1">
                                                        <a href="{{ route('operation/view', $operation->id) }}">
                                                            {{ $operation->objective->objective_code ?? 'Operation' }}
                                                        </a>
                                                    </span>
                                                    <ul class="teacher-date-list">
                                                        <li><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($operation->procedure_date)->format('M d, Y') }}</li>
                                                        <li>|</li>
                                                        <li><i class="fas fa-clock me-2"></i>{{ $operation->procedure_time }} ({{ $operation->duration }}min)</li>
                                                    </ul>
                                                    <small class="text-muted">{{ $operation->rotation->rotation_name ?? 'N/A' }}</small>
                                                </div>
                                                <div class="activity-btns ms-auto">
                                                    @if($operation->status == 'approved')
                                                        <button type="button" class="btn btn-sm btn-success">Approved</button>
                                                    @elseif($operation->status == 'rejected')
                                                        <button type="button" class="btn btn-sm btn-danger">Rejected</button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-warning">Pending</button>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-center py-4">
                                        <img src="{{URL::to('assets/img/icons/lesson-icon-01.svg')}}" alt="No Operations" class="mb-3" style="opacity: 0.5;">
                                        <h6 class="text-muted">No activity logged yet</h6>
                                        <p class="text-muted">Start logging your surgical operations to track your progress.</p>
                                        <a href="{{ route('operations/add') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Log First Activity
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="row">
                <div class="col-12">
                    <div class="card comman-shadow">
                        <div class="card-header">
                            <h5 class="card-title">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('operations/add') }}" class="btn btn-primary btn-block w-100">
                                        <i class="fas fa-plus-circle me-2"></i>Log New Activities
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('operations/list') }}" class="btn btn-info btn-block w-100">
                                        <i class="fas fa-list me-2"></i>View All Activities
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('user/profile/page') }}" class="btn btn-secondary btn-block w-100">
                                        <i class="fas fa-user me-2"></i>Update Profile
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <button class="btn btn-outline-primary btn-block w-100" onclick="printReport()">
                                        <i class="fas fa-print me-2"></i>Print Report
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
{{-- ApexCharts Script --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug: Log the data to console
        console.log('Monthly Data:', @json($monthlyData));
        
        const monthlyData = @json($monthlyData);
        
        // Check if data exists and create the chart
        if (monthlyData && monthlyData.length > 0) {
            try {
                // Monthly Activity Chart using ApexCharts
                var monthlyOptions = {
                    series: [{
                        name: 'Total',
                        data: monthlyData.map(item => item.total || 0)
                    }, {
                        name: 'Approved',
                        data: monthlyData.map(item => item.approved || 0)
                    }, {
                        name: 'Pending',
                        data: monthlyData.map(item => item.pending || 0)
                    }, {
                        name: 'Rejected',
                        data: monthlyData.map(item => item.rejected || 0)
                    }],
                    chart: {
                        type: 'area',
                        height: 350,
                        stacked: false,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: true
                            }
                        },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                            animateGradually: {
                                enabled: true,
                                delay: 150
                            },
                            dynamicAnimation: {
                                enabled: true,
                                speed: 350
                            }
                        }
                    },
                    colors: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            inverseColors: false,
                            opacityFrom: 0.5,
                            opacityTo: 0,
                            stops: [0, 90, 100]
                        }
                    },
                    xaxis: {
                        categories: monthlyData.map(item => item.month),
                        labels: {
                            style: {
                                colors: '#666',
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Number of Operations'
                        },
                        labels: {
                            style: {
                                colors: '#666',
                                fontSize: '12px'
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                        floating: true,
                        offsetY: -10,
                        offsetX: 0,
                        fontSize: '12px',
                        markers: {
                            width: 10,
                            height: 10,
                            radius: 5
                        }
                    },
                    grid: {
                        borderColor: '#e0e0e0',
                        strokeDashArray: 3
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (val) {
                                return val + " operations"
                            }
                        }
                    }
                };

                var monthlyChart = new ApexCharts(document.querySelector("#monthlyActivityChart"), monthlyOptions);
                monthlyChart.render();
                
                console.log('ApexCharts rendered successfully');
                
            } catch (error) {
                console.error('Error rendering ApexCharts:', error);
            }
        } else {
            console.log('No data available for chart');
            document.querySelector("#monthlyActivityChart").innerHTML = '<div class="text-center py-5"><p class="text-muted">No data available</p></div>';
        }

        // Progress circle animation
        const progressCircle = document.querySelector('.circle-graph2');
        if (progressCircle) {
            const percent = parseInt(progressCircle.getAttribute('data-percent')) || 0;
            
            // Animate the circle
            setTimeout(() => {
                progressCircle.style.background = `conic-gradient(#007bff 0% ${percent}%, #e9ecef ${percent}% 100%)`;
                progressCircle.style.transition = 'background 1s ease-in-out';
            }, 500);
        }
    });

    // Print report function
    function printReport() {
        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Training Progress Report</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
                    .header { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
                    .stats { display: flex; justify-content: space-between; margin: 20px 0; }
                    .stat-item { text-align: center; }
                    ul { list-style-type: none; padding: 0; }
                    li { margin: 5px 0; padding: 5px; background: #f8f9fa; border-radius: 4px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Training Progress Report</h2>
                    <p><strong>Trainee:</strong> {{ $trainee->first_name }} {{ $trainee->last_name }}</p>
                    <p><strong>Generated:</strong> ${new Date().toLocaleDateString()}</p>
                </div>
                
                <h3>Summary Statistics</h3>
                <ul>
                    <li><strong>Total Operations:</strong> {{ $totalOperations }}</li>
                    <li><strong>Approved Operations:</strong> {{ $approvedOperations }}</li>
                    <li><strong>Pending Operations:</strong> {{ $pendingOperations }}</li>
                    <li><strong>Total Duration:</strong> {{ $totalDuration }} minutes ({{ round($totalDuration / 60, 1) }} hours)</li>
                    <li><strong>Observed:</strong> {{ $observedOperations }}</li>
                    <li><strong>Assisted:</strong> {{ $assistedOperations }}</li>
                    <li><strong>Performed:</strong> {{ $performedOperations }}</li>
                    <li><strong>Unique Rotations:</strong> {{ $uniqueRotations }}</li>
                    <li><strong>Unique Objectives:</strong> {{ $uniqueObjectives }}</li>
                </ul>
                
                <h3>Performance Metrics</h3>
                <p><strong>Approval Rate:</strong> {{ $totalOperations > 0 ? round(($approvedOperations / $totalOperations) * 100, 1) : 0 }}%</p>
                <p><strong>This Month Activities:</strong> {{ $thisMonthOperations->count() }}</p>
            </body>
            </html>
        `;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    }
</script>

<style>
    .circle-bar2 {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .circle-graph2 {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: conic-gradient(#007bff 0% 0%, #e9ecef 0% 100%);
        position: relative;
        transition: background 1s ease-in-out;
    }
    
    .circle-graph2::before {
        content: '';
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white;
        position: absolute;
    }
    
    .circle-graph2 b {
        position: relative;
        z-index: 1;
        font-weight: bold;
        color: #333;
        font-size: 14px;
    }
    
    .btn-block {
        padding: 12px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }
    
    .activity-feed .feed-item {
        padding: 15px 0;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .activity-feed .feed-item:last-child {
        border-bottom: none;
    }
    
    /* Chart Legend Colors */
    .circle-blue {
        width: 10px;
        height: 10px;
        background: #007bff;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .circle-green {
        width: 10px;
        height: 10px;
        background: #28a745;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .circle-yellow {
        width: 10px;
        height: 10px;
        background: #ffc107;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .circle-gray {
        width: 10px;
        height: 10px;
        background: #6c757d;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .card-chart {
        min-height: 400px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #monthlyActivityChart {
            height: 300px !important;
        }
    }
    
    /* ApexCharts custom styling */
    .apexcharts-canvas {
        margin: 0 auto;
    }
    
    .apexcharts-legend {
        justify-content: center !important;
    }
</style>
@endsection