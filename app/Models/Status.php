<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'color',
        'icon',
        'order'
    ];

    public function scopeFilterByType(Builder $builder, $type)
    {
        return $builder->when($type, function (Builder $builder, $type) {
            return $builder->where('type', $type);
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->authorizedTenant();
    }

    public function client()
    {
        return $this->belongsToMany(Client::class)->authorizedTenant();
    }

    public function appointment()
    {
        return $this->belongsToMany(Appointment::class)->authorizedTenant();
    }

    public function item()
    {
        return $this->belongsToMany(Item::class)->authorizedTenant();
    }
}
