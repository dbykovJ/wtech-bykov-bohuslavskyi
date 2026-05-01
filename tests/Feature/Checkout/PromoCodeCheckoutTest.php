<?php

namespace Tests\Feature\Checkout;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\Checkout\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PromoCodeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_promo_code_can_be_applied_for_eligible_cart_products(): void
    {
        [$user, $sale] = $this->createPromoEligibleCart();

        $response = $this
            ->actingAs($user)
            ->from('/cart')
            ->post(route('cart.promo.apply'), [
                'promo_code' => '  summer20  ',
            ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('cart.promo_code', $sale->promo_code);
    }

    public function test_promo_sales_are_not_applied_before_promo_code_entry(): void
    {
        [$user] = $this->createPromoEligibleCart();

        Config::set('services.stripe.secret', 'sk_test_123');
        Config::set('services.stripe.currency', 'usd');

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_no_promo',
                'url' => 'https://checkout.stripe.test/session/cs_test_no_promo',
            ], 200),
        ]);

        $checkoutService = app(CheckoutService::class);

        $checkoutService->createStripeSessionForUser($user, [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+421900000000',
            'street' => 'Main 1',
            'city' => 'Bratislava',
            'postal_code' => '81101',
            'country' => 'SK',
        ]);

        $order = Order::query()->latest('id')->first();

        $this->assertNotNull($order);
        $this->assertNull($order->promo_code);
        $this->assertSame(0.00, (float) $order->promo_discount_total);
        $this->assertSame(200.00, (float) $order->subtotal);
        $this->assertSame(215.00, (float) $order->total);
    }

    public function test_checkout_stores_and_uses_applied_promo_code_totals(): void
    {
        [$user, $sale] = $this->createPromoEligibleCart(withPublicSale: true);

        $this->actingAs($user)->withSession([
            'cart.promo_code' => $sale->promo_code,
        ]);

        Config::set('services.stripe.secret', 'sk_test_123');
        Config::set('services.stripe.currency', 'usd');

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.test/session/cs_test_123',
            ], 200),
        ]);

        $checkoutService = app(CheckoutService::class);

        $checkoutUrl = $checkoutService->createStripeSessionForUser($user, [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+421900000000',
            'street' => 'Main 1',
            'city' => 'Bratislava',
            'postal_code' => '81101',
            'country' => 'SK',
        ]);

        $this->assertSame('https://checkout.stripe.test/session/cs_test_123', $checkoutUrl);

        $order = Order::query()->latest('id')->first();

        $this->assertNotNull($order);
        $this->assertSame($sale->promo_code, $order->promo_code);
        $this->assertSame(200.00, (float) $order->subtotal_before_discount);
        $this->assertSame(60.00, (float) $order->sales_discount_total);
        $this->assertSame(28.00, (float) $order->promo_discount_total);
        $this->assertSame(112.00, (float) $order->subtotal);
        $this->assertSame(127.00, (float) $order->total);

        Http::assertSent(function ($request) use ($sale) {
            $payload = $request->data();

            return ($payload['metadata']['promo_code'] ?? null) === $sale->promo_code;
        });
    }

    public function test_public_sale_without_promo_code_is_always_applied(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
        ]);

        $product = Product::create([
            'name' => 'Always On Sale Hoodie',
            'description' => 'Test product',
            'price' => 100.00,
            'slug' => 'always-sale-hoodie',
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $color = Color::create([
            'name' => 'Black',
            'hex_code' => '#000000',
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size' => 'M',
            'count' => 2,
        ]);

        $publicSale = Sale::create([
            'name' => 'Spring Deals',
            'slug' => 'always-active-spring-deals',
            'discount' => 30.00,
            'valid_from' => now()->addDays(10),
            'valid_to' => now()->subDays(10),
            'promo_code' => null,
        ]);

        $publicSale->products()->attach($product->id);

        Config::set('services.stripe.secret', 'sk_test_123');
        Config::set('services.stripe.currency', 'usd');

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_123_public_sale',
                'url' => 'https://checkout.stripe.test/session/cs_test_123_public_sale',
            ], 200),
        ]);

        $checkoutService = app(CheckoutService::class);
        $checkoutService->createStripeSessionForUser($user, [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+421900000000',
            'street' => 'Main 1',
            'city' => 'Bratislava',
            'postal_code' => '81101',
            'country' => 'SK',
        ]);

        $order = Order::query()->latest('id')->first();

        $this->assertNotNull($order);
        $this->assertNull($order->promo_code);
        $this->assertSame(60.00, (float) $order->sales_discount_total);
        $this->assertSame(0.00, (float) $order->promo_discount_total);
        $this->assertSame(140.00, (float) $order->subtotal);
    }

    public function test_duplicate_promo_code_entries_are_rejected(): void
    {
        [$user, $sale] = $this->createPromoEligibleCart();

        $duplicateSale = Sale::create([
            'name' => 'Summer Sale Duplicate',
            'slug' => 'summer-sale-duplicate',
            'discount' => 15.00,
            'valid_from' => now()->subDay(),
            'valid_to' => now()->addDay(),
            'promo_code' => $sale->promo_code,
        ]);

        $duplicateSale->products()->attach(CartItem::query()->where('user_id', $user->id)->value('product_id'));

        $response = $this
            ->actingAs($user)
            ->from('/cart')
            ->post(route('cart.promo.apply'), [
                'promo_code' => $sale->promo_code,
            ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHasErrors('promo_code');
        $response->assertSessionMissing('cart.promo_code');
    }

    private function createPromoEligibleCart(bool $withPublicSale = false): array
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
        ]);

        $product = Product::create([
            'name' => 'Promo Hoodie',
            'description' => 'Test product',
            'price' => 100.00,
            'slug' => 'promo-hoodie',
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $color = Color::create([
            'name' => 'Black',
            'hex_code' => '#000000',
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size' => 'M',
            'count' => 2,
        ]);

        $promoSale = Sale::create([
            'name' => 'Summer Sale',
            'slug' => 'summer-sale',
            'discount' => 20.00,
            'valid_from' => now()->subDay(),
            'valid_to' => now()->addDay(),
            'promo_code' => 'SUMMER20',
        ]);

        $promoSale->products()->attach($product->id);

        if ($withPublicSale) {
            $publicSale = Sale::create([
                'name' => 'Spring Deals',
                'slug' => 'spring-deals',
                'discount' => 30.00,
                'valid_from' => now()->subDay(),
                'valid_to' => now()->addDay(),
                'promo_code' => null,
            ]);

            $publicSale->products()->attach($product->id);
        }

        return [$user, $promoSale];
    }
}

