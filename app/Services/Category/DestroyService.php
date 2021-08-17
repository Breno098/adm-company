<?php

namespace App\Services\Category;

use App\Models\Category;

class DestroyService
{
    /**
     * @param  mixed  $id
     *
     * @return mixed
     */
    static public function run(Category $category)
    {
        $category->active = false;
        $category->save();
        
        return $category;
    }
}
