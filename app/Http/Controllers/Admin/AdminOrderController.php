<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderDeliveryStatusMail;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product.category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->whereHas('items.product.category', fn ($q) =>
                $q->where('name', $request->type)
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shipping_full_name', 'ilike', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'ilike', "%{$search}%"))
                  ->orWhere('id', is_numeric($search) ? (int) $search : -1);
            });
        }

        match ($request->date) {
            'oldest'     => $query->oldest(),
            'this_week'  => $query->latest()->where('created_at', '>=', now()->startOfWeek()),
            'this_month' => $query->latest()->where('created_at', '>=', now()->startOfMonth()),
            default      => $query->latest(),
        };

        if ($request->filled('min_total')) {
            $query->where('total', '>=', (float) $request->input('min_total'));
        }

        if ($request->filled('max_total')) {
            $query->where('total', '<=', (float) $request->input('max_total'));
        }

        $orders     = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->pluck('name');

        $statuses = [
            'pending_payment' => 'Очікує оплати',
            'paid'           => 'Оплачено',
            'processing'      => 'В обробці',
            'shipped'         => 'В дорозі',
            'delivered'       => 'Доставлено',
            'completed'       => 'Завершено',
            'cancelled'       => 'Скасовано',
        ];

        return view('admin.orders', compact('orders', 'categories', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.color', 'payment']);

        return view('admin.order-show', [
            'order' => $order,
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
            'delivery_carrier' => ['nullable', 'string', 'max:80'],
            'tracking_number' => [
                Rule::requiredIf(in_array($request->input('status'), ['shipped', 'delivered'], true)),
                'nullable',
                'string',
                'max:120',
            ],
        ]);

        $previousStatus = $order->status;
        $timestamps = [];

        if ($validated['status'] === 'shipped' && !$order->shipped_at) {
            $timestamps['shipped_at'] = now();
        }
        if ($validated['status'] === 'delivered' && !$order->delivered_at) {
            $timestamps['delivered_at'] = now();
        }

        $order->update([...$validated, ...$timestamps]);

        if ($previousStatus !== $order->status) {
            try {
                Mail::to($order->shipping_email)->send(
                    new OrderDeliveryStatusMail($order->loadMissing(['items.product', 'items.color']))
                );
            } catch (\Throwable $exception) {
                Log::error('Could not send delivery status email', [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'exception' => $exception,
                ]);
            }
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Замовлення оновлено.');
    }

    public function shippingLabel(Order $order)
    {
        return view('admin.shipping-label', [
            'order' => $order->load(['items.product']),
        ]);
    }

    private function statuses(): array
    {
        return [
            'pending_payment' => 'Очікує оплати',
            'paid' => 'Оплачено',
            'processing' => 'В обробці',
            'shipped' => 'В дорозі',
            'delivered' => 'Доставлено',
            'completed' => 'Завершено',
            'cancelled' => 'Скасовано',
        ];
    }
}
