<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\EditEmployeeRequest;
use App\Jobs\SendEmail;
use App\Repositories\Employees\EmloyeesRepository;
use App\Repositories\Employees\EmployeesRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
        if (Session::has('tempImgUrl')) {
            Session::forget('tempImgUrl');
        }
        $employees = $this->employeesRepo->findAll();

        $lastSearchUrl = url()->full();
        Session::put('lastSearchUrl', $lastSearchUrl);

        return view('employees/searchEmployee', ['teams' => $this->teams, 'employees' => $employees]);
    }

    public function createEmployee(): Factory|View|Application
    {
        if (request()->has('reset')) {
            Session::forget('tempImgUrl');
        }
        $previous = Session::get('_previous')['url'];
        if (str_contains($previous, 'search')) {
            Session::forget('_old_input');
        }
        return view('employees/createEmployee', ['teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function createEmployeeConfirm(CreateEmployeeRequest $request): Factory|View|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|Application
    {
        $data = $request->all();

        $request->flash();

        return view('employees/createEmployeeConfirm', ['employeeData' => $data, 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function editEmployee(int $id)
    {
        if (request()->has('reset')) {
            Session::forget('tempImgUrl');
        }

        $find = $this->employeesRepo->find($id);
        $target = $find->toArray();

        if (empty($target['0'])) {
            Session::flash('message', config('messages.TARGET_NOT_FOUND'));
            return Redirect::route('employee.searchEmployee');
        }

        session()->put('avatar_path', $target['0']['avatar']);

        return view('employees/editEmployee', ['target' => $target['0'], 'teams' => $this->teams, 'positionList' => $this->positionList, 'typeOfWork' => $this->typeOfWork]);
    }

    public function editEmployeeConfirm(EditEmployeeRequest $request)
    {
        $data = $request->all();

        if (empty($data)) {
            Session::flash('message', config('messages.TARGET_NOT_FOUND'));
            return Redirect::route('employee.searchEmployee');
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
     * @return \Illuminate\Http\RedirectResponse
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
        writeLog('Create Employee at Email ' . $data['email']);
        return Redirect::route('employee.searchEmployee');
    }

    /**
     * Update function
     * @param Request $request data from input
     * @return \Illuminate\Http\RedirectResponse
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

        $rediectDestination = Session::get('lastSearchUrl');
        Session::forget('lastSearchUrl');

        Session::flash('message', config('messages.UPDATE_SUCCESS'));
        writeLog('Update Employee at ID ' . $data['id']);
        return Redirect()->to($rediectDestination);
    }

    /**
     * Search(Read) function
     * @return Application|Factory|View
     * basically an array of result from employeeS table
     */
    public function index(Request $request)
    {
        $column = $request->get('column') ?? 'id';
        $direction = $request->get('direction') ?? 'desc';

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
     * @return \Illuminate\Http\RedirectResponse
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

        writeLog('Delete Employee at ID ' . $id);
        Session::flash('message', config('messages.DELETE_SUCCESS'));
        return Redirect::route('employee.searchEmployee');
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

