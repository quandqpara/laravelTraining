<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTeamRequest;
use App\Models\Teams;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\EditTeamRequest;
use App\Http\Requests\SearchTeamRequest;
use App\Repositories\Teams\TeamsRepository;
use App\Repositories\Teams\TeamsRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    /**
     * @var TeamsRepositoryInterface|TeamsRepository
     */
    protected $teamsRepo;

    public function __construct(TeamsRepositoryInterface $teamsRepo)
    {
        $this->teamsRepo = $teamsRepo;
    }

    public function includeTime($data)
    {
        $dataReturn = array_merge($data, [
            'ins_id' => 1,
            'ins_datetime' => date('Y-m-d H:i:s')]);
        return $dataReturn;
    }

    /**
     * handling sorting direction logic -> This must be run before column handle
     * @param $data
     * @return string|void return either ASC | DESC based on cases
     */
    private function handleDirection($data){
        //if selecting a new column to sort
        if(isset($data['column']) && $data['column'] !== Session::get('column'))
        {
            Session::put('direction', 1);
            return $this->getDirection(1);
        }

        //if column selected is the same as previous or column is not selected/newly selected
        //if request not contain direction -> set session direction = 1 and direction to query = 1
        if(!isset($data['direction'])){
            Session::put('direction', 1);
            return $this->getDirection(1);
        }

        if(!session()->has('direction') && isset($data['direction'])){
            Session::put('direction', $data['direction']);
            return $this->getDirection($data['direction']);
        }

        //if request contain direction and request direct !== session direction
        // -> set session direction = request direction
        // -> set direction to query = request direction
        if($data['direction'] !== Session::get('direction'))
        {
            Session::put('direction', $data['direction']);
            return $this->getDirection($data['direction']);
        }

        //if request contain direction and request direct == session direction
        // -> set session direction = request direction
        // -> set direction to query = request direction
        if($data['direction'] == Session::get('direction'))
        {
            $queryDirection = (int)$data['direction'] * -1;

            Session::put('direction', $queryDirection);
            return $this->getDirection($queryDirection);
        }
    }

    /**
     * handling sorting column logic
     * @param $data
     * @return string
     */
    private function handleColumn($data){
        if(!isset($data['column'])){
            Session::put('column', 'id');
            return 'id';
        }

        Session::put('column', $data['column']);
        return $data['column'];
    }

    private function getDirection($direction){
        return ($direction == 1) ? 'ASC' : 'DESC';
    }

    private function reverseGetDirection($direction)
    {
        return ($direction == 'ASC') ? 1 : -1;
    }

    //------------------------------------------------------------VIEWS-------------------------------------------------
    public function searchTeam()
    {
        return view('teams/searchTeam');
    }

    public function createTeam()
    {
        return view('teams/createTeam');
    }

    public function createTeamConfirm(CreateTeamRequest $request)
    {
        $name = $request->get('name');
        $request->flash();
        return view('teams/createTeamConfirm')->with('name', $name);
    }

    public function editTeam(int $id)
    {
        $find = $this->teamsRepo->find($id);
        $target = $find->toArray();
        return view('teams/editTeam')->with('target', $target['0']);
    }

    public function editTeamConfirm(EditTeamRequest $request)
    {
        $data = $request->all();
        $request->flash();
        return view('teams/editTeamConfirm')->with('data', $data);
    }

    //-------------------------------------------------------------CRUD-------------------------------------------------

    /**
     * Create function
     * Get data from request -> add created time, id -> perform create on DB
     *          1, if email exist -> save old input data -> redirect with error
     *          2, if not -> check if there is a new Team created
     *              2.1, if not -> redirect with error message
     *              2.2, return to search team with success message
     * @param CreateTeamRequest $request data from input
     * @return Application|Factory|View
     */
    public function store(CreateTeamRequest $request)
    {
        $data = $request->all();
        $data = $this->includeTime($data);

        try {
            $this->teamsRepo->create($data);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                $request->flash();
                return redirect('teams/createTeam')->with('message', 'Team already exist!');
            }
        }

        if (!$this->teamsRepo->isExist($data['name'])) {
            $request->flash();
            Session::flash('message', 'Failed to create team!');
            return redirect('teams/createTeam');
        }

        $message = 'Team ' . $data['name'] . ' has been created!';
        Session::flash('message', $message);
        return view('teams/searchTeam');
    }

    /**
     * take request form (id, name) -> update()
     *          -> if result = false means nothing updated -> redirect to edit page with message and old input
     *          -> else means updated! -> redirect to search page with message
     * @param EditTeamRequest $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(EditTeamRequest $request)
    {
        $data = $request->all();
        $id = $data['id'];
        try {
            $result = $this->teamsRepo->update($data, $id);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                $request->flash();
                return redirect('teams/editTeam/' . $id)->with('message', 'Team name already exist!');
            }
        }

        if ($result == false) {
            $request->flash();
            Session::flash('message', 'Failed to update. Please try again!');
            return redirect('teams/editTeam/' . $id);
        }

        Session::flash('success', 'Team ID:' . $id . ' has been edited!');
        return view('teams/searchTeam');
    }

    /**
     * Search(Read) function
     * @return Application|Factory|View
     * basically an array of result from TEAMS table
     */
    public function index(SearchTeamRequest $request)
    {
        $name = $request->get('name');

        $teams = $this->teamsRepo->findByName(request()->get('name'));
        dd($teams);
        $request->flash();
        return view('teams.searchTeam', ['teams' => $teams]);
    }

    /**
     * Delete function by ID
     * @param $id
     * @return Application|Factory|View
     */
    public function destroy($id)
    {
        $name = $this->teamsRepo->getName($id);
        $result = $this->teamsRepo->delete($id);

        if ($result == false) {
            Session::flash('success', 'The team has not been deleted!');
            return redirect('teams/searchTeam/' . $id);
        }

        Session::flash('success', 'Team ' . $name . ' has been deleted!');
        return view('teams/searchTeam');
    }

    private function setSortingInfo($request){
        if(!$request->has('column') && !$request->has('direction')){
            return ['column'=>'id', 'direction' => 'ASC'];
        }
    }


}
