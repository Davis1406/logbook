<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                <li class="submenu {{ set_active(['home', 'teacher/dashboard', 'student/dashboard']) }}">
                    <a href="#"><i class="feather-grid"></i>
                        <span> Dashboard</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('home') }}" class="{{ set_active(['home']) }}">Admin Dashboard</a></li>
                        <li><a href="{{ route('teacher/dashboard') }}"
                                class="{{ set_active(['teacher/dashboard']) }}">Teacher Dashboard</a></li>
                        <li><a href="{{ route('student/dashboard') }}"
                                class="{{ set_active(['student/dashboard']) }}">Student Dashboard</a></li>
                    </ul>
                </li>
                @if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin')
                    <li
                        class="submenu 
                        {{ set_active(['list/users']) }} 
                        {{ request()->is('view/user/edit/*') ? 'active' : '' }} 
                        {{ request()->is('add/user') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-shield-alt"></i>
                            <span>User Management</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('list/users') }}"
                                    class="{{ set_active(['list/users']) }} {{ request()->is('view/user/edit/*') ? 'active' : '' }}">
                                    List Users
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li
                    class="submenu {{ set_active(['trainees/list', 'trainees/grid', 'trainees/add/page']) }} {{ request()->is('trainee/edit/*') ? 'active' : '' }} {{ request()->is('trainee/profile/*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-graduation-cap"></i>
                        <span> Trainees</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('trainees/list') }}"
                                class="{{ set_active(['trainees/list', 'trainees/grid']) }}">Trainees List</a></li>
                        <li><a href="{{ route('trainee/add') }}" class="{{ set_active(['trainees/add/page']) }}">Add
                                Trainee</a></li>
                        {{-- <li><a class="{{ request()->is('student/edit/*') ? 'active' : '' }}">Edit Trainees</a></li> --}}
                    </ul>
                </li>

                <li
                    class="submenu  {{ set_active(['supervisor/add', 'supervisors/list', 'supervisor/grid/page', 'supervisor/edit']) }} {{ request()->is('supervisor/edit/*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-chalkboard-teacher"></i>
                        <span> Supervisors</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('supervisors/list') }}"
                                class="{{ set_active(['supervisors/list', 'supervisor/grid/page']) }}">Supervisors
                                List</a>
                        </li>
                        <li><a href="{{ route('supervisor/add') }}"
                                class="{{ set_active(['supervisor/add/page']) }}">Add Supervisors</a></li>
                        <li><a class="{{ request()->is('supervisor/edit/*') ? 'active' : '' }}">Edit Supervisors</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="submenu {{ set_active(['hospital/add/page', 'hospital/list/page', 'hospital/edit/page']) }}">
                    <a href="#"><i class="fas fa-building"></i>
                        <span> Hospitals</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('hospital/list/page') }}"
                                class="{{ set_active(['hospital/list/page']) }}">Hospital List</a></li>
                        <li><a href="{{ route('hospital/add/page') }}"
                                class="{{ set_active(['hospital/add/page']) }}">Add Hospital</a></li>
                    </ul>
                </li>
                <li
                    class="submenu {{ set_active(['operations/list', 'operations/create', 'operations/edit/*', 'operations/view/*']) }}">
                    <a href="#"><i class="fas fa-tasks"></i>
                        <span> Trainee Operations</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('operations/list') }}" class="{{ set_active(['operations/list']) }}">
                                Operations
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="submenu {{ set_active(['training-programmes/list', 'training-programmes/add', 'training-programmes/edit/*', 'rotations/list', 'objectives/list']) }}">
                    <a href="#"><i class="fas fa-cogs"></i>
                        <span> Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('training-programmes.list') }}"
                                class="{{ set_active(['training-programmes/list', 'training-programmes/add', 'training-programmes/edit/*']) }}">
                                <i class="fas fa-book-open me-1"></i> Training Programmes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rotations.list') }}" class="{{ set_active(['rotations/list']) }}">
                                <i class="fas fa-sync-alt me-1"></i> Rotations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('objectives.list') }}" class="{{ set_active(['objectives/list']) }}">
                                <i class="fas fa-bullseye me-1"></i> Objectives
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
