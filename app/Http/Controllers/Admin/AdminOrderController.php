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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category / type filter
        if ($request->filled('type')) {
            $query->whereHas('items.product.category', fn ($q) =>
                $q->where('name', $request->type)
            );
        }

        // Search by customer name or order ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shipping_full_name', 'ilike', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'ilike', "%{$search}%"))
                  ->orWhere('id', is_numeric($search) ? (int) $search : -1);
            });
        }

        // Date ordering / range
        match ($request->date) {
            'oldest'     => $query->oldest(),
            'this_week'  => $query->latest()->where('created_at', '>=', now()->startOfWeek()),
            'this_month' => $query->latest()->where('created_at', '>=', now()->startOfMonth()),
            default      => $query->latest(),
        };

        $orders     = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->pluck('name');

        $statuses = [
            'pending_payment' => 'Pending',
            'paid'            => 'Processing',
            'processing'      => 'Processing',
            'shipped'         => 'In Transit',
            'delivered'       => 'Delivered',
            'completed'       => 'Completed',
            'cancelled'       => 'Cancelled',
        ];

        return view('admin.orders', compact('orders', 'categories', 'statuses'));
    }
}
