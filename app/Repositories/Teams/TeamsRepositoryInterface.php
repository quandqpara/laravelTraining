<?php

namespace App\Repositories\Teams;

interface TeamsRepositoryInterface
{
    public function findByName(string $name);
}
