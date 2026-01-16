<?php

namespace App\Http\Controllers;

use App\Models\StatutorySetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = StatutorySetting::all()->groupBy('category');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $id => $value) {
            StatutorySetting::where('id', $id)->update(['setting_value' => $value]);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
