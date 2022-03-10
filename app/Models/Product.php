<?php

namespace App\Models;

use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends TenantModel
{
    use HasFactory, SoftDeletes;

    public $table = 'items';

    protected $fillable = [
        'name',
        'description',
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
        'default_value' => 'float',
        'cost' => 'float',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new ProductScope);
        parent::booted();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'category_id', 'item_id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
