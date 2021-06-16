<?php

namespace App\Models;

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

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_address', 'client_id', 'address_id');
    }
}
