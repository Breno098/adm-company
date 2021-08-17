<?php

namespace App\Services\Category;

use App\Models\Category;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
    {
        return Category::create($data);
    }
}
