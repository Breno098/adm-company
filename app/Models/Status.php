<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
        'type',
        'color'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('active-status', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function scopeFilterByType(Builder $builder, $type)
    {
        return $builder->when($type, function (Builder $builder, $type) {
            return $builder->where('type', $type);
        });
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function client()
    {
        return $this->belongsToMany(Client::class);
    }

    public function appointment()
    {
        return $this->belongsToMany(Appointment::class);
    }

    public function item()
    {
        return $this->belongsToMany(Item::class);
    }
}
