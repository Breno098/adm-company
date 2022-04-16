<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends TenantModel
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

    public function scopeFilterByBetweenTime(Builder $builder, $timeStart, $timeEnd = null)
    {
        $builder->when($timeStart, function (Builder $builder, $timeStart) {
            return $builder->where('time_start', '>=', $timeStart);
        });

        $builder->when($timeEnd, function (Builder $builder, $timeEnd) {
            return $builder->where('time_end', '<=', $timeEnd);
        });

        return $builder;
    }

    public function scopeFilterByConcluded(Builder $builder, $concluded)
    {
        return $builder->when($concluded, function (Builder $builder, $concluded) {
            return $builder->where('concluded', $concluded);
        });
    }

    public function scopeFilterById(Builder $builder, $id)
    {
        return $builder->when($id, function (Builder $builder, $id) {
            return $builder->where('id', $id);
        });
    }

    public function scopeFilterByNotId(Builder $builder, $id)
    {
        return $builder->when($id, function (Builder $builder, $id) {
            return $builder->where('id', '!=', $id);
        });
    }

    public function scopeFilterByAddress(Builder $builder, $address)
    {
        return $builder->when($address, function (Builder $builder, $address) {
            return $builder->whereHas('address', function (Builder $builder) use ($address) {
                    return $builder->where('street', 'LIKE', "%{$address}%")
                            ->orWhere('district', 'LIKE', "%{$address}%")
                            ->orWhere('city', 'LIKE', "%{$address}%")
                            ->orWhere('cep', 'LIKE', "%{$address}%");
                });
        });
    }

    public function scopeFilterByClientName(Builder $builder, $clientName)
    {
        return $builder->when($clientName, function (Builder $builder, $clientName) {
            return $builder->whereHas('client', function (Builder $builder) use ($clientName) {
                    return $builder->where('name', 'LIKE', "%{$clientName}%");
                });
        });
    }

    public function scopeFilterByEmployee(Builder $builder, $employee)
    {
        return $builder->when($employee, function (Builder $builder, $employee) {
            return $builder->where('employee_id', $employee instanceof Employee ? $employee->id : $employee);
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
