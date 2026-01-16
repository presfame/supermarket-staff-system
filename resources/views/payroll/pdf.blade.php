<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->employee->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .container { padding: 25px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #1e40af; }
        .header h1 { color: #1e40af; font-size: 22px; margin-bottom: 3px; }
        .header p { color: #666; font-size: 10px; }
        .payslip-title { background: #1e40af; color: white; text-align: center; padding: 8px; font-size: 14px; font-weight: bold; margin-bottom: 15px; }
        .info-row { display: table; width: 100%; margin-bottom: 15px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-col p { margin-bottom: 4px; font-size: 10px; }
        .section-title { background: #f1f5f9; padding: 6px 10px; font-weight: bold; margin-bottom: 8px; border-left: 3px solid #1e40af; font-size: 11px; }
        .earnings-title { border-left-color: #10b981; }
        .deductions-title { border-left-color: #ef4444; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        table tr:last-child td { border-bottom: none; }
        .text-right { text-align: right; }
        .total-row { background: #f8fafc; font-weight: bold; }
        .days-summary { display: table; width: 100%; margin-bottom: 15px; }
        .days-box { display: table-cell; width: 33.33%; text-align: center; padding: 8px; background: #f8fafc; }
        .days-box .label { font-size: 9px; color: #666; }
        .days-box .value { font-size: 16px; font-weight: bold; }
        .net-salary { background: #1e40af; color: white; padding: 12px 15px; margin: 15px 0; }
        .net-salary table { margin: 0; }
        .net-salary td { border: none; color: white; padding: 3px 0; }
        .net-salary .amount { font-size: 18px; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #666; }
        .two-col { display: table; width: 100%; }
        .col { display: table-cell; width: 50%; padding-right: 10px; vertical-align: top; }
        .col:last-child { padding-right: 0; padding-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MANIMO SUPERMARKET</h1>
            <p>Employee Management & Payroll System</p>
            <p>P.O Box 12345, Nairobi, Kenya | Tel: +254 700 000 000</p>
        </div>

        <div class="payslip-title">
            PAYSLIP - {{ strtoupper(date('F Y', mktime(0, 0, 0, $payroll->pay_period_month, 1, $payroll->pay_period_year))) }}
        </div>

        <div class="info-row">
            <div class="info-col">
                <p><strong>Employee Name:</strong> {{ $payroll->employee->full_name }}</p>
                <p><strong>Employee No:</strong> {{ $payroll->employee->employee_number }}</p>
                <p><strong>ID Number:</strong> {{ $payroll->employee->id_number }}</p>
                <p><strong>Department:</strong> {{ $payroll->employee->department->name ?? 'N/A' }}</p>
                <p><strong>Position:</strong> {{ $payroll->employee->position->title ?? 'N/A' }}</p>
            </div>
            <div class="info-col">
                <p><strong>Pay Period:</strong> {{ date('F Y', mktime(0, 0, 0, $payroll->pay_period_month, 1, $payroll->pay_period_year)) }}</p>
                <p><strong>Payment Status:</strong> {{ $payroll->status }}</p>
                <p><strong>KRA PIN:</strong> {{ $payroll->employee->kra_pin ?? 'N/A' }}</p>
                <p><strong>NSSF No:</strong> {{ $payroll->employee->nssf_number ?? 'N/A' }}</p>
                <p><strong>NHIF No:</strong> {{ $payroll->employee->nhif_number ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="days-summary">
            <div class="days-box">
                <div class="label">Days Worked</div>
                <div class="value" style="color: #10b981;">{{ $payroll->days_worked ?? 0 }}</div>
            </div>
            <div class="days-box">
                <div class="label">Days Absent</div>
                <div class="value" style="color: #ef4444;">{{ $payroll->days_absent ?? 0 }}</div>
            </div>
            <div class="days-box">
                <div class="label">Days Leave</div>
                <div class="value" style="color: #3b82f6;">{{ $payroll->days_leave ?? 0 }}</div>
            </div>
        </div>

        <div class="two-col">
            <div class="col">
                <div class="section-title earnings-title">EARNINGS</div>
                <table>
                    <tr>
                        <td>Basic Salary</td>
                        <td class="text-right">KES {{ number_format($payroll->basic_salary, 2) }}</td>
                    </tr>
                    @if($payroll->overtime_pay > 0)
                    <tr>
                        <td>Overtime ({{ $payroll->overtime_hours }} hrs)</td>
                        <td class="text-right">KES {{ number_format($payroll->overtime_pay, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td><strong>Gross Salary</strong></td>
                        <td class="text-right"><strong>KES {{ number_format($payroll->gross_salary, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="col">
                <div class="section-title deductions-title">STATUTORY DEDUCTIONS</div>
                <table>
                    <tr>
                        <td>NSSF</td>
                        <td class="text-right">KES {{ number_format($payroll->nssf_deduction, 2) }}</td>
                    </tr>
                    <tr>
                        <td>SHIF (Health Insurance)</td>
                        <td class="text-right">KES {{ number_format($payroll->shif_deduction, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Housing Levy</td>
                        <td class="text-right">KES {{ number_format($payroll->housing_levy ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>PAYE (Income Tax)</td>
                        <td class="text-right">KES {{ number_format($payroll->paye_deduction, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total Deductions</strong></td>
                        <td class="text-right"><strong>KES {{ number_format($payroll->total_deductions, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="net-salary">
            <table>
                <tr>
                    <td><strong>NET SALARY PAYABLE</strong></td>
                    <td class="text-right amount">KES {{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 15px; font-size: 10px;">
            <p><strong>Bank Details:</strong></p>
            <p>Bank: {{ $payroll->employee->bank_name ?? 'N/A' }} | Account: {{ $payroll->employee->bank_account ?? 'N/A' }} | Branch: {{ $payroll->employee->bank_branch ?? 'N/A' }}</p>
        </div>

        <div class="footer">
            <p>This is a computer-generated payslip and does not require a signature.</p>
            <p>Generated on {{ now()->format('d M Y, h:i A') }} | MANIMO SUPERMARKET - Employee Management System</p>
            <p style="margin-top: 8px; font-style: italic; border-top: 1px dashed #ccc; padding-top: 8px;">
                FINAL YEAR PROJECT | Student: PRESLEY OLENDO | Reg: 22/05796 | Supervisor: GLADYS MANGE | Course: BBIT | Unit: BBIT 04105
            </p>
        </div>
    </div>
</body>
</html>
