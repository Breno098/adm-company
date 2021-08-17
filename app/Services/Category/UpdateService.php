<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(Category $category, array $data = [])
    {
        $category->update($data);

        return $category;
    }
}
