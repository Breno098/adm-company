<?php

namespace App\Services\Expense;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexExpenseService
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
        return Expense::with($relations)
            ->when($authorized, function (Builder $builder) {
                return $builder->authorizedTenant();
            })
            ->when(Arr::get($filters, 'title'), function (Builder $builder, $title) {
                return $builder->where('title', 'like', "%{$title}%");
            })
            ->when(Arr::get($filters, 'date'), function (Builder $builder, $date) {
                return $builder->where('date', $date);
            })
            ->when($orderBy, function (Builder $builder, $orderBy) {
                return $builder->orderby($orderBy);
            })
            ->when(
                $itemsPerPage,
                function (Builder $builder) use ($itemsPerPage) {
                    return $builder->paginate($itemsPerPage);
                },
                function (Builder $builder) {
                    return $builder->get();
                }
            );
    }
}
