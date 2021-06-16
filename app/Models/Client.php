<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'fantasy_name',
        'birth_date',
        'type',
        'notes',
        'active'
    ];

    protected $casts = [
        'birth_date' => 'datetime: Y-m-d',
        'active' => 'boolean'
    ];

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'client_address', 'client_id', 'address_id');
    }

    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function status()
    {
        return $this->hasOne(Status::class);
    }
}
