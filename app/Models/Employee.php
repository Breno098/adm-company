<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'rg',
        'birth_date',
        'salary',
        'admission_date',
        'resignation_date',
        'active'
    ];

    protected $casts = [
        'birth_date' => 'datetime:Y-m-d',
        'admission_date' => 'datetime:Y-m-d',
        'resignation_date' => 'datetime:Y-m-d',
        'active' => 'boolean',
        'salary' => 'float'
    ];

    /**
     * Relationships
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
