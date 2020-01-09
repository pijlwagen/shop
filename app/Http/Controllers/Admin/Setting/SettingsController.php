<?php


namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.settings.index', [
            'settings' => $settings
        ]);
    }
}
