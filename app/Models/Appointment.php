<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends AuthBaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'concluded'
    ];

    protected $casts = [
        'date_start' => 'date:Y-m-d',
        'date_end' => 'date:Y-m-d',
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i'
    ];

    public function scopeFilterByBetweenDate(Builder $builder, $dateStart, $dateEnd = null)
    {
        $builder->when($dateStart, function (Builder $builder, $dateStart) {
            return $builder->where('date_start', '>=', $dateStart);
        });

        $builder->when($dateEnd, function (Builder $builder, $dateEnd) {
            return $builder->where('date_end', '<=', $dateEnd);
        });

        return $builder;
    }

    public function scopeFilterByConcluded(Builder $builder, $concluded)
    {
        return $builder->when($concluded, function (Builder $builder, $concluded) {
            return $builder->where('concluded', $concluded);
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    
}
