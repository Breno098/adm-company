<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'name',
        'role',
        'tag'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function scopeFilterByRole(Builder $builder, $role)
    {
        return $builder->when($role, function (Builder $builder, $role) {
            return $builder->where('role', $role);
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
