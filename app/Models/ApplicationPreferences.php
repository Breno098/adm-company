<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationPreferences extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function scopeFilterByType(Builder $builder, $type)
    {
        return $builder->when($type, function (Builder $builder, $type) {
            return $builder->where('type', $type);
        });
    }
}
