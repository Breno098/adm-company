<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'type',
        'total_value',
        'execution_date',
        'discount_amount',
        'discount_percentage',
        'active',
        'warranty_days',
        'warranty_conditions'
    ];

    protected $casts = [
        'active' => 'boolean',
        'total_value' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
    ];

    protected $with = [
        'status',
    ];

    public function status()
    {
        return $this->hasOne(Status::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
