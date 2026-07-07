<?php

namespace App\Services\PromoCode;

use App\Models\Sale;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PromoCodeService
{
    private const SESSION_KEY = 'cart.promo_code';

    public function apply(string $promoCode, Collection $productIds): Sale
    {
        $normalized = strtoupper(trim($promoCode));

        if ($normalized === '') {
            throw ValidationException::withMessages([
                'promo_code' => 'Будь ласка, введіть промокод.',
            ]);
        }

        $sales = $this->findActiveSalesByCode($normalized);

        if ($sales->isEmpty()) {
            throw ValidationException::withMessages([
                'promo_code' => 'Промокод недійсний або термін його дії закінчився.',
            ]);
        }

        if ($sales->count() > 1) {
            throw ValidationException::withMessages([
                'promo_code' => 'Промокод дублюється в системі. Будь ласка, зверніться до підтримки.',
            ]);
        }

        /** @var Sale $sale */
        $sale = $sales->first();

        $hasEligibleProduct = $productIds->isNotEmpty() && Sale::query()
                ->where('sales.id', $sale->id)
                ->whereHas('products', fn ($q) => $q->whereIn('products.id', $productIds))
                ->exists();

        if (! $hasEligibleProduct) {
            throw ValidationException::withMessages([
                'promo_code' => 'Цей промокод не застосовується до товарів у вашому кошику.',
            ]);
        }

        session([self::SESSION_KEY => $sale->promo_code]);

        return $sale;
    }

    public function remove(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function getApplied(): ?Sale
    {
        $code = session(self::SESSION_KEY);

        if (! is_string($code) || trim($code) === '') {
            return null;
        }

        $sales = $this->findActiveSalesByCode($code);

        if ($sales->count() !== 1) {
            session()->forget(self::SESSION_KEY);
            return null;
        }

        /** @var Sale $sale */
        $sale = $sales->first();

        return $sale;
    }

    private function findActiveSalesByCode(string $code): Collection
    {
        $now = now();

        return Sale::query()
            ->whereNotNull('promo_code')
            ->whereRaw('UPPER(TRIM(promo_code)) = ?', [strtoupper(trim($code))])
            ->where('valid_from', '<=', $now)
            ->where('valid_to', '>=', $now)
            ->get();
    }
}
