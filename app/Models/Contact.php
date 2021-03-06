<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'contact',
        'main',
        'type',
        'owner_id',
        'owner_type',
        'company_id'
    ];

    protected $casts = [
        'main' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Scopes
     */
    public function scopePhones(Builder $builder)
    {
        return $builder->whereIn('type', ['TELEFONE', 'CELULAR', 'WHATSAPP']);
    }

    public function scopeEmails(Builder $builder)
    {
        return $builder->where('type', 'EMAIL');
    }

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
