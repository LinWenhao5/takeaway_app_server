<?php

namespace App\Features\Vat\Controllers;

use App\Features\Vat\Models\VatRate;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class VatAdminController extends Controller
{
    public function adminIndex()
    {
        $vatRates = VatRate::paginate(10);
        return view('vat::index', compact('vatRates'));
    }

    public function adminCreate()
    {
        return view('vat::create');
    }

    public function adminEdit(VatRate $vat)
    {
        return view('vat::edit', compact('vat'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
        ]);
        try {
            VatRate::create($request->only('name', 'rate'));
            return redirect()->route('admin.vat.index')->with('success', __('vat.success_create'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => __('vat.error_create') . ': ' . $e->getMessage()]);
        }
    }

    public function adminUpdate(Request $request, VatRate $vat)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
        ]);

        try {
            $vat->update($request->only('name', 'rate'));
            return redirect()->route('admin.vat.index')->with('success', __('vat.success_edit'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => __('vat.error_edit') . ': ' . $e->getMessage()]);
        }
    }

    public function adminDestroy(VatRate $vat)
    {
        try {
            $vat->delete();
            return redirect()->route('admin.vat.index')->with('success', __('vat.success_delete'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => __('vat.error_delete') . ': ' . $e->getMessage()]);
        }
    }
}