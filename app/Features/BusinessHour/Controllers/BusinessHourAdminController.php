<?php

namespace App\Features\BusinessHour\Controllers;

use App\Http\Controllers\Controller;
use App\Features\BusinessHour\Models\BusinessHour;
use Illuminate\Http\Request;

class BusinessHourAdminController extends Controller
{
    public function index()
    {
        $businessHours = BusinessHour::orderBy('weekday')->get();
        return view('business_hour::index', compact('businessHours'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'is_closed' => 'boolean',
        ]);

        $hour = BusinessHour::findOrFail($id);
        $hour->update([
            'open_time' => substr($request->input('open_time'), 0, 5),
            'close_time' => substr($request->input('close_time'), 0, 5),
            'is_closed' => $request->boolean('is_closed', false),
        ]);

        return redirect()->back()->with('success', __('messages.update_success'));
    }
    public function updateTime(Request $request, $id)
    {
        $request->validate([
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
        ]);

        $hour = BusinessHour::findOrFail($id);
        $hour->update([
            'open_time' => substr($request->input('open_time'), 0, 5),
            'close_time' => substr($request->input('close_time'), 0, 5),
        ]);

        return redirect()->back()->with('success', __('messages.update_success'));
    }

    public function updateClosed(Request $request, $id)
    {
        $request->validate([
            'is_closed' => 'required|boolean',
        ]);

        $hour = BusinessHour::findOrFail($id);
        $hour->update([
            'is_closed' => $request->boolean('is_closed'),
        ]);

        return redirect()->back()->with('success', __('messages.update_success'));
    }
}