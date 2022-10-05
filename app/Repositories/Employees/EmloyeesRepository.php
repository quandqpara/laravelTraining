<?php

namespace App\Repositories\Employees;

use App\Repositories\Baserepository;

class EmloyeesRepository extends BaseRepository implements EmployeesRepositoryInterface
{

    /**
     * find LIKE %name% where del_flag = 0
     * @param $name
     * @param string $column
     * @param string $direction
     * @return void
     */
    public function findByName($name, $column = 'id', $direction = 'asc')
    {
        return $result = $this->model->select('id', 'name')
            ->where([['name', 'LIKE', '%' . $name . '%'],
                ['del_flag', '=', 0]])
            ->when(!empty(request('name')), function ($q) {
                $q->where('name', 'LIKE', '%' . request('name') . '%');
            })
            ->when(str_contains(request('name'), '%'), function ($q) {
                $namePhrase = str_replace('%', '\%', request('name'));
                $q->where('name', 'LIKE', '%' . $namePhrase . '%');
            })
            ->orderBy($column, $direction)
            ->paginate(3)
            ->withQueryString();
    }

    public function getModel()
    {
        return \App\Models\Employees::class;
    }

    public function isExist(string $name)
    {
        // TODO: Implement isExist() method.
    }

    public function getName($id)
    {
        // TODO: Implement getName() method.
    }
}
