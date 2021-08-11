<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
        'path'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category', 'item_id', 'category_id');
    }
}
