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
        'execution_date_start',
        'execution_date_end',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'execution_date_start' => 'datetime:Y-m-d H:i',
        'execution_date_end' => 'datetime:Y-m-d H:i',
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
            return $builder->where('execution_date_start', '>=', "{$dateStart} 00:00:00");
        });

        $builder->when($dataEnd, function (Builder $builder, $dataEnd) {
            return $builder->where('execution_date_end', '<=', "{$dataEnd} 23:59:59");
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

    public function getDateStartAttribute()
    {
        return $this->execution_date_start->format('Y-m-d');
    }

    public function getHourStartAttribute()
    {
        return $this->execution_date_start->format('H:i');
    }
    public function getDateEndAttribute()
    {
        return $this->execution_date_end ? $this->execution_date_end->format('Y-m-d') : null;
    }

    public function getHourEndAttribute()
    {
        return $this->execution_date_end ? $this->execution_date_end->format('H:i') : null;
    }
}
