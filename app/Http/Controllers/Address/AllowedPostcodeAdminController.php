<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Models\AllowedPostcode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AllowedPostcodeAdminController extends Controller
{
    public function index()
    {
        $postcodes = AllowedPostcode::orderBy('postcode_pattern', 'asc')->get();
        return view('admin.allowed_postcodes.index', compact('postcodes'));
    }

    public function create()
    {
        return view('admin.allowed_postcodes.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'postcode_pattern' => 'required|regex:/^\d{4}$/|unique:allowed_postcodes,postcode_pattern',
            ]);
            AllowedPostcode::create($request->only('postcode_pattern'));
            return redirect()->route('admin.allowed-postcodes.index')->with('success', __('messages.add_success'));
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', __('messages.add_failed'));
        }
    }

    public function edit(AllowedPostcode $allowedPostcode)
    {
        return view('admin.allowed_postcodes.edit', compact('allowedPostcode'));
    }

    public function update(Request $request, AllowedPostcode $allowedPostcode)
    {
        try {
            $request->validate([
                'postcode_pattern' => 'required|regex:/^\d{4}$/|unique:allowed_postcodes,postcode_pattern,' . $allowedPostcode->id,
            ]);
            $allowedPostcode->update($request->only('postcode_pattern'));
            return redirect()->route('admin.allowed-postcodes.index')->with('success', __('messages.update_success'));
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', __('messages.update_failed'));
        }
    }

    public function destroy(AllowedPostcode $allowedPostcode)
    {
        $allowedPostcode->delete();
        return redirect()->route('admin.allowed-postcodes.index')->with('success', __('messages.delete_success'));
    }
}