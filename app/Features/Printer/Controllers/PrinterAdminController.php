<?php
namespace App\Features\Printer\Controllers;
use App\Http\Controllers\Controller;
use App\Features\Printer\Models\Printer;
use Illuminate\Http\Request;    

class PrinterAdminController extends Controller
{
    public function index()
    {
        $printers = Printer::latest()->paginate(10);
        return view('printer::index', compact('printers'));
    }

    public function create()
    {
        return view('printer::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mac_address' => 'required|string|max:255|unique:printers,mac_address',
            'is_online' => 'nullable|boolean',
        ]);

        $validated['is_online'] = $request->has('is_online');

        Printer::create($validated);

        return redirect()->route('admin.printers.index')
            ->with('success', __('printer.created_success'));
    }

    public function edit(Printer $printer)
    {
        return view('printer::edit', compact('printer'));
    }

    public function update(Request $request, Printer $printer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mac_address' => 'required|string|max:255|unique:printers,mac_address,' . $printer->id,
            'is_online' => 'nullable|boolean',
        ]);

        $validated['is_online'] = $request->has('is_online');

        $printer->update($validated);

        return redirect()->route('admin.printers.index')
            ->with('success', __('printer.updated_success'));
    }

    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect()->route('admin.printers.index')
            ->with('success', __('printer.deleted_success'));
    }
}