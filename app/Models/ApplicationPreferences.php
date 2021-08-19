<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationPreferences extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'first_title_option',
        'second_title_option',
        'third_title_option',
        'fourth_title_option',
        'fifth_title_option',
        'route_name',
        'params',
        'link',
        'icon',
        'color',
        'type'
    ];
}
