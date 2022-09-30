<?php

namespace App\Repositories;

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
    public function getAll(){
        return $this->model->all();
    }

    /**
     * Find one record by ID
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->where([['id','=',$id],['del_flag','=',0]])->get();
    }

    /**
     * Create record
     * @param $attributes
     * @return mixed
     */
    public function create($attributes =[]){
        return $this->model->create($attributes);
    }

    /**
     * Update function
     * Find the tar get then Update record
     * @param array $attributes
     * @param $id
     * @return false|mixed
     */
    public function update(array $attributes, $id){
        $target = $this->model->findOrFail($id);
        return $target->update($attributes);
    }

    /**
     * Delete(soft) record by reuse Update with del_flag = 1
     * @param $id
     * @return bool
     */
    public function delete($id){
        $attributes = ['del_flag'=>1];
        return $this->update($attributes, $id);
    }

}
