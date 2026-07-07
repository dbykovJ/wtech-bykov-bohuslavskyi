<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_item_id' => 'required|integer|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'body' => 'nullable|string|max:1000',
        ]);

        $item = OrderItem::with('order')->find($validated['order_item_id']);

        abort_if($item->order->user_id !== Auth::id(), 403);
        abort_if(!in_array($item->order->status, ['delivered', 'completed']), 403);

        if (ProductReview::where('user_id', Auth::id())
            ->where('order_item_id', $item->id)
            ->exists()) {
            return back()->withErrors(['review' => 'Ви вже залишили відгук про цей товар.']);
        }

        ProductReview::create([
            'user_id' => Auth::id(),
            'order_item_id' => $item->id,
            'product_id' => $item->product_id,
            'rating' => $validated['rating'],
            'body' => $validated['body'],
        ]);

        $this->updateProductRating($item->product_id);

        return back()->with('success', 'Відгук надіслано!');
    }

    public function destroy(ProductReview $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);

        $productId = $review->product_id;
        $review->delete();

        $this->updateProductRating($productId);

        return back()->with('success', 'Відгук видалено.');
    }

    private function updateProductRating(int $productId): void
    {
        $avgRating = ProductReview::where('product_id', $productId)->avg('rating');
        DB::table('products')
            ->where('id', $productId)
            ->update(['rating' => $avgRating ?? 0]);
    }
}
