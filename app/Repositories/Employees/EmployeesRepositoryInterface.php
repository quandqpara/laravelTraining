<?php

namespace App\Repositories\Employees;

interface EmployeesRepositoryInterface
{
    public function isExist(string $name);

    public function findByName(string $name, $column, $direction);

    public function getName($id);
}
