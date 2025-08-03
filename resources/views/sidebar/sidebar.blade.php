<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"><span>Main Menu</span></li>

                {{-- DASHBOARDS --}}
                <li class="submenu {{ set_active(['home', 'supervisor/dashboard', 'trainee/dashboard']) }}">
                    <a href="#"><i class="feather-grid"></i>
                        <span> Dashboard</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin')
                            <li><a href="{{ route('home') }}" class="{{ set_active(['home']) }}">Admin Dashboard</a>
                            </li>
                        @endif
                        @if (Session::get('role_name') === 'Supervisor')
                            <li><a href="{{ route('supervisor/dashboard') }}"
                                    class="{{ set_active(['supervisor/dashboard']) }}">Supervisor Dashboard</a></li>
                        @endif
                        @if (Session::get('role_name') === 'Trainee')
                            <li><a href="{{ route('trainee/dashboard') }}"
                                    class="{{ set_active(['trainee/dashboard']) }}">Trainee Dashboard</a></li>
                        @endif
                    </ul>
                </li>

                {{-- USER MANAGEMENT --}}
                @if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin')
                    <li class="submenu {{ set_active(['list/users']) }}">
                        <a href="#"><i class="fas fa-shield-alt"></i>
                            <span>User Management</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('list/users') }}" class="{{ set_active(['list/users']) }}">List
                                    Users</a></li>
                        </ul>
                    </li>
                @endif

                {{-- TRAINEES --}}
                @if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin')
                    <li
                        class="submenu {{ set_active(['trainees/list', 'trainees/add/page']) }} {{ request()->is('trainees/edit-trainee/*') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-graduation-cap"></i><span> Trainees</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('trainees/list') }}"
                                    class="{{ set_active(['trainees/list']) }}">Trainees List</a></li>
                            <li><a href="{{ route('trainee/add') }}"
                                    class="{{ set_active(['trainees/add/page']) }}">Add Trainee</a></li>
                        </ul>
                    </li>
                @endif

                {{-- SUPERVISORS --}}
                @if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin')
                    <li
                        class="submenu {{ set_active(['supervisors/list', 'supervisor/add/page']) }} {{ request()->is('supervisor/edit/*') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-chalkboard-teacher"></i><span> Supervisors</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('supervisors/list') }}"
                                    class="{{ set_active(['supervisors/list']) }}">Supervisors List</a></li>
                            <li><a href="{{ route('supervisor/add') }}"
                                    class="{{ set_active(['supervisor/add/page']) }}">Add Supervisor</a></li>
                        </ul>
                    </li>
                @endif

                {{-- HOSPITALS --}}
                @if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin')
                    <li class="submenu {{ set_active(['hospital/add/page', 'hospital/list/page']) }}">
                        <a href="#"><i class="fas fa-building"></i><span> Hospitals</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('hospital/list/page') }}"
                                    class="{{ set_active(['hospital/list/page']) }}">Hospital List</a></li>
                            <li><a href="{{ route('hospital/add/page') }}"
                                    class="{{ set_active(['hospital/add/page']) }}">Add Hospital</a></li>
                        </ul>
                    </li>
                @endif

                {{-- OPERATIONS (Visible to all roles, restricted in controller) --}}
                @if (in_array(Session::get('role_name'), ['Admin', 'Super Admin', 'Supervisor', 'Trainee']))
                    <li class="submenu {{ set_active(['operations/list','operations/add','operation/view/*']) }}">
                        <a href="#"><i class="fas fa-tasks"></i><span> Trainee Operations</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('operations/list') }}"
                                    class="{{ set_active(['operations/list','operations/add','operation/view/*' ]) }}">Operations</a></li>
                        </ul>
                    </li>
                @endif


                {{-- SETTINGS --}}
                @if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin')
                    <li
                        class="submenu {{ set_active(['training-programmes/list', 'rotations/list', 'objectives/list']) }}">
                        <a href="#"><i class="fas fa-cogs"></i><span> Settings</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('training-programmes.list') }}"
                                    class="{{ set_active(['training-programmes/list']) }}">Training Programmes</a></li>
                            <li><a href="{{ route('rotations.list') }}"
                                    class="{{ set_active(['rotations/list']) }}">Rotations</a></li>
                            <li><a href="{{ route('objectives.list') }}"
                                    class="{{ set_active(['objectives/list']) }}">Objectives</a></li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
