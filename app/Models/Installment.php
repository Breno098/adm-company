<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * @package App\Models
 *
 * @property int $number
 * @property int $order_id
 * @property int $expense_id
 * @property string $payment_method
 * @property string $status
 * @property Carbon $due_date
 * @property Carbon $pay_day
 * @property float $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property Order $order
 * @property Expense $expense
 *
 * @method Installment typeOrder()
 * @method Installment typeExpense()
 * @method Installment orderId(null|int $order_id)
 * @method Installment paidAnnually(null|int $year)
 * @method Installment paidMonthly(null|int $year, null|int $month)
 * @method Installment unpaidAnnually(null|int $year)
 * @method Installment unpaidMonthly(null|int $year, null|int $month)
 * @method Installment toPendingAnnually(null|int $year)
 * @method Installment toPendingMonthly(null|int $year, null|int $month)
 *
 * @mixin Builder
 */
class Installment extends Model
{
    use HasFactory; //, SoftDeletes;

    const PAGO = 'PAGO';
    const INADIMPLENTE = 'INADIMPLENTE';
    const EM_ABERTO = 'EM ABERTO';

    protected $fillable = [
        'number',
        'order_id',
        'expense_id',
        'payment_method',
        // 'status',
        'due_date',
        'pay_day',
        'value'
    ];

    protected $casts = [
        'pay_day' => 'date:Y-m-d',
        'due_date' => 'date:Y-m-d',
        'value' => 'float'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    /**
     * Attributes
     */
    public function getStatusAttribute()
    {
        if($this->pay_day) {
            return self::PAGO;
        }

        if($this->due_date < now()->format('Y-m-d')) {
            return self::INADIMPLENTE;
        }

        return self::EM_ABERTO;
    }

    /**
     * Scopes
     */
    public function scopeTypeOrder(Builder $builder)
    {
        return $builder->whereNotNull('order_id');
    }

    public function scopeTypeExpense(Builder $builder)
    {
        return $builder->whereNotNull('expense_id');
    }

    public function scopeOrderId(Builder $builder, $order_id)
    {
        return $builder->when(
            $order_id,
            function (Builder $builder, $order_id) {
                return $builder->where('order_id', $order_id);
            }
        );
    }

    public function scopePaid(Builder $builder, $year)
    {
        return $builder->whereNotNull('pay_day');
    }

    public function scopePaidAnnually(Builder $builder, $year)
    {
        return $builder->when(
            $year,
            function (Builder $builder, $year) {
                return $builder->whereNotNull('pay_day')
                    // ->where('status', 'PAGO')
                    ->whereBetween('pay_day', [
                        Carbon::create($year)->startOfYear(),
                        Carbon::create($year)->endOfYear()
                    ]);
            }
        );
    }

    public function scopePaidMonthly(Builder $builder, $year, $month)
    {
        return $builder->when(
            $year && $month,
            function (Builder $builder) use ($year, $month){
                return $builder->whereNotNull('pay_day')
                    // ->where('status', 'PAGO')
                    ->whereBetween('pay_day', [
                        Carbon::create($year, $month)->startOfMonth(),
                        Carbon::create($year, $month)->endOfMonth()
                    ]);
            }
        );
    }

    public function scopeUnpaidAnnually(Builder $builder, $year)
    {
        return $builder->when(
            $year,
            function (Builder $builder, $year) {
                return $builder->whereNull('pay_day')
                    // ->where('status', '!=', 'PAGO')
                    ->where('due_date', '<', now()->format('Y-m-d'))
                    ->whereBetween('due_date', [
                        Carbon::create($year)->startOfYear(),
                        Carbon::create($year)->endOfYear()
                    ]);
            }
        );
    }

    public function scopeUnpaidMonthly(Builder $builder, $year, $month)
    {
        return $builder->when(
            $year && $month,
            function (Builder $builder) use ($year, $month){
                return $builder->whereNull('pay_day')
                // ->where('status', '!=', 'PAGO')
                ->where('due_date', '<', now()->format('Y-m-d'))
                ->whereBetween('due_date', [
                    Carbon::create($year, $month)->startOfMonth(),
                    Carbon::create($year, $month)->endOfMonth()
                ]);
            }
        );
    }

    public function scopeToPendingAnnually(Builder $builder, $year)
    {
        return $builder->when(
            $year,
            function (Builder $builder, $year) {
                return $builder->whereNull('pay_day')
                    // ->where('status', '!=', 'PAGO')
                    ->where('due_date', '>=', now()->format('Y-m-d'))
                    ->whereBetween('due_date', [
                        Carbon::create($year)->startOfYear(),
                        Carbon::create($year)->endOfYear()
                    ]);
            }
        );
    }

    public function scopeToPendingMonthly(Builder $builder, $year, $month)
    {
        return $builder->when(
            $year && $month,
            function (Builder $builder) use ($year, $month){
                return $builder->whereNull('pay_day')
                    // ->where('status', '!=', 'PAGO')
                    ->where('due_date', '>=', now()->format('Y-m-d'))
                    ->whereBetween('due_date', [
                        Carbon::create($year, $month)->startOfMonth(),
                        Carbon::create($year, $month)->endOfMonth()
                    ]);
            }
        );
    }
}
