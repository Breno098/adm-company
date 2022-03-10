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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
