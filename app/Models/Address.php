<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package App\Models
 *
 * @property string $number
 * @property string $district
 * @property string $city
 * @property string $state
 * @property string $cep
 * @property string $complement
 * @property string $apartment
 * @property string $description
 * @property bool $main
 * @property string $block
 * @property string $house
 * @property string $tower
 *
 * @method Address filterClientId(null|string|int $clientId)
 */
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
     * @param int|string|null $clientId
     */
    public function scopeFilterClientId(Builder $builder, $clientId)
    {
        return $builder->when($clientId, function (Builder $builder, $clientId) {
            return $builder->where('client_id', $clientId);
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
