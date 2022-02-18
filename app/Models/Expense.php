<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package App\Models
 *
 * @property string $title
 * @property string $description
 * @property Date $date
 * @property DateTime $time
 * @property int $quantity
 * @property float $value
 *
 * @property Category[]|Collection $categories
 * @property Installment[]|Collection $installments
 */
class Expense extends BaseModel
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
