<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'cnpj',
        'fantasy_name',
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'path_storage',
        'logo',
        'signature'
    ];

    protected $hidden = [
        'cpf',
        'deleted_at'
    ];

    /**
     * @param Builder $builder
     * @param string $name
     *
     * @return
     */
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

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    /**
     * Attributes
     */
    public function getPathAttribute()
    {
        return "company/id_{$this->id}";
    }

    public function getPathStorageAttribute()
    {
        return "storage/company/id_{$this->id}";
    }

    public function getLogoAttribute()
    {
        return $this->images()->where('tag', 'logo')->first();
    }

    public function getSignatureAttribute()
    {
        return $this->images()->where('tag', 'signature')->first();
    }

    /**
     * Actions
     */
    public function makeDirectory()
    {
        return Storage::makeDirectory("company/id_{$this->id}");
    }
}
