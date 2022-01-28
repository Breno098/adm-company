<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @package App\Models
 *
 * @property int $number
 * @property int $order_id
 * @property string $payment_method
 * @property string $status
 * @property Carbon $due_date
 * @property Carbon $pay_day
 * @property float $value
 *
 * @property Order $order
 *
 * @mixin Builder
 */
class Installment extends Model
{
    use HasFactory; //, SoftDeletes;

    protected $fillable = [
        'number',
        'order_id',
        'payment_method',
        'status',
        'due_date',
        'pay_day',
        'value'
    ];

    protected $dates = [
        'pay_day',
        'due_date'
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scopes
     */
    public function scopeFilterOrderId(Builder $builder, $order_id)
    {
        return $builder->when(
            $order_id,
            function (Builder $builder, $order_id) {
                return $builder->where('order_id', $order_id);
            }
        );
    }
}
