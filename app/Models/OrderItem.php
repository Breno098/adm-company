<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public $table = 'order_item';

    protected $fillable = [
        'description',
        'active',
        'quantity',
        'value'
    ];

    protected $casts = [
        'active' => 'boolean',
        'value' => 'float',
    ];

    protected $with = [
        'order',
        'item'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
