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
    public function findEmployee($column = 'id', $direction = 'asc')
    {
        if($column == 'name'){
            $column = 'last_name';
        }
        $teamID = request()->get('team_id') ?? '';
        $name = request()->get('name') ?? '';
        $email = request()->get('email') ?? '';

        return $this->model->select('id', 'avatar', 'team_id', 'first_name', 'last_name', 'email')
            ->where([['del_flag','=', config('global.DEL_FLAG_OFF')],
                    ['email', 'LIKE', '%'.$email.'%'],
                    ['team_id', 'LIKE', '%'.$teamID.'%']])
            ->when(!empty($teamID), function($q) use($teamID){
                $q->when(str_contains($teamID, '%'), function ($t) use ($teamID){
                    $teamIDPhrase = replacePercent($teamID);
                    $t->where('team_id', 'LIKE', '%'.$teamIDPhrase.'%');
                });
            })
            ->when(!empty($email), function($q) use ($email){
                $q->when(str_contains($email, '%'), function ($e) use ($email){
                    $emailPhrase = replacePercent($email);
                    $e->where('email', 'LIKE', '%'.$emailPhrase.'%');
                });
            })
            ->when(!empty($name), function($q) use ($name){
                $q->when(str_contains($name, '%'), function ($n) use ($name){
                    $namePhrase = replacePercent($name);
                    $n->where('first_name', 'LIKE', '%'.$namePhrase.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$namePhrase.'%')
                        ->orWhere(DB::raw("CONCAT(last_name,' ',first_name)"), 'LIKE', '%'.$namePhrase.'%');
                });
            })
            ->orderBy($column, $direction)
            ->paginate(config('global.PAGE_LIMIT'))
            ->withQueryString();
    }

    public function findAll($column = 'id', $direction='asc')
    {
        return $this->model->select('id', 'avatar', 'team_id', 'first_name', 'last_name', 'email')
            ->where('del_flag','=', 0)
            ->orderBy($column, $direction)
            ->paginate(config('global.PAGE_LIMIT'))
            ->withQueryString();
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

    /**
     * @param $id
     * @return string
     */
    public function getName($id)
    {
        $target = $this->model->find($id);
        $arr = $target->toArray();
        return $arr['last_name']. ' ' .$arr['first_name'];
    }
}
