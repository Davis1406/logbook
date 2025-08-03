<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Redirect to appropriate dashboard based on role */
    public function index()
    {
        $role = session()->get('role_name');

        if ($role === 'Admin' || $role === 'Super Admin') {
            return view('dashboard.home');
        } elseif ($role === 'Trainee') {
            return redirect()->route('trainee/dashboard');
        } elseif ($role === 'Supervisor') {
            return redirect()->route('supervisor/dashboard');
        }

        abort(403, 'Unauthorized access');
    }

    public function userProfile()
    {
        return view('dashboard.profile');
    }

    public function teacherDashboardIndex()
    {
        return view('dashboard.supervisor_dashboard');
    }

    public function studentDashboardIndex()
    {
        return view('dashboard.trainee_dashboard');
    }
}
