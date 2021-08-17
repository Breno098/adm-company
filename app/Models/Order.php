<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'type',
        'amount',
        'amount_paid',
        'execution_date',
        'technical_visit',
        'discount_amount',
        'discount_percentage',
        'active',
        'warranty_days',
        'warranty_conditions',
        'installments',
        'comments'
    ];

    protected $casts = [
        'technical_visit' => 'datetime:Y-m-d H:i',
        'active' => 'boolean',
        'amount' => 'float',
        'amount_paid' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
    ];

    protected $with = [
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope('active-order', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

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
                    'items.name'
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
                'items.name'
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
                    ]);
    }

    public function getTechnicalVisitDateAttribute()
    {
        return $this->technical_visit->format('Y-m-d');
    }

    public function getTechnicalVisitHourAttribute()
    {
        return $this->technical_visit->format('H:i');
    }
}
