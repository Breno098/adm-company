<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
        'icon'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

     protected static function booted()
    {
        static::addGlobalScope('active-category', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function scopeFilterByName(Builder $builder, $name)
    {
        return $builder->when($name, function (Builder $builder, $name) {
            return $builder->where('name', 'like', "%{$name}%");
        });
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category', 'item_id', 'category_id');
    }
}
