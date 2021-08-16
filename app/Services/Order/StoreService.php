<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
    {
        $item = Item::create($data);

        $item->categories()->sync(Arr::get($data, 'categories'));

        $item->status()->associate(Arr::get($data, 'status_id'));

        $item->save();

        return $item;
    }
}
