<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController, EmployeeController, DepartmentController,
    PositionController, AttendanceController, PayrollController,
    SettingsController, ShiftController, ReportController
};
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveRequestController;

Route::get('/', fn() => redirect()->route('login'));

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:admin,hr'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('positions', PositionController::class);
        
        // Shift management
        Route::resource('shifts', ShiftController::class);
        Route::get('shifts/{id}/schedule', [ShiftController::class, 'schedule'])->name('shifts.schedule');
        Route::post('shifts/{id}/add-schedule', [ShiftController::class, 'addSchedule'])->name('shifts.add-schedule');
        Route::post('shifts/{id}/remove-schedule', [ShiftController::class, 'removeSchedule'])->name('shifts.remove-schedule');

        // Payroll routes
        Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('payroll/process', [PayrollController::class, 'processForm'])->name('payroll.process.form');
        Route::post('payroll/process', [PayrollController::class, 'process'])->name('payroll.process');
        Route::get('payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
        Route::get('payroll/{id}/download', [PayrollController::class, 'downloadPdf'])->name('payroll.download');
        Route::delete('payroll/{id}/reverse', [PayrollController::class, 'reverse'])->name('payroll.reverse');
        
        // Settings routes
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Reports routes
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('reports/payroll', [ReportController::class, 'payroll'])->name('reports.payroll');
        Route::get('reports/performance', [ReportController::class, 'performance'])->name('reports.performance');

        // User management
        Route::resource('users', UserController::class);
    });

    // Attendance - accessible by admin, hr, supervisor
    Route::resource('attendance', AttendanceController::class)->middleware('role:admin,hr,supervisor');
    // Leave requests
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    
    // Self clock-in/out for all authenticated users
    Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');

    // Personal routes (available to all logged-in users)
    Route::get('my-payslips', [PayrollController::class, 'myPayslips'])->name('payslips.my');
    Route::get('my-schedule', [ShiftController::class, 'mySchedule'])->name('schedule.my');
    
    // Allow employees to view and download their own payslips
    Route::get('payroll/{id}/view', [PayrollController::class, 'showOwn'])->name('payroll.show.own');
    Route::get('payroll/{id}/download-pdf', [PayrollController::class, 'downloadOwnPdf'])->name('payroll.download.own');
    
    // API endpoints
    Route::get('api/positions/{id}/salary', [ShiftController::class, 'getPositionSalary'])->name('api.position.salary');
});
