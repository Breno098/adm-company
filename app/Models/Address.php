<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'district',
        'city',
        'state',
        'street',
        'cep',
        'complement',
        'apartment',
        'floor',
        'description',
        'active',
        'main'
    ];

    protected $casts = [
        'active' => 'boolean',
        'main' => 'boolean'
    ];

    protected static function booted()
    {
        static::addGlobalScope('active-address', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
}
