<?php

namespace App\Repositories\Teams;

interface TeamsRepositoryInterface
{
    public function isExist(string $name);

    public function findByName(string $name, $column, $direction);
}
