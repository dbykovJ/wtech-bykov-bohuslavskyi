<?php

namespace App\Http\Controllers\Cart\Stripe;

use App\Http\Controllers\Controller;
use App\Models\StripeWebhookEvent;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, CheckoutService $checkoutService)
    {
        $payload = (string) $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$checkoutService->isStripeSignatureValid($payload, $signature)) {
            return response()->json(['message' => 'Invalid signature.'], 400);
        }

        $event = json_decode($payload, true);
        if (!is_array($event)) {
            return response()->json(['message' => 'Invalid payload.'], 400);
        }

        $eventId = Arr::get($event, 'id');
        if (!is_string($eventId) || $eventId === '') {
            return response()->json(['message' => 'Missing event id.'], 400);
        }

        $record = StripeWebhookEvent::firstOrCreate(
            ['event_id' => $eventId],
            [
                'type' => Arr::get($event, 'type'),
                'payload' => $event,
            ]
        );

        if ($record->processed_at) {
            return response()->json(['status' => 'already_processed']);
        }

        if (Arr::get($event, 'type') === 'checkout.session.completed') {
            $checkoutService->handleCheckoutSessionCompleted((array) Arr::get($event, 'data.object', []));
        }

        $record->update(['processed_at' => now()]);

        return response()->json(['status' => 'ok']);
    }
}

