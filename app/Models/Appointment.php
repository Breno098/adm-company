<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    protected $with = [
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope('active-appointment', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function scopeFilterByDate(Builder $builder, $date)
    {
        return $builder->when($date, function (Builder $builder, $date) {
            return $builder->where('date', $date);
        });
    }

     public function scopeFilterByStatusId(Builder $builder, $statusId)
    {
        return $builder->when($statusId, function (Builder $builder, $statusId) {
            return $builder->where('status_id', $statusId);
        });
    }

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
        return $this->belongsTo(Status::class);
    }

    public function getDateFormatAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    public function getHourFormatAttribute()
    {
        return $this->date->format('H:i');
    }
}
