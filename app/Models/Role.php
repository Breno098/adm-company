<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package App\Models
 *
 * @property string $description
 * @property string $name
 * @property string $role
 * @property string $tag
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property User[]|Collection $users
 *
 * @method Role filterByRole(null|string $role)
 */
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

     /**
     * Scopes
     */
    public function scopeFilterByRole(Builder $builder, $role)
    {
        return $builder->when($role, function (Builder $builder, $role) {
            return $builder->where('role', $role);
        });
    }

     /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
