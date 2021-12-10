<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * Attributes
     */
    public function getCategoriesIdAttribute()
    {
        return $this->categories()->pluck('categories.id');
    }
}
