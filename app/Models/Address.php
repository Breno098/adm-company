<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends TenantModel
{
    use HasFactory, SoftDeletes;

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
        'main',
        'block',
        'house',
        'tower'
    ];

    protected $casts = [
        'main' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Scopes
     */

    /**
     * @param Builder $builder
     * @param int $client_id
     */
    public function scopeFilterClientId(Builder $builder, $cliente_id)
    {
        return $builder->when($cliente_id, function (Builder $builder, $cliente_id) {
            return $builder->where('client_id', $cliente_id);
        });
    }

    /**
     * Relationships
     */

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
}
