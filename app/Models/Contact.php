<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'contact',
        'active',
        'main',
        'type'
    ];

    protected $casts = [
        'active' => 'boolean',
        'main' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
