<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
        'default_value',
        'type',
        'unit_measure',
        'brad',
        'cost',
        'bar_code',
        'warranty_days',
        'warranty_conditions'
    ];

    protected $casts = [
        'active' => 'boolean',
        'default_value' => 'float',
        'cost' => 'float',
    ];

    public function order()
    {
        return $this->belongsToMany(OrderItem::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category', 'item_id', 'category_id');
    }

    public function images()
    {
        return $this->belongsToMany(Category::class, 'item_category', 'item_id', 'category_id');
    }

    public function status()
    {
        return $this->hasOne(Status::class);
    }
}
