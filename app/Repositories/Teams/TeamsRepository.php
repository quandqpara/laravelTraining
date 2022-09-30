<?php

namespace App\Repositories\Teams;
use BaseRepository;

class TeamsRepository extends BaseRepository implements TeamsRepositoryInterface
{

    /**
     * set Model to use for this Repo
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Teams::class;
    }


    public function findByName(string $name)
    {
        return $this->model->find($name);
    }
}
