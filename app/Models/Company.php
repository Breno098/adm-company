<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'cnpj',
        'fantasy_name',
    ];

    protected $casts = [
    ];

    public function scopeFilterByName(Builder $builder, $name)
    {
        return $builder->when($name, function (Builder $builder, $name) {
            return $builder->where('name', 'like', "%{$name}%");
        });
    }

    public function scopeFilterByCpf(Builder $builder, $cpf)
    {
        return $builder->when($cpf, function (Builder $builder, $cpf) {
            return $builder->where('cpf', 'like', "%{$cpf}%");
        });
    }

    public function scopeFilterByCnpj(Builder $builder, $cnpj)
    {
        return $builder->when($cnpj, function (Builder $builder, $cnpj) {
            return $builder->where('cnpj', 'like', "%{$cnpj}%");
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
