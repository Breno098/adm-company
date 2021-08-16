<?php

namespace App\Services\Item;

use App\Models\Item;

class DestroyService
{
    /**
     * @param  mixed  $id
     *
     * @return mixed
     */
    static public function run($id)
    {
       $item = Item::find($id);

        if (is_null($item)) {
            return [];
        }

        $item->active = false;
        $item->save();
        
        return $item;
    }
}
