<?php

namespace App\Repositories\Employees;

use App\Repositories\Baserepository;
use App\Repositories\Teams\TeamsRepository;
use Illuminate\Support\Facades\DB;

class EmloyeesRepository extends BaseRepository implements EmployeesRepositoryInterface
{

    /**
     * find LIKE %name% where del_flag = 0
     * @param string $column
     * @param string $direction
     * @return void
     */
    public function findEmployee($column = 'id', $direction = 'desc', $export = false)
    {
        if ($column == 'name') {
            $column = 'last_name';
        }
        $teamID = replacePercent(request()->get('team_id'));
        $name = replacePercent(request()->get('name'));
        $email = replacePercent(request()->get('email'));

        $result = $this->model->select('id', 'avatar', 'team_id', 'first_name', 'last_name', 'email')
            ->when(!empty($teamID), function ($q) use ($teamID) {
                $q->where('team_id', '=', $teamID);
            })
            ->when(!empty($email), function ($q) use ($email) {
                $q->where('email', 'LIKE', '%' . $email . '%');
            })
            ->when(!empty($name), function ($q) use ($name) {
                $q->where(function($query) use ($name){
                    $query->where('first_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                        ->orWhere(DB::raw("CONCAT(last_name,' ',first_name)"), 'LIKE', '%' . $name . '%');
                });
            })
            ->orderBy($column, $direction);

        if (!$export) {
            $result = $result->paginate(config('global.PAGE_LIMIT'))
                ->withQueryString();
        } elseif ($export){
            $result = $result->get();
        }

        return $result;
    }

    public function findAll($column = 'id', $direction = 'desc')
    {
        return $this->model->select('id', 'avatar', 'team_id', 'first_name', 'last_name', 'email')
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
        return $this->model->where('email', '=', $email)->count() > 0;
    }

    /**
     * @param $id
     * @return string
     */
    public function getName($id)
    {
        $employee = $this->model->find($id);
        $name = $employee->last_name.' '.$employee->first_name;
        return $name;
    }

    public function getTeamList()
    {
        $teamModal = new TeamsRepository;
        $teams = $teamModal->getAll();
        return $teams->toArray();
    }
}
