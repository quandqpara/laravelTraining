<?php

namespace App\Repositories\Employees;

interface EmployeesRepositoryInterface
{
    public function findByName(string $name);
}
