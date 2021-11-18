<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'agency',
        'bank',
        'main'
    ];

    protected $casts = [
        'main' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
