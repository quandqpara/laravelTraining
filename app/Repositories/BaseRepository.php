<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements RepositoryInterface
{
    //target model to interact with
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    //need to be defined in extended classes
    abstract public function getModel();

    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Find all records
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Find one record by ID
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->where([['id', '=', $id]])->get();
    }

    /**
     * Create record
     * @param $attributes
     * @return mixed
     */
    public function create($attributes = [])
    {
        $attributes = $this->includeTime($attributes);
        return $this->model->create($attributes);
    }

    /**
     * Update function
     * Find the tar get then Update record
     * @param array $attributes
     * @param $id
     * @return false|mixed
     */
    public function update(array $attributes, $id)
    {
        $target = $this->model->findOrFail($id);
        $attributes = $this->includeTimeUpdate($attributes);
        return $target->update($attributes);
    }


    /**
     * Delete(soft) record by reuse Update with del_flag = 1
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $attributes = ['del_flag' => config('global.DEL_FLAG_ON')];
        $result = $this->update($attributes, $id);
        if ($result !== false) {
            return true;
        }
        return false;
    }

    public function includeTime($data)
    {
        return array_merge($data, [
            'ins_id' => config('global.ADMIN_ID'),
            'ins_datetime' => date('Y-m-d H:i:s')]);
    }

    public function includeTimeUpdate($data)
    {
        return array_merge($data, [
            'upd_id' => config('global.ADMIN_ID'),
            'upd_datetime' => date('Y-m-d H:i:s')]);
    }

}
