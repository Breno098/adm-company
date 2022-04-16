<?php

namespace App\Models;

use App\Scopes\ServiceScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package App\Models
 *
 * @property string $name
 * @property string $description
 * @property float $default_value
 * @property string $type
 * @property string $unit_measure
 * @property string $brand
 * @property float $cost
 * @property string $bar_code
 * @property int $warranty_days
 * @property string $warranty_conditions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property Category[]|Collection $categories
 * @property Image[]|Collection $images
 *
 * @method Service filterByName(null|string $name)
 */
class Service extends TenantModel
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
        static::addGlobalScope(new ServiceScope);
        parent::booted();
    }

    /**
     * Scopes
     */
    public function scopeFilterByName(Builder $builder, $name)
    {
        return $builder->when($name, function (Builder $builder, $name) {
            return $builder->where('name', 'like', "%{$name}%");
        });
    }

    /**
     * Relationships
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'item_order', 'item_id', 'order_id');
    }
}
