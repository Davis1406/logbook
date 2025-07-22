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
                        <li><a href="{{ route('trainee/add') }}"
                                class="{{ set_active(['trainees/add/page']) }}">Add Trainee</a></li>
                        {{-- <li><a class="{{ request()->is('student/edit/*') ? 'active' : '' }}">Edit Trainees</a></li> --}}
                    </ul>
                </li>

                <li
                    class="submenu  {{ set_active(['supervisor/add', 'supervisors/list', 'teacher/grid/page', 'teacher/edit']) }} {{ request()->is('teacher/edit/*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-chalkboard-teacher"></i>
                        <span> Supervisors</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('supervisors/list') }}"
                                class="{{ set_active(['supervisors/list', 'teacher/grid/page']) }}">Supervisors List</a>
                        </li>
                        <li><a href="{{ route('supervisor/add') }}"
                                class="{{ set_active(['supervisor/add/page']) }}">Add Supervisors</a></li>
                        <li><a class="{{ request()->is('teacher/edit/*') ? 'active' : '' }}">Edit Supervisors</a>
                        </li>
                    </ul>
                </li>

                <li class="submenu {{ set_active(['department/add/page', 'department/edit/page']) }}">
                    <a href="#"><i class="fas fa-building"></i>
                        <span> Hospitals</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('department/list/page') }}"
                                class="{{ set_active(['department/list/page']) }}">Hospital List</a></li>
                        <li><a href="{{ route('department/add/page') }}"
                                class="{{ set_active(['department/add/page']) }}">Add Hospital</a></li>
                        <li><a href="{{ route('department/edit/page') }}"
                                class="{{ set_active(['department/edit/page']) }}">Edit Hospital</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-book-reader"></i>
                        <span> Subjects</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="subjects.html">Subject List</a></li>
                        <li><a href="add-subject.html">Subject Add</a></li>
                        <li><a href="edit-subject.html">Subject Edit</a></li>
                    </ul>
                </li>
                <li class="{{ set_active(['setting/page']) }}">
                    <a href="{{ route('setting/page') }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
