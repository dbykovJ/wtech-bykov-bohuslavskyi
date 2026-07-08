<?php

namespace App\Services\Loyalty;

use App\Models\OrderItem;
use App\Models\User;

class LoyaltyService
{
    public const ITEMS_PER_TIER = 10;
    public const DISCOUNT_PER_TIER = 2.5;
    public const MAX_TIERS = 10;

    /**
     * Create the user's loyalty card, if they don't already have one.
     * Idempotent — purchases made before this timestamp never count.
     */
    public function createCard(User $user): void
    {
        if ($user->loyalty_card_created_at === null) {
            $user->update(['loyalty_card_created_at' => now()]);
        }
    }

    public function hasCard(User $user): bool
    {
        return $user->loyalty_card_created_at !== null;
    }

    /**
     * Total item quantity across the user's paid orders, counted only
     * from orders placed after the loyalty card was created.
     */
    public function getItemsCount(User $user): int
    {
        if (!$this->hasCard($user)) {
            return 0;
        }

        return (int) OrderItem::query()
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('status', 'paid')
                    ->where('paid_at', '>=', $user->loyalty_card_created_at);
            })
            ->sum('quantity');
    }

    public function getTier(User $user): int
    {
        return min(intdiv($this->getItemsCount($user), self::ITEMS_PER_TIER), self::MAX_TIERS);
    }

    public function getDiscountPercent(User $user): float
    {
        return $this->getTier($user) * self::DISCOUNT_PER_TIER;
    }

    /**
     * Everything the account page needs to render the card/progress bar.
     *
     * @return array{
     *     has_card: bool,
     *     items_count: int,
     *     tier: int,
     *     discount_percent: float,
     *     items_into_current_tier: int,
     *     items_until_next_tier: int,
     *     segments: array<int, float>,
     * }
     */
    public function getProgress(User $user): array
    {
        $hasCard = $this->hasCard($user);
        $itemsCount = $this->getItemsCount($user);
        $tier = min(intdiv($itemsCount, self::ITEMS_PER_TIER), self::MAX_TIERS);
        $maxedOut = $tier >= self::MAX_TIERS;

        $itemsIntoCurrentTier = $maxedOut ? self::ITEMS_PER_TIER : $itemsCount % self::ITEMS_PER_TIER;
        $itemsUntilNextTier = $maxedOut ? 0 : self::ITEMS_PER_TIER - $itemsIntoCurrentTier;

        $segments = [];
        for ($i = 1; $i <= self::MAX_TIERS; $i++) {
            if ($i <= $tier) {
                $segments[] = 100.0;
            } elseif ($i === $tier + 1 && !$maxedOut) {
                $segments[] = round(($itemsIntoCurrentTier / self::ITEMS_PER_TIER) * 100, 1);
            } else {
                $segments[] = 0.0;
            }
        }

        return [
            'has_card' => $hasCard,
            'items_count' => $itemsCount,
            'tier' => $tier,
            'discount_percent' => $tier * self::DISCOUNT_PER_TIER,
            'items_into_current_tier' => $itemsIntoCurrentTier,
            'items_until_next_tier' => $itemsUntilNextTier,
            'segments' => $segments,
        ];
    }
}
