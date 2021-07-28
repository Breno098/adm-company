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
        'discount_amount',
        'discount_percentage',
        'active',
        'warranty_days',
        'warranty_conditions',
        'installments'
    ];

    protected $casts = [
        'active' => 'boolean',
        'amount' => 'float',
        'amount_paid' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
    ];

    protected $with = [
        'status',
    ];

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
                ->ofType('product')
                ->select([
                    'item_order.id',
                    'item_order.quantity',
                    'item_order.value'
                ]);
    }

    public function services()
    {
        return $this->items()
            ->ofType('service')
            ->select([
                'item_order.id',
                'item_order.quantity',
                'item_order.value'
            ]);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
