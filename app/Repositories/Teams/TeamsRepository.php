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
        if(empty($target)){
            return false;
        }
        return true;
    }

    /**
     * find LIKE %name% where del_flag = 0
     * @param $name
     * @return void
     */
    public function findByName($name, $column, $direction)
    {
        $sortField = request()->get('sortField', 'id');
        $sortType = request()->get('sortType', 'desc');

        return $result = $this->model->select('id', 'name')
            ->where([   ['name','LIKE','%'.$name.'%'],
                        ['del_flag', '=', 0]            ])
            ->when(!empty(request('name')), function($q) {
                $q->where('name','LIKE','%'.request('name') .'%');
            })
            ->orderBy($sortField, $sortType)
            ->paginate(3)
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
