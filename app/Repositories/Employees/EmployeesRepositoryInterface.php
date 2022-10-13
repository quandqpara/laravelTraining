<?php

namespace App\Repositories\Employees;

interface EmployeesRepositoryInterface
{
    public function isExist(string $name);

    public function findEmployee($column, $direction);

    public function getName($id);

    public function getTeamList();
}
