<?php

namespace App\Repositories\Employees;

interface EmployeesRepositoryInterface
{
    public function isExist(string $name);

    public function findEmployee(array $data, $column, $direction);

    public function getName($id);
}
