<?php

namespace App\Features\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Coupon\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponAdminController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);

        return view('coupon::index', compact('coupons'));
    }

    public function create()
    {
        return view('coupon::create');
    }

    public function edit(Coupon $coupon)
    {
        return view('coupon::edit', compact('coupon'));
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->received_quantity > 0) {
        return redirect()->route('admin.coupons.index')
            ->with('error', __('coupon.disabled_due_to_usage'));
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', __('coupon.success_delete'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'in:fixed,percent'],
            'value' => [
                'required', 
                'numeric', 
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('type') === 'percent' && $value > 100) {
                        $fail(__('coupon.value_percent_error', 'Percentage discount cannot exceed 100%.'));
                    }
                }
            ],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'total_quantity' => ['nullable', 'integer', 'min:1'],
            'per_customer_limit' => ['nullable', 'integer', 'min:1'],
            'pickup_start_at' => ['nullable', 'date'],
            'pickup_end_at' => ['nullable', 'date', 'after_or_equal:pickup_start_at'],
            'use_start_at' => ['nullable', 'date'],
            'use_end_at' => ['nullable', 'date', 'after_or_equal:use_start_at'],
            'valid_days' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
        ]);

        $validated['per_customer_limit'] = $validated['per_customer_limit'] ?? 1;
        $validated['min_order_amount'] = $validated['min_order_amount'] ?? 0.00;

        Coupon::create($validated);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', __('coupon.success_create'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($coupon->id)],
            'type' => ['required', 'in:fixed,percent'],
            'value' => [
                'required', 
                'numeric', 
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('type') === 'percent' && $value > 100) {
                        $fail(__('coupon.value_percent_error'));
                    }
                }
            ],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'total_quantity' => ['nullable', 'integer', 'min:1'],
            'per_customer_limit' => ['nullable', 'integer', 'min:1'],
            'pickup_start_at' => ['nullable', 'date'],
            'pickup_end_at' => ['nullable', 'date', 'after_or_equal:pickup_start_at'],
            'use_start_at' => ['nullable', 'date'],
            'use_end_at' => ['nullable', 'date', 'after_or_equal:use_start_at'],
            'valid_days' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
        ]);

        $coupon->update($validated);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', __('coupon.success_update'));
    }
}