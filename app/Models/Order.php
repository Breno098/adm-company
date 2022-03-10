<?php

namespace App\Models;

use App\Services\Number\NumberExtensive;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * @package App\Models
 *
 * @property string $complaint
 * @property string $type
 * @property float $amount
 * @property float $amount_paid
 * @property Carbon $execution_date
 * @property Carbon $technical_visit_date
 * @property Carbon $technical_visit_time
 * @property float $discount_amount
 * @property string $discount_percentage
 * @property int $warranty_days
 * @property string $warranty_conditions
 * @property string $comments
 * @property string $status
 * @property string $payment_status
 * @property int $number_of_installments
 * @property string $accepted_payment_methods
 * @property string $work_found
 * @property string $work_done
 *
 * @property-read string $amount_to_currency
 * @property-read string $amount_to_currency_extensive
 * @property-read string $discount_amount_to_currency
 *
 * @property Client $client
 * @property Address $address
 * @property Installment[]|Collection $installments
 * @property Item[]|Collection $products
 * @property Item[]|Collection $services
 * @property Appointment $appointment
 *
 * @method Order filterByStatus(null|array|int $status)
 * @method Order filterByNameClient(null|string $client_name)
 * @method Order filterByAddress(null|string $address)
 */
class Order extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'complaint',
        'type',
        'amount',
        'amount_paid',
        'execution_date',
        'technical_visit_date',
        'technical_visit_time',
        'discount_amount',
        'discount_percentage',
        'warranty_days',
        'warranty_conditions',
        'comments',
        'status',
        'payment_status',
        'number_of_installments',
        'accepted_payment_methods',
        'work_found',
        'work_done'
    ];

    protected $casts = [
        'technical_visit_date' => 'date:Y-m-d',
        'technical_visit_time' => 'datetime:H:i',
        'amount' => 'float',
        'amount_paid' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
    ];

    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function products()
    {
        return $this->items()
                ->filterByType('product')
                ->select([
                    'items.id',
                    'item_order.quantity',
                    'item_order.value',
                    'items.name',
                    'items.default_value',
                    DB::raw('(item_order.quantity * item_order.value) as total_value')
                ]);
    }

    public function services()
    {
        return $this->items()
            ->filterByType('service')
            ->select([
                'items.id',
                'item_order.quantity',
                'item_order.value',
                'items.name',
                'items.default_value',
                DB::raw('(item_order.quantity * item_order.value) as total_value')
            ]);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class)
                    ->select([
                        'payments.id',
                        'payments.name',
                        'order_payment.value',
                        'order_payment.date',
                    ]);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

      /**
     * Scopes
     */
    public function scopeFilterByStatus(Builder $query, $status)
    {
        return $query->when($status, function (Builder $query, $status) {
            if(is_array($status)){
                return $query->whereIn('status', $status);
            }

            return $query->where('status', $status);
        });
    }

    public function scopeFilterByNameClient(Builder $builder, $client_name)
    {
        return $builder->when($client_name, function (Builder $builder, $client_name) {
            return $builder->whereHas('client', function (Builder $builder) use ($client_name) {
                    return $builder->where('name', 'LIKE', "%{$client_name}%");
                });
        });
    }

    public function scopeFilterByAddress(Builder $builder, $address)
    {
        return $builder->when($address, function (Builder $builder, $address) {
            return $builder->whereHas('address', function (Builder $builder) use ($address) {
                    return $builder->where('street', 'LIKE', "%{$address}%")
                                ->orWhere('district', 'LIKE', "%{$address}%")
                                ->orWhere('city', 'LIKE', "%{$address}%")
                                ->orWhere('cep', 'LIKE', "%{$address}%");
                });
        });
    }

    /**
     * Acessors
     */

    public function getAcceptedPaymentMethodsExplodeAttribute()
    {
        return explode(',', $this->accepted_payment_methods);
    }

    public function getProgramationDateFormatAttribute()
    {
        $dateformat = '';

        if($this->technical_visit_date){
            $dateformat .= $this->technical_visit_date->format('d/m/Y');
        }

        if($this->technical_visit_date && $this->technical_visit_time){
            $dateformat .= ' às ';
        }

        if($this->technical_visit_time){
            $dateformat .= $this->technical_visit_time->format('H:i');
        }

        return $dateformat;
    }

    public function getPhonesAttribute()
    {
        $phones = [];

        $this->client->contacts->each(function($contact) use (&$phones){
            if($contact->type === 'TELEFONE' || $contact->type === 'CELULAR' || $contact->type === 'WHATSAPP'){
                $phones[] = $contact->contact;
            }
        });

        return implode(' | ', $phones);
    }

    public function getEmailsAttribute()
    {
        $emails = [];

        $this->client->contacts->each(function($contact) use (&$emails){
            if($contact->type === 'EMAIL'){
                $emails[] = $contact->contact;
            }
        });

        return implode(' | ', $emails);
    }

    public function getAmountToCurrencyAttribute(): string
    {
        return $this->amount ? 'R$ ' . number_format($this->amount,2,",",".") : 'R$ 0,00';
    }

    public function getAmountToCurrencyExtensiveAttribute(): string
    {
        return $this->amount ? (new NumberExtensive($this->amount))->toCurrency() : '';
    }

    public function getDiscountAmountToCurrencyAttribute(): string
    {
        return $this->discount_amount ? 'R$ ' . number_format($this->discount_amount,2,",",".") : 'R$ 0,00';
    }
}
