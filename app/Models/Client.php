<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'document',
        'fantasy_name',
        'birth_date',
        'type',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'datetime:Y-m-d',
    ];

    public function scopeFilterByName(Builder $builder, $name)
    {
        return $builder->when($name, function (Builder $builder, $name) {
            return $builder->where('name', 'like', "%{$name}%");
        });
    }

    public function scopeFilterByDocument(Builder $builder, $document)
    {
        return $builder->when($document, function (Builder $builder, $document) {
            return $builder->where('document', 'like', "%{$document}%");
        });
    }

    public function scopeFilterByType(Builder $builder, $type)
    {
        return $builder->when($type, function (Builder $builder, $type) {
            return $builder->where('type', $type);
        });
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function status()
    {
        return $this->hasOne(Status::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
