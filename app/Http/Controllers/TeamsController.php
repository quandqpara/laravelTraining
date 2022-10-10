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

    //------------------------------------------------------------VIEWS-------------------------------------------------
    public function searchTeam()
    {
        $name = "";
        $teams = $this->teamsRepo->findByName($name);

        return view('teams/searchTeam')->with('teams', $teams);
    }

    public function createTeam()
    {
        return view('teams/createTeam');
    }

    public function createTeamConfirm(CreateTeamRequest $request)
    {
        $name = $request->get('name');
        $request->flash();
        if($this->teamsRepo->targetExist($name,'name','teams') > 0){
            return redirect('teams/createTeam')->with('message', 'Team already exist!');
        }
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
        $data = $this->teamsRepo->includeTime($data);

        //1
        try {
            $this->teamsRepo->create($data);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                $request->flash();
                return redirect('teams/createTeam')->with('message', 'Team already exist!');
            }
        }

        //2
        if (!$this->teamsRepo->isExist($data['name'])) {
            $request->flash();
            Session::flash('message', 'Failed to create team!');
            return redirect('teams/createTeam');
        }

        $message = 'Team ' . $data['name'] . ' has been created!';
        Session::flash('success', $message);
        return $this->index($request);
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

        try {
            $result = $this->teamsRepo->update($data, $data['id']);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                $request->flash();
                return redirect('teams/editTeam/' . $data['id'])->with('message', 'Team name already exist!');
            }
        }

        if ($result == false) {
            $request->flash();
            Session::flash('message', 'Failed to update. Please try again!');
            return redirect('teams/editTeam/' . $data['id']);
        }

        Session::flash('success', 'Team ID:' . $data['id'] . ' has been edited!');
        return $this->index($request);
    }

    /**
     * Search(Read) function
     * @return Application|Factory|View
     * basically an array of result from TEAMS table
     */
    public function index(Request $request, $column='id', $direction='asc')
    {
        $name = $request->get('name');
        $teams = $this->teamsRepo->findByName($name, $column, $direction);

        $request->flash();
        return view('teams.searchTeam', ['teams' => $teams, 'column' => $column, 'direction' => $direction]);
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
        return $this->searchTeam();
    }
}
