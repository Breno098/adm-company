<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @package App\Models
 *
 * @property string $description
 * @property string $type
 * @property float $amount
 * @property float $amount_paid
 * @property Date $execution_date
 * @property Date $technical_visit_date
 * @property DateTime $technical_visit_time
 * @property float $discount_amount
 * @property string $discount_percentage
 * @property int $warranty_days
 * @property string $warranty_conditions
 * @property string $installments
 * @property string $comments
 *
 * @property Status $status
 * @property Client $client
 * @property Address $address
 * @property Item $items
 * @property Item $products
 * @property Item $services
 * @property Appointment $appointment
 * @property Payment $formOfPayments
 *
 * @method Builder concluded()
 * @method Builder filterByStatusId(null|int $statusId)
 * @method Builder filterByStatusType(null|string $status_type)
 */
class Order extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
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
        'installments',
        'comments'
    ];

    protected $casts = [
        'technical_visit_date' => 'date:Y-m-d',
        'technical_visit_time' => 'datetime:H:i',
        'amount' => 'float',
        'amount_paid' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
    ];

    protected $with = [
        'status',
    ];

    public function scopeFilterByStatusId(Builder $query, $statusId)
    {
        return $query->when($statusId, function (Builder $query, $statusId) {
            return $query->where('status_id', $statusId);
        });
    }

    public function scopeFilterByStatusType(Builder $query, ?string $status_type)
    {
        return $query ->when($status_type, function (Builder $builder, $status_type) {
            switch ($status_type) {
                case 'concluded': return $builder->concluded();
                default: return $builder;
            }
        });
    }

    public function scopeConcluded(Builder $query)
    {
        return $query->where('status_id', 5);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

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

    public function formOfPayments()
    {
        return $this->belongsToMany(Payment::class, 'form_of_payment_order', 'order_id', 'payment_id');
    }
}
