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
        'type'
    ];

    protected $casts = [
        'main' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
