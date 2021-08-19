<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'contact',
        'active',
        'main',
        'type'
    ];

    protected $casts = [
        'active' => 'boolean',
        'main' => 'boolean',
    ];

     protected static function booted()
    {
        static::addGlobalScope('active-contact', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
