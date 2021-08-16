<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run($id, array $data = [])
    {
        $item = Item::find($id);

        if(!$item){
            return $item;
        }

        $item->update($data);

        $item->categories()->sync(Arr::get($data, 'categories'));

        $item->status()->associate(Arr::get($data, 'status_id'));

        $item->save();

        return $item;
    }
}
