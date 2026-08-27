<?php

namespace Tests\Feature;

use App\Mail\OrderDeliveryStatusMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminOrderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_number_change_sends_customer_email_even_when_status_is_unchanged(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $order = Order::query()->create([
            'status' => 'shipped',
            'currency' => 'usd',
            'subtotal_before_discount' => 100,
            'subtotal' => 100,
            'total' => 100,
            'shipping_full_name' => 'Test Customer',
            'shipping_email' => 'customer@example.com',
            'shipping_phone' => '+380000000000',
            'shipping_street' => 'Test street 1',
            'shipping_city' => 'Kyiv',
            'shipping_postal_code' => '01001',
            'shipping_country' => 'UA',
            'delivery_carrier' => 'Nova Poshta',
            'tracking_number' => 'OLD123',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update', $order), [
            'status' => 'shipped',
            'delivery_carrier' => 'Nova Poshta',
            'tracking_number' => 'NEW456',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success', 'Замовлення оновлено. Email надіслано на customer@example.com.');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'tracking_number' => 'NEW456']);
        Mail::assertSent(OrderDeliveryStatusMail::class, function (OrderDeliveryStatusMail $mail): bool {
            return $mail->hasTo('customer@example.com') && $mail->order->tracking_number === 'NEW456';
        });
    }
}
