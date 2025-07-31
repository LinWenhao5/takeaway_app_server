<?php
namespace App\Features\Order\Controllers;

use App\Features\Order\Models\Order;
use App\Features\Order\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderAdminController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $allStatuses = collect(OrderStatus::cases())->map(fn($s) => $s->value)->toArray();

        $isSubmitted = $request->has('statuses_submitted');
        if ($isSubmitted) {
            $selectedStatuses = (array) $request->get('statuses', []);
        } else {
            $selectedStatuses = ['paid', 'delivering', 'completed'];
        }
        $statuses = $allStatuses;

        $statusMeta = [
            'unpaid' => ['label' => __('orders.unpaid'), 'color' => 'secondary', 'icon' => 'bi-hourglass'],
            'paid' => ['label' => __('orders.paid'), 'color' => 'success', 'icon' => 'bi-currency-euro'],
            'delivering' => ['label' => __('orders.delivering'), 'color' => 'info', 'icon' => 'bi-truck'],
            'completed' => ['label' => __('orders.completed'), 'color' => 'primary', 'icon' => 'bi-check-circle'],
        ];

        $date = $request->get('date', now()->toDateString());

        $allTodayOrders = Order::with(['customer'])
            ->whereDate('reserve_time', $date)
            ->orderByDesc('reserve_time')
            ->get();

        return view('order::index', compact(
            'statuses', 'statusMeta', 'allTodayOrders', 'selectedStatuses'
        ));
    }

    /**
     * Show the details of a specific order.
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'products'])->findOrFail($id);

        return view('order::show', compact('order'));
    }

    public function history(Request $request)
    {
        $historyOrders = Order::orderByDesc('created_at')->paginate(15);

        return view('order::history', compact('historyOrders'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', __('messages.update_success'));
    }


    /**
     * Delete an order.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', __('messages.delete_success'));
    }
}