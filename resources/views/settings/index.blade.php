@extends('layouts.app')
@section('title', 'Statutory Settings')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Statutory Settings</h2>
        <p class="text-muted mb-0">Configure NSSF, SHIF, PAYE and other statutory deduction rates</p>
    </div>
</div>

<form action="{{ route('settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        @foreach($settings as $category => $categorySettings)
        <div class="col-md-6 mb-4">
            <div class="settings-card">
                <h6><i class="fas fa-cog me-2"></i>{{ strtoupper($category) }} Settings</h6>
                
                @foreach($categorySettings as $setting)
                <div class="mb-3">
                    <label class="form-label">{{ $setting->display_name }}</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="settings[{{ $setting->id }}]" 
                               value="{{ $setting->setting_value }}" class="form-control">
                        <span class="input-group-text">
                            @if(str_contains($setting->setting_name, 'rate'))
                                %
                            @else
                                KES
                            @endif
                        </span>
                    </div>
                    @if($setting->description)
                    <small class="text-muted">{{ $setting->description }}</small>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Save Settings
        </button>
    </div>
</form>
@endsection
