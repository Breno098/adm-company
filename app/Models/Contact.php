<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends AuthBaseModel
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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
