<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'date',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'date' => 'datetime:Y-m-d H:i',
    ];

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function status()
    {
        return $this->hasOne(Status::class);
    }

    public function getDateFormatAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    public function getHourFormatAttribute()
    {
        return $this->date->format('H:m');
    }
}
