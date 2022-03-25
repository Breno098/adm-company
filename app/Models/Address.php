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
 * @property int $owner_id
 * @property string $owner_type
 *
 * @method Address filterClient(null|int|Client $client)
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
        'tower',
        'company_id',
        'employee_id',
        'client_id',
        'owner_id',
        'owner_type',
    ];

    protected $casts = [
        'main' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'owner_id',
        'owner_type',
    ];

    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Scopes
     */

    public function scopeFilterClient(Builder $builder, $client)
    {
        return $builder->when(
            $client,
            function (Builder $builder, $client) {
                return $builder
                        ->where('owner_id', $client instanceof Client ? $client->id : $client)
                        ->where('owner_type', 'Client');
            }
        );
    }

    /**
     * Relationships
     */

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
}
