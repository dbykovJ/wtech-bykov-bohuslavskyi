<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product.category']);

        $query->where('status', $request->status);

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
}
