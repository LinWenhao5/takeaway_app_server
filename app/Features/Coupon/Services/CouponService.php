<?php

namespace App\Features\Coupon\Services;

use App\Features\Coupon\Models\Coupon;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;

class CouponService
{
    public function pickup(int $customerId, int $couponId): bool
    {
        return DB::transaction(function () use ($customerId, $couponId) {
            $coupon = Coupon::lockForUpdate()->find($couponId);
            
            if (!$coupon) {
                throw new BusinessException('Coupon not found.', 'COUPON_NOT_FOUND', 404);
            }

            if (!$coupon->is_active) {
                throw new BusinessException('This coupon has been disabled.', 'COUPON_INACTIVE');
            }
            if ($coupon->pickup_start_at && $coupon->pickup_start_at->isFuture()) {
                throw new BusinessException('Coupon distribution has not started yet.', 'COUPON_PICKUP_NOT_STARTED');
            }
            if ($coupon->pickup_end_at && $coupon->pickup_end_at->isPast()) {
                throw new BusinessException('Coupon distribution has ended.', 'COUPON_PICKUP_ENDED');
            }
            if ($coupon->total_quantity !== null && $coupon->received_quantity >= $coupon->total_quantity) {
                throw new BusinessException('Out of stock. All coupons have been claimed.', 'COUPON_STOCK_EMPTY');
            }

            $alreadyReceivedCount = DB::table('coupon_customer')
                ->where('customer_id', $customerId)
                ->where('coupon_id', $couponId)
                ->count();

            if ($alreadyReceivedCount >= $coupon->per_customer_limit) {
                throw new BusinessException('You have reached the maximum claim limit for this coupon.', 'COUPON_LIMIT_EXCEEDED');
            }

            $expiresAt = $coupon->use_end_at;
            if ($coupon->valid_days) {
                $expiresAt = now()->addDays($coupon->valid_days);
            }

            $coupon->customers()->attach($customerId, [
                'received_at' => now(),
                'expires_at' => $expiresAt,
                'is_used' => false
            ]);

            $coupon->increment('received_quantity');

            return true;
        });
    }

    public function verifyAndCalculateDiscount(int $couponCustomerId, int $customerId, float $subtotal, bool $lock = false): array
    {
        $query = DB::table('coupon_customer')
            ->join('coupons', 'coupon_customer.coupon_id', '=', 'coupons.id')
            ->select(
                'coupon_customer.id', 
                'coupon_customer.coupon_id', 
                'coupons.type', 
                'coupons.value', 
                'coupons.min_order_amount', 
                'coupons.name', 
                'coupons.code'
            )
            ->where('coupon_customer.id', $couponCustomerId)
            ->where('coupon_customer.customer_id', $customerId)
            ->where('coupon_customer.is_used', false)
            ->where('coupon_customer.expires_at', '>', now());

        if ($lock) {
            $query->lockForUpdate();
        }

        $userCoupon = $query->first();

        if (!$userCoupon) {
            throw new BusinessException('The selected coupon is invalid or has expired.', 'INVALID_USER_COUPON');
        }

        if ($subtotal < $userCoupon->min_order_amount) {
            throw new BusinessException('Minimum order amount not met for this coupon.', 'COUPON_MIN_AMOUNT_NOT_REACHED');
        }

        $discount = $userCoupon->type === 'fixed'
            ? min($userCoupon->value, $subtotal)
            : round($subtotal * $userCoupon->value, 2);

        return [$userCoupon, $discount];
    }
}