<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @package App\Models
 *
 * @property string $title
 * @property string $description
 * @property Carbon $date
 * @property Carbon $time
 * @property int $quantity
 * @property float $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read array $categories_id
 *
 * @property Category[]|Collection $categories
 * @property Installment[]|Collection $installments
 */
class Expense extends TenantModel
{
     use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'quantity',
        'value',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'datetime:H:i',
        'value' => 'float',
    ];

    protected $appends = [
        'categories_id'
    ];

    /**
     * Relationships
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * Attributes
     */
    public function getCategoriesIdAttribute()
    {
        return $this->categories()->pluck('categories.id');
    }
}
