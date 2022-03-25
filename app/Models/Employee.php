<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @package App\Models
 *
 * @property string $name
 * @property string $cpf
 * @property string $rg
 * @property Carbon $birth_date
 * @property float $salary
 * @property Carbon $admission_date
 * @property Carbon $resignation_date
 * @property bool $active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property Position $position
 * @property Address[]|Collection $addresses
 * @property Contact[]|Collection $contacts
 * @property Company $company
 */
class Employee extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'rg',
        'birth_date',
        'salary',
        'admission_date',
        'resignation_date',
        'active'
    ];

    protected $casts = [
        'birth_date' => 'datetime:Y-m-d',
        'admission_date' => 'datetime:Y-m-d',
        'resignation_date' => 'datetime:Y-m-d',
        'active' => 'boolean',
        'salary' => 'float'
    ];

    /**
     * Relationships
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
