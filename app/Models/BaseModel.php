<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->company_id = auth()->user()->company_id;
        });
    }

    public function scopeAuthorizedByCompany(Builder $builder)
    {
        return $builder->where('company_id', auth()->user()->company_id);
    }
}
