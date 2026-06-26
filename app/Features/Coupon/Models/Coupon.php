<?php

namespace App\Features\Coupon\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\Customer\Models\Customer;

class Coupon extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'value', 'min_order_amount',
        'pickup_start_at', 'pickup_end_at', 'valid_days',
        'use_start_at', 'use_end_at', 'total_quantity',
        'received_quantity', 'per_customer_limit', 'is_active'
    ];

    protected $casts = [
        'pickup_start_at' => 'datetime',
        'pickup_end_at' => 'datetime',
        'use_start_at' => 'datetime',
        'use_end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function (Coupon $coupon) {
            
            // 1. 基础领取日期规范化
            if ($coupon->pickup_start_at) {
                $coupon->pickup_start_at = \Carbon\Carbon::parse($coupon->pickup_start_at)->startOfDay();
            }
            if ($coupon->pickup_end_at) {
                $coupon->pickup_end_at = \Carbon\Carbon::parse($coupon->pickup_end_at)->endOfDay();
            }

            // 2. 核心：如果主动修改了有效天数
            if ($coupon->isDirty('valid_days')) {
                if (!empty($coupon->valid_days)) {
                    // 情况 A：填了具体天数 -> 在 start_at 基础上累加
                    $validDays = (int) $coupon->valid_days;
                    $startDate = !empty($coupon->use_start_at) 
                        ? \Carbon\Carbon::parse($coupon->use_start_at)->startOfDay() 
                        : now()->startOfDay();

                    $coupon->use_start_at = $startDate;
                    $coupon->use_end_at = $startDate->copy()->addDays($validDays)->endOfDay();
                } else {
                    // 情况 B：💡 运营主动删除了天数（留空） -> 代表无限时长，清除绝对时间限制
                    $coupon->valid_days = null;
                    $coupon->use_start_at = null;
                    $coupon->use_end_at = null;
                }
            } 
            
            // 3. 核心反向：如果没动天数，而是直接修改了绝对时间
            elseif (($coupon->isDirty('use_start_at') || $coupon->isDirty('use_end_at'))) {
                if (!empty($coupon->use_start_at) && !empty($coupon->use_end_at)) {
                    // 如果两端时间都有值 -> 反向推导天数
                    $startDate = \Carbon\Carbon::parse($coupon->use_start_at)->startOfDay();
                    $endDate = \Carbon\Carbon::parse($coupon->use_end_at)->endOfDay();

                    $coupon->use_start_at = $startDate;
                    $coupon->use_end_at = $endDate;
                    $coupon->valid_days = $startDate->diffInDays($endDate) + 1;
                } else {
                    // 如果运营在页面上把绝对时间也清空了 -> 同样视为无限时长
                    $coupon->valid_days = null;
                    $coupon->use_start_at = null;
                    $coupon->use_end_at = null;
                }
            }
        });
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'coupon_customer')
                    ->withPivot('id', 'received_at', 'expires_at', 'is_used', 'used_at', 'order_id')
                    ->withTimestamps();
    }
}