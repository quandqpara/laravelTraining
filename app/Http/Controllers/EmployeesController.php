<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Repositories\Employees\EmloyeesRepository;
use App\Repositories\Employees\EmployeesRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EmployeesController extends Controller
{
    /**
     * @var EmployeesRepositoryInterface | EmloyeesRepository
     */
    protected EmloyeesRepository|EmployeesRepositoryInterface $employeesRepo;
    protected $positionList;
    protected $typeOfWork;

    public function __construct(EmployeesRepositoryInterface $employeesRepo)
    {
        $this->employeesRepo = $employeesRepo;
        $this->positionList = [['id' => 1, 'name' => 'Manager'], ['id' => 2, 'name' => 'Team lead'], ['id' => 3, 'name' => 'BSE'], ['id' => 4, 'name' => 'DEV'], ['id' => 5, 'name' => 'Tester']];
        $this->typeOfWork = [['id' => 1, 'name' => 'Full time'], ['id' => 2, 'name' => 'Part time'], ['id' => 3, 'name' => 'Probationary Staff'], ['id' => 4, 'name' => 'Intern']];
    }

    //-------------------------------------------VIEWS------------------------------------------------------------------
    public function searchEmployee()
    {
        $employees = $this->employeesRepo->findAll();
        $teams = $this->employeesRepo->getTeamList();
        return view('employees/searchEmployee', ['teams' => $teams]);
    }

    public function createEmployee()
    {
        $teams = $this->employeesRepo->getTeamList();
        return view('employees/createEmployee', ['teams' => $teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function createEmployeeConfirm(CreateEmployeeRequest $request)
    {
        $data = $request->all();

        $email = $request->get('email');

        if ($this->employeesRepo->targetExist($email, 'email', 'employees') > 0) {
            return redirect('employees/createEmployee')->with('message', 'Employee already exist!');
        }

        $teams = $this->employeesRepo->getTeamList();
        //tempImgUrl

        return view('employees/createEmployeeConfirm', ['employeeData' => $data, 'teams' => $teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    //--------------------------------------------CRUD------------------------------------------------------------------

    /**
     * Create function
     * @param Request $request data from input
     * @return Application|Factory|View
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $temp = $data['avatar'];
        $avatar = str_replace('temp/temp_', 'auth/',$temp);

        $data['avatar'] = $avatar;

        $this->employeesRepo->create($data);

        if(!$this->employeesRepo->isExist($data['email'])) {
            $request->flash();
            Session::flash('message', 'Failed to create employee!');
            return redirect('teams/createTeam');
        }

        $message = 'Employee ' . $data['first_name'] . ' has been created!';
        Session::flash('success', $message);
        session()->forget('tempImgUrl');
        return $this->searchEmployee();
    }

    /**
     * Update function
     * @param Request $request data from input
     * @param $id
     * @return Application|Factory|View
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        //viet form request

        $employee = $this->employeesRepo->update($id, $request);
        //check this output
        return view('employees.update');

    }

    /**
     * Search(Read) function
     * @return Application|Factory|View
     * basically an array of result from employeeS table
     */
    public function index()
    {
        $employees = $this->employeesRepo->getAll();
        //check this output
        return view('employees.search', ['employees' => $employees]);
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
        $result = $this->employeesRepo->delete($id);
        //check this $result
        return view('employees.search');
    }

}
