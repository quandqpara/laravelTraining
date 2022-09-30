<?php

namespace App\Repositories\Employees;
use BaseRepository;

class EmloyeesRepository extends BaseRepository implements EmployeesRepositoryInterface
{

    public function findByName(string $name)
    {
        return $this->model->firstWhere('name', $name);
    }

    public function getModel()
    {
        return \App\Models\Employees::class;
    }
}
