<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuthBaseModel extends Model
{
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->company_id = auth()->user()->company_id;
        });

        static::addGlobalScope('company_auth', function (Builder $builder) {
            $builder->where('company_id', auth()->user()->company_id);
        });
    }
}
