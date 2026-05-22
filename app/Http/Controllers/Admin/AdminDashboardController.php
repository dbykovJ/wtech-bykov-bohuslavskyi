<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        $totalUsers  = User::count();
        $totalOrders = Order::count();
        $totalSales  = Order::whereNotIn('status', ['pending_payment', 'cancelled'])->sum('total');
        $totalPending = Order::where('status', 'pending_payment')->count();

        $salesData = Order::whereNotIn('status', ['pending_payment', 'cancelled'])
            ->whereRaw('EXTRACT(MONTH FROM created_at) = ?', [now()->month])
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [now()->year])
            ->select(DB::raw('EXTRACT(DAY FROM created_at)::int as day'), DB::raw('SUM(total) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $daysInMonth = now()->daysInMonth;
        $chartLabels = range(1, $daysInMonth);
        $chartData   = array_map(fn($d) => round((float) ($salesData[$d] ?? 0), 2), $chartLabels);

        $recentOrders = Order::with(['user', 'items.product.category'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalSales',
            'totalPending',
            'chartLabels',
            'chartData',
            'recentOrders',
        ));
    }
}
