<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrdersAndReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $products = Product::limit(20)->get();

        // Create sample orders
        for ($i = 0; $i < 5; $i++) {
            // Calculate items and total first
            $itemCount = rand(1, 4);
            $subtotal = 0;
            $items = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $itemTotal = $product->price * $quantity;
                $subtotal += $itemTotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $itemTotal,
                ];
            }

            $deliveryFee = rand(5, 20);
            $total = $subtotal + $deliveryFee;

            $order = Order::create([
                'user_id' => $admin->id,
                'status' => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
                'currency' => 'US',
                'subtotal_before_discount' => $subtotal,
                'sales_discount_total' => 0,
                'promo_discount_total' => 0,
                'discount_total' => 0,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'shipping_full_name' => 'John Doe',
                'shipping_email' => 'john@example.com',
                'shipping_phone' => '+1234567890',
                'shipping_street' => '123 Main Street',
                'shipping_city' => 'New York',
                'shipping_postal_code' => '10001',
                'shipping_country' => 'USA',
                'shipping_method' => ['truck', 'post', 'drop box'][rand(0, 2)],
                'payment_method' => ['Credit Card', 'Apple Pay', 'Google Pay'][rand(0, 2)],
                'created_at' => now()->subDays(rand(1, 90)),
            ]);

            // Add order items and reviews
            foreach ($items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Add review for this item
                if (rand(0, 1)) {
                    ProductReview::create([
                        'product_id' => $item['product']->id,
                        'order_item_id' => $orderItem->id,
                        'user_id' => $admin->id,
                        'rating' => rand(3, 5),
                        'body' => $this->getRandomReview(),
                        'created_at' => now()->subDays(rand(1, 60)),
                    ]);
                }
            }
        }
    }

    private function getRandomReview(): string
    {
        $reviews = [
            'Amazing quality! Highly recommend.',
            'Great product, exactly as described.',
            'Fast shipping and great customer service.',
            'Love it! Will order again.',
            'Perfect fit and great material.',
            'Exceeded my expectations!',
            'Best purchase ever.',
            'Fantastic value for money.',
            'Very satisfied with my purchase.',
            'Excellent quality and durability.',
            'Great customer service experience.',
            'Would definitely buy again.',
            'Really impressed with the quality.',
            'Fantastic product at a great price.',
            'Shipping was quick and product arrived in perfect condition.',
        ];

        return $reviews[array_rand($reviews)];
    }
}
