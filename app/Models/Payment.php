<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $with = [
        'type'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function type()
    {
        return $this->hasOne(PaymentType::class);
    }
}
