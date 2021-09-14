<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'type',
        'amount',
        'amount_paid',
        'execution_date',
        'technical_visit_date',
        'technical_visit_time',
        'discount_amount',
        'discount_percentage',
        'warranty_days',
        'warranty_conditions',
        'installments',
        'comments'
    ];

    protected $casts = [
        'technical_visit_date' => 'date:Y-m-d',
        'technical_visit_time' => 'datetime:H:i',
        'amount' => 'float',
        'amount_paid' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
    ];

    protected $with = [
        'status',
    ];

    public function scopeFilterByStatusId(Builder $builder, $statusId)
    {
        return $builder->when($statusId, function (Builder $builder, $statusId) {
            return $builder->where('status_id', $statusId);
        });
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function products()
    {
        return $this->items()
                ->filterByType('product')
                ->select([
                    'items.id',
                    'item_order.quantity',
                    'item_order.value',
                    'items.name',
                    'items.default_value',
                    DB::raw('(item_order.quantity * item_order.value) as total_value')
                ]);
    }

    public function services()
    {
        return $this->items()
            ->filterByType('service')
            ->select([
                'items.id',
                'item_order.quantity',
                'item_order.value',
                'items.name',
                'items.default_value',
                DB::raw('(item_order.quantity * item_order.value) as total_value')
            ]);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class)
                    ->select([
                        'payments.id',
                        'payments.name',
                        'order_payment.value',
                        'order_payment.date',
                    ]);
    }

    public function formOfPayments()
    {
        return $this->belongsToMany(Payment::class, 'form_of_payment_order', 'order_id', 'payment_id');
    }
}
