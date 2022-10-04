<?php

<<<<<<< HEAD
namespace App\Repositories;

=======
>>>>>>> origin/master
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
<<<<<<< HEAD
        return $this->model->where([['id','=',$id],['del_flag','=',0]])->get();
=======
        return $this->model->find($id);
>>>>>>> origin/master
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
<<<<<<< HEAD
     * Update function
     * Find the tar get then Update record
     * @param array $attributes
     * @param $id
     * @return false|mixed
     */
    public function update(array $attributes, $id){
        $target = $this->model->findOrFail($id);
        return $target->update($attributes);
=======
     * Update record
     * @param $id
     * @param $attributes
     * @return false|mixed
     */
    public function update($id, $attributes = []){
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
>>>>>>> origin/master
    }

    /**
     * Delete(soft) record by reuse Update with del_flag = 1
     * @param $id
     * @return bool
     */
    public function delete($id){
        $attributes = ['del_flag'=>1];
<<<<<<< HEAD
        return $this->update($attributes, $id);
=======
        $result =  $this->update($id, $attributes);
        if($result !== false){
            return true;
        }
        return false;
>>>>>>> origin/master
    }

}
