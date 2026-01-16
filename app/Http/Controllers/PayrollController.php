<?php

namespace App\Http\Controllers;

use App\Services\PayrollCalculationService;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollCalculationService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function processForm()
    {
        return view('payroll.process');
    }

    public function process(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020'
        ]);

        $result = $this->payrollService->processPayroll($request->month, $request->year);

        if ($result['success']) {
            return redirect()->route('payroll.index')
                ->with('success', "Processed {$result['processed']} employees!");
        }

        return back()->with('error', $result['message']);
    }

    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $payrolls = Payroll::with('employee.department')
            ->where('pay_period_month', $month)
            ->where('pay_period_year', $year)->get();

        return view('payroll.index', compact('payrolls', 'month', 'year'));
    }

    public function show($id)
    {
        $payroll = Payroll::with('employee.department', 'employee.position')->findOrFail($id);
        return view('payroll.show', compact('payroll'));
    }

    public function myPayslips(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->employee_id) {
            return view('payroll.my-payslips', ['payrolls' => collect()]);
        }

        $payrolls = Payroll::where('employee_id', $user->employee_id)
            ->orderBy('pay_period_year', 'desc')
            ->orderBy('pay_period_month', 'desc')
            ->get();

        return view('payroll.my-payslips', compact('payrolls'));
    }

    public function downloadPdf($id)
    {
        $payroll = Payroll::with('employee.department', 'employee.position')->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payroll.pdf', compact('payroll'));
        
        $filename = 'Payslip_' . $payroll->employee->employee_number . '_' . 
                    $payroll->pay_period_month . '_' . $payroll->pay_period_year . '.pdf';
        
        return $pdf->download($filename);
    }

    public function reverse($id)
    {
        $payroll = Payroll::findOrFail($id);
        $employeeName = $payroll->employee->full_name;
        $period = date('F Y', mktime(0, 0, 0, $payroll->pay_period_month, 1, $payroll->pay_period_year));
        
        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', "Payroll for {$employeeName} ({$period}) has been reversed/deleted.");
    }

    // Allow employees to view their OWN payslips
    public function showOwn($id)
    {
        $user = auth()->user();
        $payroll = Payroll::with('employee.department', 'employee.position')->findOrFail($id);
        
        // Check if this payroll belongs to the logged-in employee
        if ($user->employee_id !== $payroll->employee_id) {
            abort(403, 'You can only view your own payslips.');
        }
        
        return view('payroll.show', compact('payroll'));
    }

    // Allow employees to download their OWN payslips
    public function downloadOwnPdf($id)
    {
        $user = auth()->user();
        $payroll = Payroll::with('employee.department', 'employee.position')->findOrFail($id);
        
        // Check if this payroll belongs to the logged-in employee
        if ($user->employee_id !== $payroll->employee_id) {
            abort(403, 'You can only download your own payslips.');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payroll.pdf', compact('payroll'));
        
        $filename = 'Payslip_' . $payroll->employee->employee_number . '_' . 
                    $payroll->pay_period_month . '_' . $payroll->pay_period_year . '.pdf';
        
        return $pdf->download($filename);
    }
}
