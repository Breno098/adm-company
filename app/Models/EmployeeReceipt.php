<?php

namespace App\Models;

use App\Services\Number\NumberExtensive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EmployeeReceipt extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date_start',
        'time_start',
        'date_end',
        'time_end',
        'amount',
        'discount_amount',
        'comments',
        'paid',
        'path'
    ];

    protected $casts = [
        'date_start' => 'date:Y-m-d',
        'date_end' => 'date:Y-m-d',
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
        'amount' => 'float',
        'discount_amount' => 'float',
        'paid' => 'boolean'
    ];

    /**
     * Relationships
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Attributes
     */
    public function getHasFileAttribute(): bool
    {
        return (bool) $this->path && Storage::exists($this->download_path);
    }

    public function getDateStartFormatAttribute(): string
    {
        return $this->date_start ? (string) $this->date_start->format('d/m/Y') : '';
    }

    public function getDateEndFormatAttribute(): string
    {
        return $this->date_end ? (string) $this->date_end->format('d/m/Y') : '';
    }

    public function getFullAmountInFullAttribute(): string
    {
        return $this->amount ? (new NumberExtensive($this->amount))->toCurrency() : '';
    }

    public function getAmountToCurrencyAttribute(): string
    {
        return $this->amount ? 'R$ ' . number_format($this->amount,2,",","") : '';
    }

    public function getDownloadPathAttribute(): string
    {
        return str_replace('storage', '', $this->path ?? '');
    }

    /**
     * Actions
     */
    public function download()
    {
        return Storage::download($this->download_path ?? '');
    }
}
