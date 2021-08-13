<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'brand',
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

     protected static function booted()
    {
        static::addGlobalScope('active-item', function (Builder $builder) {
            $builder->where('active', true);
        });
    }
    
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

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
        return $this->belongsTo(Status::class);
    }
}
