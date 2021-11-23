<?php

namespace App\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ClientRepository.
 *
 * @package namespace App\Repositories\Interfaces;
 */
interface ClientRepository extends RepositoryInterface
{
    public function index(array $filters = [], array $relations = [], $orderBy = false, $itemsPerPage = false, bool $authorized = true);
}
