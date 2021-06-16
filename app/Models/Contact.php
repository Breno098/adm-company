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
        'main'
    ];

    protected $casts = [
        'active' => 'boolean',
        'main' => 'boolean',
    ];

    protected $with = [
        'type'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function type()
    {
        return $this->hasOne(ContactType::class, 'type_id');
    }
}
