<?php

namespace App\Repositories;

use App\Models\PersonInterface;

interface PersonRepositoryInterface
{
    /**
     * @return PersonInterface[]
     */
    public function getList(): iterable;

    /**
     * @param array $data
     * @return void
     */
    public function add(array $data);
}
