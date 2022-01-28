<?php

namespace App\Services\EmployeeReceipt;

use App\Models\EmployeeReceipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexEmployeeReceiptService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|string $orderBy
     * @param  bool|int  $itemsPerPage
     * @param  bool $authorized
     *
     * @return mixed
     */
    public function run(array $filters = [], array $relations = [], $orderBy = false, $itemsPerPage = false, bool $authorized = true)
    {
        return EmployeeReceipt::with($relations)
            ->when($authorized, function (Builder $builder) {
                return $builder->authorizedTenant();
            })
            ->when(Arr::get($filters, 'date_start'), function (Builder $builder, $date_start) {
                return $builder->where('date_start', '>=', "%{$date_start}%");
            })
            ->when(Arr::get($filters, 'date_end'), function (Builder $builder, $date_end) {
                return $builder->where('date_end', '<=', "%{$date_end}%");
            })
            ->when($orderBy, function (Builder $builder, $orderBy) {
                return $builder->orderby($orderBy);
            })
            ->when(
                $itemsPerPage,
                function (Builder $builder, $itemsPerPage) {
                    return $builder->paginate($itemsPerPage);
                },
                function (Builder $builder) {
                    return $builder->get();
                }
            );
    }
}
