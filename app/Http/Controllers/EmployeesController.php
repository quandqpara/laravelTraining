<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\EditEmployeeRequest;
use App\Http\Requests\EditTeamRequest;
use App\Repositories\Employees\EmloyeesRepository;
use App\Repositories\Employees\EmployeesRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EmployeesController extends Controller
{
    /**
     * @var EmployeesRepositoryInterface | EmloyeesRepository
     */
    protected EmloyeesRepository|EmployeesRepositoryInterface $employeesRepo;
    protected array $positionList;
    protected array $typeOfWork;
    protected array $teams;

    public function __construct(EmployeesRepositoryInterface $employeesRepo)
    {
        $this->employeesRepo = $employeesRepo;
        $this->teams = $this->employeesRepo->getTeamList();
        $this->positionList = [['id' => 1, 'name' => 'Manager'], ['id' => 2, 'name' => 'Team lead'], ['id' => 3, 'name' => 'BSE'], ['id' => 4, 'name' => 'DEV'], ['id' => 5, 'name' => 'Tester']];
        $this->typeOfWork = [['id' => 1, 'name' => 'Full time'], ['id' => 2, 'name' => 'Part time'], ['id' => 3, 'name' => 'Probationary Staff'], ['id' => 4, 'name' => 'Intern']];
    }

    //-------------------------------------------VIEWS------------------------------------------------------------------
    public function searchEmployee(): Factory|View|Application
    {
        $employees = $this->employeesRepo->findAll();

        return view('employees/searchEmployee', ['teams' => $this->teams, 'employees' => $employees]);
    }

    public function createEmployee(): Factory|View|Application
    {
        return view('employees/createEmployee', ['teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function createEmployeeConfirm(CreateEmployeeRequest $request): Factory|View|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|Application
    {
        $data = $request->all();

        $email = $request->get('email');

        if ($this->employeesRepo->targetExist($email, 'email', 'employees') > 0) {
            return redirect('employees/createEmployee')->with('message', 'Employee already exist!');
        }

        return view('employees/createEmployeeConfirm', ['employeeData' => $data, 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function editEmployee(int $id): Factory|View|Application
    {
        $find = $this->employeesRepo->find($id);
        $target = $find->toArray();
        session()->put('avatar_path', $target['0']['avatar']);
        return view('employees/editEmployee',['target'=>$target['0'], 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function editEmployeeConfirm(EditEmployeeRequest $request): Factory|View|Application
    {
        $data = $request->all();
        $data = correctingInputForEdit($data);

        return view('employees/editEmployeeConfirm', ['data'=>$data, 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    //--------------------------------------------CRUD------------------------------------------------------------------

    /**
     * get $data from request -> get 2 path of avatar (temp, auth) -> create
     * -> create failed -> redirect to create Page
     * -> create success ->
     * Create function
     * @param Request $request data from input
     * @return Application|Factory|View
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $temp = $data['avatar'];
        $avatar = str_replace('temp/temp_', 'auth/', $temp);
        $data['avatar'] = $avatar;

        $this->employeesRepo->create($data);

        if (!$this->employeesRepo->isExist($data['email'])) {
            $request->flash();
            Session::flash('message', 'Failed to create employee!');
            return redirect('employees/createEmployee');
        }

        $message = 'Employee ' . $data['last_name'] .' '. $data['first_name'] . ' has been created!';
        Session::flash('success', $message);
        rename($temp, $avatar);
        session()->forget('tempImgUrl');
        return $this->index($request);
    }

    /**
     * Update function
     * @param Request $request data from input
     * @param $id
     * @return Application|Factory|View
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $temp = $data['avatar'];
        $avatar = str_replace('temp/temp_', 'auth/', $temp);
        $data['avatar'] = $avatar;


        $employee = $this->employeesRepo->update($data, $data['id']);

        if ($employee == false) {
            $request->flash();
            Session::flash('message', 'Failed to update. Please try again!');
            return redirect('employees/editEmployee/' . $data['id']);
        }

        rename($temp, $avatar);
        session()->forget('avatar_path');
        session()->forget('tempImgUrl');
        Session::flash('success', 'Employee ' . $data['last_name'] . ' ' . $data['first_name'] . ' information has been edited!');
        return $this->index($request);
    }

    /**
     * Search(Read) function
     * @return Application|Factory|View
     * basically an array of result from employeeS table
     */
    public function index(Request $request)
    {

        $column = $request->get('column') ?? 'id';
        $direction = $request->get('direction') ?? 'asc';

        $employees = $this->employeesRepo->findEmployee($column, $direction);

        $request->flash();
        return view('employees.searchEmployee', ['employees' => $employees, 'teams' => $this->teams, 'column' => $column, 'direction' => $direction]);
    }

    /**
     * Search one by name
     * @param Request $request
     * @return Application|Factory|View
     */
    public function show(Request $request)
    {
        $employees = $this->employeesRepo->findByName($request->get('name'));

        return view('employees.search', ['employees', $employees]);
    }

    /**
     * Delete function by ID
     * @param $id
     * @return Application|Factory|View
     */
    public function destroy($id)
    {
        $name = $this->employeesRepo->getName($id);
        $result = $this->employeesRepo->delete($id);

        if ($result == false) {
            Session::flash('success', 'The employee has not been deleted!');
            return redirect('employees/searchEmployee/' . $id);
        }

        Session::flash('success', 'Employee ' . $name . ' has been deleted!');
        return $this->searchEmployee();
    }

}

