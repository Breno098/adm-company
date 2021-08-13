<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date_start',
        'date_end',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'date_start' => 'datetime:Y-m-d H:i',
        'date_end' => 'datetime:Y-m-d H:i',
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

    public function scopeFilterByBetweenDate(Builder $builder, $dateStart, $dataEnd = null)
    {
        $builder->when($dateStart, function (Builder $builder, $dateStart) {
            return $builder->where('date_start', '>=', "{$dateStart} 00:00:00");
        });

        $builder->when($dataEnd, function (Builder $builder, $dataEnd) {
            return $builder->where('date_end', '<=', "{$dataEnd} 23:59:59");
        });

        return $builder;
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

    public function getDateStartFormatAttribute()
    {
        return $this->date_start->format('Y-m-d');
    }

    public function getHourStartFormatAttribute()
    {
        return $this->date_start->format('H:i');
    }
    public function getDateEndFormatAttribute()
    {
        return $this->date_end ? $this->date_end->format('Y-m-d') : null;
    }

    public function getHourEndFormatAttribute()
    {
        return $this->date_end ? $this->date_end->format('H:i') : null;
    }
}
