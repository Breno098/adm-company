<?php

namespace App\Services\Category;

use App\Models\Category;

class ShowService
{
    /**
     * @param  mixed  $id
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run(Category $category, array $relations = [])
    {
        $category->load($relations);
        
        return $category;
    }
}
