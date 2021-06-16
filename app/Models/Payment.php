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
        'value',
        'date',
        'validity'
    ];

    protected $casts = [
        'active' => 'boolean',
        'date' => 'datetime:Y-m-d H:i:s',
        'validity' => 'datetime:Y-m-d',
        'value' => 'float',
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
        return $this->hasOne(PaymentType::class, 'type_id');
    }
}
