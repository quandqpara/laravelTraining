<?php

namespace App\Http\Controllers;

use App\Repositories\Employees\EmloyeesRepository;
use App\Repositories\Employees\EmployeesRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    /**
     * @var EmployeesRepositoryInterface | EmloyeesRepository
     */
    protected $employeesRepo;

    public function __construct(EmployeesRepositoryInterface $employeesRepo){
        $this->employeesRepo = $employeesRepo;
    }

    //-------------------------------------------VIEWS------------------------------------------------------------------



    //--------------------------------------------CRUD------------------------------------------------------------------
    /**
     * Create function
     * @param Request $request  data from input
     * @return Application|Factory|View
     */
    public function store(Request $request){
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
    public function update(Request $request, $id){
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
    public function index() {
        $employees = $this->employeesRepo->getAll();
        //check this output
        return view('employees.search', ['employees'=>$employees]);
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
    public function destroy($id){
        $result = $this->employeesRepo->delete($id);
        //check this $result
        return view('employees.search');
    }

}
