<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
        'type',
        'color'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsToMany(Order::class);
    }

    public function client()
    {
        return $this->belongsToMany(Client::class);
    }

    public function appointment()
    {
        return $this->belongsToMany(Appointment::class);
    }

    public function item()
    {
        return $this->belongsToMany(Item::class);
    }
}
