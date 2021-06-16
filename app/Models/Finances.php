<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finances extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'profit',
        'year',
        'month',
        'expenses'
    ];

    protected $casts = [
        'profit' => 'float',
        'expenses' => 'float',
    ];
}
