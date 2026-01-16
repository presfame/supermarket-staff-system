<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand">
            Manimo Supermarket
            <small>Employee & Payroll System</small>
        </div>
    </div>
    <div class="sidebar-menu">
        <div class="menu-section">
            <p class="menu-label">MAIN</p>
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-{{ auth()->user()->role === 'employee' ? 'home' : 'th-large' }}"></i>
                <span>{{ auth()->user()->role === 'employee' ? 'Home' : 'Dashboard' }}</span>
            </a>
        </div>
        @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isHR()))
        <div class="menu-section">
            <p class="menu-label">MANAGEMENT</p>
            <a href="{{ route('employees.index') }}" class="menu-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Employees</span>
            </a>
            <a href="{{ route('departments.index') }}" class="menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <i class="fas fa-sitemap"></i><span>Departments</span>
            </a>
            <a href="{{ route('positions.index') }}" class="menu-item {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                <i class="fas fa-id-badge"></i><span>Positions</span>
            </a>
            <a href="{{ route('shifts.index') }}" class="menu-item {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i><span>Shifts</span>
            </a>
        </div>
        <div class="menu-section">
            <p class="menu-label">OPERATIONS</p>
            <a href="{{ route('attendance.index') }}" class="menu-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <i class="fas fa-fingerprint"></i><span>Attendance</span>
            </a>
            <a href="{{ route('payroll.index') }}" class="menu-item {{ request()->is('payroll*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i><span>Payroll</span>
            </a>
            <a href="{{ route('reports.index') }}" class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i><span>Reports</span>
            </a>
        </div>
        <div class="menu-section">
            <p class="menu-label">CONFIGURATION</p>
            <a href="{{ route('settings.index') }}" class="menu-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a>
        </div>
        @endif
        <div class="menu-section">
            <p class="menu-label">PERSONAL</p>
            <a href="{{ route('schedule.my') }}" class="menu-item {{ request()->routeIs('schedule.my') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i><span>My Schedule</span>
            </a>
            <a href="{{ route('payslips.my') }}" class="menu-item {{ request()->routeIs('payslips.my') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i><span>My Payslips</span>
            </a>
        </div>
    </div>
</div>
