<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

class TenantModel extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);

        static::saving(function ($model) {
            $model->company_id = auth()->user()->company_id;
        });
    }
}
