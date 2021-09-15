<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_data',
        'table_name',
        'data',
        'operation'
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->user()->associate(auth()->user());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
