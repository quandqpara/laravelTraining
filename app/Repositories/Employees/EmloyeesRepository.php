<?php

namespace App\Repositories\Employees;

use App\Repositories\Baserepository;
use Illuminate\Support\Facades\DB;

class EmloyeesRepository extends BaseRepository implements EmployeesRepositoryInterface
{

    /**
     * find LIKE %name% where del_flag = 0
     * @param $name
     * @param string $column
     * @param string $direction
     * @return void
     */
    public function findEmployee($data, $column = 'id', $direction = 'asc')
    {
        $teamID = $data['team_id'];
        $name = $data['name'];
        $email = $data['email'];

        return $result = $this->model->select('id', 'name')
            ->where([
                ['team_id','LIKE', '%'.$teamID.'%'],
                ['email', 'LIKE', '%' . $email . '%'],
                ['del_flag', '=', 0]])
            ->when()
            ->orderBy($column, $direction)
            ->paginate(3)
            ->withQueryString();
    }

    public function findAll(){
        return $this->model->select('id', 'avatar', 'team_id', 'first_name', 'last_name', 'email')
            ->where('del_flag','=', 0)->get();
    }

    public function getModel()
    {
        return \App\Models\Employees::class;
    }

    public function isExist(string $email)
    {
        $isExist = DB::table('employees')->where('email', '=', $email )->count();
        if($isExist > 0){
            return true;
        }
        return false;
    }

    public function getName($id)
    {
        // TODO: Implement getName() method.
    }
}
