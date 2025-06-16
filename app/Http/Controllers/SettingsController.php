<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller 
{
    public function index()
    {
        return view('settings.index');
    }

    public function setLocale($locale)
    {
        if (in_array($locale, ['en', 'zh-cn'])) {
            Cookie::queue('locale', $locale, 60 * 24 * 365);
        }
        return redirect()->back();
    }

    public function setTheme($theme)
    {
        if (in_array($theme, ['light', 'dark', 'auto'])) {
            Cookie::queue('bs-theme', $theme, 60 * 24 * 365, '/');
        }
        return redirect()->back();
    }
}
