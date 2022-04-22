<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @package App\Models
 *
 * @property string $name
 * @property string $cpf
 * @property string $cnpj
 * @property string $fantasy_name
 * @property Carbon $deadline
 *
 * @property-read string $phones_label
 * @property-read string $emails_label
 * @property-read string $path_storage
 *
 * @property User[]|Collection $users
 * @property Address[]|Collection $addresses
 * @property Address $addressMain
 * @property Contact[]|Collection $contacts
 * @property BankAccount[]|Collection $bankAccount
 * @property Image[]|Collection $images
 *
 * @method Company filterByName(null|string $name)
 * @method Company filterByCpf(null|string $cpf)
 * @method Company filterByCnpj(null|string $cnpj)
 *
 * @method bool makeDirectory()
 */
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
        // 'path_storage',
        // 'logo',
        // 'signature'
    ];

    protected $hidden = [
        'cpf',
        'deleted_at'
    ];

    /**
     * Scopes
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

    /**
     * Relationships
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function addressMain()
    {
        return $this->hasOne(Address::class)->oldestOfMany();
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'owner');
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'owner');
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

    public function getPhonesLabelAttribute()
    {
        $phones = $this->contacts()->phones()->get()->pluck('contact')->toArray();

        return implode(' | ', $phones);
    }

    public function getEmailsLabelAttribute()
    {
        $emails = $this->contacts()->emails()->get()->pluck('contact')->toArray();

        return implode(' | ', $emails);
    }

    /**
     * Actions
     */
    public function makeDirectory()
    {
        return Storage::makeDirectory("company/id_{$this->id}");
    }
}
