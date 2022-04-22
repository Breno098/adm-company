<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package App\Models
 *
 * @property string $name
 * @property string $cpf
 * @property string $cnpj
 * @property string $fantasy_name
 * @property Carbon $birth_date
 * @property string $type
 * @property string $notes
 * @property int $category_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property Address[]|Collection $addresses
 * @property Contact[]|Collection $contacts
 * @property Order[]|Collection $orders
 * @property Appointment $appointment
 * @property Category $category
 * @property Company $company
 */
class Client extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'cnpj',
        'fantasy_name',
        'birth_date',
        'type',
        'notes',
        'category_id',
    ];

    protected $casts = [
        'birth_date' => 'datetime:Y-m-d',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Relationships
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'owner');
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'owner');
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
