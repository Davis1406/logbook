<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;
use App\Models\Trainees;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Supervisor;
use App\Models\TrainingProgramme;
use App\Models\Hospitals;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    // Also update your existing index method to call adminDashboard
    public function index()
    {
        $role = session()->get('role_name');

        if ($role === 'Admin' || $role === 'Super Admin') {
            return $this->adminDashboard();
        } elseif ($role === 'Trainee') {
            return redirect()->route('trainee/dashboard');
        } elseif ($role === 'Supervisor') {
            return redirect()->route('supervisor/dashboard');
        }

        abort(403, 'Unauthorized access');
    }

public function userProfile()
{
    $data = [];
    
    // Get current user's role from session
    $userRole = Session::get('role_name');
    
    try {
        switch ($userRole) {
            case 'Trainee':
                // Fetch trainee data with relationships
                $trainee = Trainees::with(['programme', 'hospital', 'operations'])
                    ->where('trainee_id', Session::get('user_id'))
                    ->orWhere('email', Session::get('email'))
                    ->first();
                
                if ($trainee) {
                    $data['trainee'] = $trainee;
                }
                break;
                
            case 'Supervisor':
                // Fetch supervisor data with relationships
                $supervisor = Supervisor::with(['programme', 'hospital'])
                    ->where('supervisor_id', Session::get('user_id'))
                    ->orWhere('name', Session::get('name'))
                    ->first();
                
                if ($supervisor) {
                    $data['supervisor'] = $supervisor;
                }
                break;
                
            case 'Admin':
            default:
                // For admin users, we primarily use session data
                // You can add additional admin-specific data here if needed
                $data['user'] = [
                    'name' => Session::get('name'),
                    'email' => Session::get('email'),
                    'role' => Session::get('role_name'),
                    'user_id' => Session::get('user_id'),
                    'phone_number' => Session::get('phone_number'),
                    'department' => Session::get('department'),
                    'join_date' => Session::get('join_date'),
                    'status' => Session::get('status'),
                    'position' => Session::get('position'),
                    'avatar' => Session::get('avatar'),
                ];
                break;
        }
        
    } catch (\Exception $e) {
        // Log the error and continue with empty data
        \Log::error('Error fetching user profile data: ' . $e->getMessage());
    }
    
    return view('dashboard.profile', $data);
}

    public function teacherDashboardIndex()
    {
        $userId = auth()->id();
        $supervisor = \App\Models\Supervisor::where('supervisor_id', $userId)->first();

        if (!$supervisor) {
            return redirect()->back()->with('error', 'Supervisor record not found.');
        }

        // Get operations assigned to this supervisor
        $operations = Operation::where('supervisor_id', $supervisor->id)
            ->with(['trainee', 'rotation', 'objective'])
            ->orderBy('procedure_date', 'desc')
            ->get();

        // Calculate statistics
        $totalOperations = $operations->count();
        $pendingOperations = $operations->where('status', 'pending')->count();
        $approvedOperations = $operations->where('status', 'approved')->count();
        $rejectedOperations = $operations->where('status', 'rejected')->count();

        // Get unique trainees supervised
        $uniqueTrainees = $operations->pluck('trainee_id')->unique()->count();

        // Get operations by participation type
        $observedOperations = $operations->where('participation_type', 'observed')->count();
        $assistedOperations = $operations->where('participation_type', 'assisted')->count();
        $performedOperations = $operations->where('participation_type', 'performed')->count();

        // Recent operations requiring review (last 5 pending)
        $pendingReviews = $operations->where('status', 'pending')->take(5);

        // Recently reviewed operations (last 5 approved/rejected)
        $recentlyReviewed = $operations->whereIn('status', ['approved', 'rejected'])->take(5);

        // This month's operations
        $thisMonthOperations = $operations->filter(function ($operation) {
            return Carbon::parse($operation->procedure_date)->month == now()->month &&
                Carbon::parse($operation->procedure_date)->year == now()->year;
        });

        // Monthly review data for chart (last 6 months)
        $monthlyReviewData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthOperations = $operations->filter(function ($operation) use ($month) {
                $opDate = Carbon::parse($operation->procedure_date);
                return $opDate->month == $month->month && $opDate->year == $month->year;
            });

            $monthlyReviewData[] = [
                'month' => $month->format('M Y'),
                'total' => $monthOperations->count(),
                'approved' => $monthOperations->where('status', 'approved')->count(),
                'pending' => $monthOperations->where('status', 'pending')->count(),
                'rejected' => $monthOperations->where('status', 'rejected')->count(),
            ];
        }

        // Get unique rotations and objectives supervised
        $uniqueRotations = $operations->pluck('rotation.rotation_name')->unique()->filter()->count();
        $uniqueObjectives = $operations->pluck('objective.objective_code')->unique()->filter()->count();

        // Performance metrics by trainee
        $traineePerformance = $operations->groupBy('trainee_id')->map(function ($traineeOps, $traineeId) {
            $trainee = $traineeOps->first()->trainee;
            return [
                'trainee' => $trainee,
                'total' => $traineeOps->count(),
                'approved' => $traineeOps->where('status', 'approved')->count(),
                'pending' => $traineeOps->where('status', 'pending')->count(),
                'rejected' => $traineeOps->where('status', 'rejected')->count(),
                'approval_rate' => $traineeOps->count() > 0 ? round(($traineeOps->where('status', 'approved')->count() / $traineeOps->count()) * 100, 1) : 0
            ];
        })->sortByDesc('total')->take(5);

        $dashboardData = [
            'supervisor' => $supervisor,
            'totalOperations' => $totalOperations,
            'pendingOperations' => $pendingOperations,
            'approvedOperations' => $approvedOperations,
            'rejectedOperations' => $rejectedOperations,
            'uniqueTrainees' => $uniqueTrainees,
            'observedOperations' => $observedOperations,
            'assistedOperations' => $assistedOperations,
            'performedOperations' => $performedOperations,
            'pendingReviews' => $pendingReviews,
            'recentlyReviewed' => $recentlyReviewed,
            'thisMonthOperations' => $thisMonthOperations,
            'monthlyReviewData' => $monthlyReviewData,
            'uniqueRotations' => $uniqueRotations,
            'uniqueObjectives' => $uniqueObjectives,
            'traineePerformance' => $traineePerformance,
        ];

        return view('dashboard.supervisor_dashboard', $dashboardData);
    }

    public function studentDashboardIndex()
{
    $userId = auth()->id();
    $trainee = Trainees::where('trainee_id', $userId)->first();

    if (!$trainee) {
        return redirect()->back()->with('error', 'Trainee record not found.');
    }

    // Get all operations for this trainee
    $operations = Operation::where('trainee_id', $trainee->id)
        ->with(['rotation', 'objective'])
        ->orderBy('procedure_date', 'desc')
        ->get();

    // Calculate statistics
    $totalOperations = $operations->count();
    $approvedOperations = $operations->where('status', 'approved')->count();
    $pendingOperations = $operations->where('status', 'pending')->count();
    $rejectedOperations = $operations->where('status', 'rejected')->count();

    // Calculate completion rates
    $totalDuration = $operations->sum('duration');
    $approvedDuration = $operations->where('status', 'approved')->sum('duration');

    // Get operations by participation type
    $observedOperations = $operations->where('participation_type', 'observed')->count();
    $assistedOperations = $operations->where('participation_type', 'assisted')->count();
    $performedOperations = $operations->where('participation_type', 'performed')->count();

    // Get recent operations (last 5)
    $recentOperations = $operations->take(5);

    // Get this month's operations
    $thisMonthOperations = $operations->filter(function ($operation) {
        return Carbon::parse($operation->procedure_date)->month == now()->month &&
            Carbon::parse($operation->procedure_date)->year == now()->year;
    });

    // Get upcoming operations (if any scheduled)
    $upcomingOperations = $operations->filter(function ($operation) {
        return Carbon::parse($operation->procedure_date)->isFuture();
    })->take(5);

    // Monthly activity data for chart - FIXED VERSION
    $monthlyData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $monthOperations = $operations->filter(function ($operation) use ($month) {
            $opDate = Carbon::parse($operation->procedure_date);
            return $opDate->month == $month->month && $opDate->year == $month->year;
        });

        $monthlyData[] = [
            'month' => $month->format('M Y'),
            'total' => $monthOperations->count(),
            'approved' => $monthOperations->where('status', 'approved')->count(),
            'pending' => $monthOperations->where('status', 'pending')->count(),
            'rejected' => $monthOperations->where('status', 'rejected')->count(),
        ];
    }

    // Debug: Add some sample data if no operations exist
    if (empty($monthlyData) || collect($monthlyData)->sum('total') == 0) {
        // Generate sample data for testing - remove this in production
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'total' => rand(0, 10),
                'approved' => rand(0, 8),
                'pending' => rand(0, 3),
                'rejected' => rand(0, 2),
            ];
        }
    }

    // Get unique rotations and objectives
    $uniqueRotations = $operations->pluck('rotation.rotation_name')->unique()->filter()->count();
    $uniqueObjectives = $operations->pluck('objective.objective_code')->unique()->filter()->count();

    $dashboardData = [
        'trainee' => $trainee,
        'totalOperations' => $totalOperations,
        'approvedOperations' => $approvedOperations,
        'pendingOperations' => $pendingOperations,
        'rejectedOperations' => $rejectedOperations,
        'totalDuration' => $totalDuration,
        'approvedDuration' => $approvedDuration,
        'observedOperations' => $observedOperations,
        'assistedOperations' => $assistedOperations,
        'performedOperations' => $performedOperations,
        'recentOperations' => $recentOperations,
        'thisMonthOperations' => $thisMonthOperations,
        'upcomingOperations' => $upcomingOperations,
        'monthlyData' => $monthlyData,
        'uniqueRotations' => $uniqueRotations,
        'uniqueObjectives' => $uniqueObjectives,
    ];

    return view('dashboard.trainee_dashboard', $dashboardData);
}

    public function adminDashboard()
    {
        // Get all statistics for admin dashboard

        // User Statistics
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::where('status', 'Active')->count();
        $totalTrainees = \App\Models\Trainees::count();
        $totalSupervisors = \App\Models\Supervisor::count();

        // User roles distribution for chart
        $userRolesData = [
            'Trainees' => $totalTrainees,
            'Supervisors' => $totalSupervisors,
            'Admins' => \App\Models\User::whereIn('role_name', ['Admin', 'Super Admin'])->count()
        ];

        // Hospital Statistics
        $totalHospitals = \App\Models\Hospitals::count();
        $activeHospitals = \App\Models\Hospitals::where('status', 'active')->count();
        $inactiveHospitals = \App\Models\Hospitals::where('status', 'inactive')->count();

        // Operation Statistics
        $totalOperations = \App\Models\Operation::count();
        $approvedOperations = \App\Models\Operation::where('status', 'approved')->count();
        $pendingOperations = \App\Models\Operation::where('status', 'pending')->count();
        $rejectedOperations = \App\Models\Operation::where('status', 'rejected')->count();

        // Operations this month
        $thisMonthOperations = \App\Models\Operation::whereMonth('procedure_date', now()->month)
            ->whereYear('procedure_date', now()->year)
            ->count();

        // Total duration and average
        $totalDuration = \App\Models\Operation::sum('duration');
        $averageOperationDuration = $totalOperations > 0 ? round($totalDuration / $totalOperations, 1) : 0;

        // Approval rate
        $approvalRate = $totalOperations > 0 ? round(($approvedOperations / $totalOperations) * 100, 1) : 0;

        // Participation type breakdown
        $observedOperations = \App\Models\Operation::where('participation_type', 'observed')->count();
        $assistedOperations = \App\Models\Operation::where('participation_type', 'assisted')->count();
        $performedOperations = \App\Models\Operation::where('participation_type', 'performed')->count();

        // Recent operations (last 10)
        $recentOperations = \App\Models\Operation::with(['trainee', 'objective'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Monthly operations data for chart (last 6 months)
        $monthlyOperationsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthOperations = \App\Models\Operation::whereMonth('procedure_date', $month->month)
                ->whereYear('procedure_date', $month->year);

            $monthlyOperationsData[] = [
                'month' => $month->format('M Y'),
                'total' => $monthOperations->count(),
                'approved' => $monthOperations->where('status', 'approved')->count(),
                'pending' => $monthOperations->where('status', 'pending')->count(),
                'rejected' => $monthOperations->where('status', 'rejected')->count(),
            ];
        }

        // System activities (mock data - you can implement actual activity logging)
        $systemActivities = [
            [
                'type' => 'operation_logged',
                'title' => 'New Operation Logged',
                'description' => 'Latest operation awaiting review',
                'time' => '2 hours ago'
            ],
            [
                'type' => 'user_registered',
                'title' => 'New User Registered',
                'description' => 'New trainee joined the system',
                'time' => '5 hours ago'
            ],
            [
                'type' => 'hospital_added',
                'title' => 'Hospital Added',
                'description' => 'New hospital added to the system',
                'time' => '1 day ago'
            ],
            [
                'type' => 'operation_approved',
                'title' => 'Operation Approved',
                'description' => 'Supervisor approved multiple operations',
                'time' => '2 days ago'
            ]
        ];

        // Compile all data for dashboard
        $dashboardData = [
            // User stats
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalTrainees' => $totalTrainees,
            'totalSupervisors' => $totalSupervisors,
            'userRolesData' => $userRolesData,

            // Hospital stats
            'totalHospitals' => $totalHospitals,
            'activeHospitals' => $activeHospitals,
            'inactiveHospitals' => $inactiveHospitals,

            // Operation stats
            'totalOperations' => $totalOperations,
            'thisMonthOperations' => $thisMonthOperations,
            'approvedOperations' => $approvedOperations,
            'pendingOperations' => $pendingOperations,
            'rejectedOperations' => $rejectedOperations,
            'totalDuration' => $totalDuration,
            'averageOperationDuration' => $averageOperationDuration,
            'approvalRate' => $approvalRate,

            // Participation breakdown
            'observedOperations' => $observedOperations,
            'assistedOperations' => $assistedOperations,
            'performedOperations' => $performedOperations,

            // Charts data
            'monthlyOperationsData' => $monthlyOperationsData,

            // Recent data
            'recentOperations' => $recentOperations,
            'systemActivities' => $systemActivities,
        ];

        return view('dashboard.home', $dashboardData);
    }
}
