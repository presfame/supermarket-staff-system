@extends('layouts.app')
@section('title', 'Process Payroll')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Process Payroll</h2>
    <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Back to Payroll</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="table-card">
            <h5 class="mb-4"><i class="fas fa-calculator me-2 text-primary"></i>Process Monthly Payroll</h5>
            <p class="text-muted mb-4">Select the pay period to process payroll for all active employees. This will calculate NSSF, SHIF, and PAYE deductions based on current statutory settings.</p>
            
            <form action="{{ url('payroll/process') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Month <span class="text-danger">*</span></label>
                        <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('month', now()->month) == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                        @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                            @for($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ old('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This will recalculate payroll for all active employees in the selected period.
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-cogs me-2"></i>Process Payroll
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="table-card">
            <h5 class="mb-4"><i class="fas fa-info-circle me-2 text-info"></i>Statutory Deductions Info</h5>
            <table class="table table-sm">
                <tr>
                    <th>NSSF</th>
                    <td>6% of gross (Tier I max: KES 1,080, Tier II max: KES 2,160)</td>
                </tr>
                <tr>
                    <th>SHIF</th>
                    <td>2.75% of gross salary</td>
                </tr>
                <tr>
                    <th>PAYE</th>
                    <td>Progressive tax bands with KES 2,400 personal relief</td>
                </tr>
            </table>
            
            <h6 class="mt-4 mb-3">PAYE Tax Bands</h6>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Income Range (KES)</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>0 - 24,000</td><td>10%</td></tr>
                    <tr><td>24,001 - 32,333</td><td>25%</td></tr>
                    <tr><td>32,334 - 500,000</td><td>30%</td></tr>
                    <tr><td>500,001 - 800,000</td><td>32.5%</td></tr>
                    <tr><td>Above 800,000</td><td>35%</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
