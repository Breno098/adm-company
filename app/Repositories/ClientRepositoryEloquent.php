<?php

namespace App\Repositories;

use App\Interfaces\ClientRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Class ClientRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
        'cpf' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
