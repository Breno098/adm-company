<?php

namespace App\Services\Item;

use App\Models\Item;

class ShowService
{
    /**
     * @param  mixed  $id
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run($id, array $relations = [])
    {
       $item = Item::find($id);

       if (is_null($item)) {
            return [];
        }

        $item->load($relations);
        
        return $item;
    }
}
