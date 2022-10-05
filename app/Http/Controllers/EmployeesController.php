<?php

namespace App\Http\Controllers;

use App\Repositories\Employees\EmloyeesRepository;
use App\Repositories\Employees\EmployeesRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $this->positionList = [['id'=>'Manager', 'name'=>'Manager'], ['id'=>'Team lead','name'=>'Team lead'], ['id'=>'BSE','name'=>'BSE'], ['id'=>'DEV','name'=>'DEV'], ['id'=>'Tester','name'=>'Tester']];
        $this->typeOfWork = [['id'=>'Full time', 'name'=>'Full time'], ['id'=>'Part time','name'=>'Part time'], ['id'=>'Probationary Staff','name'=>'Probationary Staff'], ['id'=>'Intern','name'=>'Intern']];
    }

    //-------------------------------------------VIEWS------------------------------------------------------------------
    public function searchEmployee()
    {
        $teams = $this->employeesRepo->getTeamList();
        return view('employees/searchEmployee', ['teams' => $teams]);
    }

    public function createEmployee()
    {
        $teams = $this->employeesRepo->getTeamList();
        return view('employees/createEmployee', ['teams'=>$teams, 'positionList'=>$this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function createEmployeeConfirm()
    {
        $teams = $this->employeesRepo->getTeamList();
        return view('employees/createEmployeeConfirm', ['teams'=>$teams, 'positionList'=>$this->positionList, 'typeOfWork' => $this->typeOfWork]);
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

        //viet form request

        $employee = $this->employeesRepo->create($data);
        //check this output.

        return view('employees.search');
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
