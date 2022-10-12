<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\EditEmployeeRequest;
use App\Http\Requests\EditTeamRequest;
use App\Jobs\SendEmail;
use App\Repositories\Employees\EmloyeesRepository;
use App\Repositories\Employees\EmployeesRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PHPUnit\Exception;

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

        $request->flash();

        return view('employees/createEmployeeConfirm', ['employeeData' => $data, 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function editEmployee(int $id): Factory|View|Application
    {
        $find = $this->employeesRepo->find($id);
        $target = $find->toArray();

        if (empty($target['0'])) {
            Session::flash('messages', config('global.TARGET_NOT_FOUND'));
            return view(route('employee.searchEmployee'));
        }

        session()->put('avatar_path', $target['0']['avatar']);

        return view('employees/editEmployee', ['target' => $target['0'], 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function editEmployeeConfirm(EditEmployeeRequest $request): Factory|View|Application
    {
        $data = $request->all();

        if (empty($data)) {
            Session::flash('messages', config('global.TARGET_NOT_FOUND'));
            return view(route('employee.searchEmployee'));
        }

        $data = correctingInputForEdit($data);

        return view('employees/editEmployeeConfirm', ['data' => $data, 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
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
        $request->flash();

        $temp = $data['avatar'];
        $avatar = str_replace('temp/temp_', 'auth/', $temp);
        $data['avatar'] = $avatar;

        try {
            $this->employeesRepo->create($data);
        } catch (Exception $e) {
            handleExceptionMessage($e);
            return redirect(route('employee.createEmployee'));
        }

        if (!$this->employeesRepo->isExist($data['email'])) {
            Session::flash('message', config('messages.CREATE_FAILED'));
            return redirect(route('employee.createEmployee'));
        }

        rename($temp, $avatar);
        session()->forget('tempImgUrl');

        $message = [
            'subject' => config('mail.subject'),
            'content' => config('mail.content'),
        ];
        $employeeEmail = $data['email'];

        SendEmail::dispatch($message, $employeeEmail);

        Session::flash('message', config('messages.CREATE_SUCCESS'));
        writeLog('Create Employee at Email '.$data['email']);
        return $this->index($request);
    }

    /**
     * Update function
     * @param Request $request data from input
     * @return Application|Factory|View
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $request->flash();

        $temp = $data['avatar'];
        $avatar = str_replace('temp/temp_', 'auth/', $temp);
        $data['avatar'] = $avatar;

        try {
            $employee = $this->employeesRepo->update($data, $data['id']);
        } catch (Exception $e) {
            handleExceptionMessage($e);
            return redirect(route('employee.editEmployee'), ['id' => $data['id']]);
        }

        if (!$employee) {
            Session::flash('message', config('messages.UPDATE_FAILED'));
            return redirect(route('employee.editEmployee'), ['id' => $data['id']]);
        }

        rename($temp, $avatar);
        session()->forget('avatar_path');
        session()->forget('tempImgUrl');

        Session::flash('message', config('messages.UPDATE_SUCCESS'));
        writeLog('Update Employee at ID '.$data['id']);
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

        $employees = $this->employeesRepo->findEmployee($column, $direction, false);

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
        if (empty($id)) {
            Session::flash('message', config('messages.TARGET_NOT_FOUND'));
            return redirect(route('employee.searchEmployee'));
        }

        try {
            $result = $this->employeesRepo->delete($id);
        } catch (Exception $e) {
            handleExceptionMessage($e);
            return redirect(route('employee.searchEmployee'));
        }

        if (!$result) {
            Session::flash('message', config('messages.DELETE_FAILED'));
            return redirect(route('employee.searchEmployee'));
        }

        writeLog('Delete Employee at ID '.$id);
        Session::flash('message', config('messages.DELETE_SUCCESS'));
        return $this->searchEmployee();
    }

    //-------------------------------------------OTHERS-----------------------------------------------------------------
    public function exportCSV(Request $request)
    {
        $employees = $this->employeesRepo->findEmployee(config('global.DEFAULT_COLUMN'), config('global.DEFAULT_DIRECTION'), true);

        $employees = $employees->toArray();

        exportCSV($employees);
        Session::flash('message', config('messages.EXPORTED'));
        $file = config('global.DEFAULT_EXPORT_FILE_PATH');
        $headers = array(
            'Content-Type: text/csv',
        );
        return response()->download($file, 'export.csv', $headers);
    }
}

