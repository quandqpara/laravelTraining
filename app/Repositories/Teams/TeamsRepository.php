<?php

namespace App\Repositories\Teams;

use App\Repositories\Baserepository;

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

    /**
     * if find by name return at least a row -> is_exist = true
     * @param string $name
     * @return mixed
     */
    public function isExist(string $name)
    {
        $target = $this->findByName($name)->toArray();
        if (empty($target)) {
            return false;
        }
        return true;
    }

    /**
     * find LIKE %name% where del_flag = 0
     * @param $name
     * @param string $column
     * @param string $direction
     * @return void
     */
    public function findByName($name, $column = 'id', $direction = 'asc')
    {
        return $this->model->select('id', 'name')
            ->where([['name', 'LIKE', '%' . $name . '%'],
                ['del_flag', '=', config('global.DEL_FLAG_OFF')]])

            ->orderBy($column, $direction)
            ->paginate(config('global.PAGE_LIMIT'))
            ->withQueryString();
    }

    /**
     * @param $id
     * @return string
     */
    public function getName($id)
    {
        $target = $this->model->find($id);
        $arr = $target->toArray();
        return $arr['name'];
    }
}
