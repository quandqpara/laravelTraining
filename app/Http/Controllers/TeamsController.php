<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use App\Repositories\Teams\TeamsRepository;
use App\Repositories\Teams\TeamsRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    /**
     * @var TeamsRepositoryInterface|TeamsRepository
     */
    protected $teamsRepo;

    public function __construct(TeamsRepositoryInterface $teamsRepo){
        $this->teamsRepo = $teamsRepo;
    }

    //------------------------------------------------------------VIEWS-------------------------------------------------
    public function searchTeam(){
        if(Auth::check()){
            return view('teams/searchTeam');
        }
        return redirect('login')->with('success', 'You are not allow to access this page.');
    }


    //-------------------------------------------------------------CRUD-------------------------------------------------
    /**
     * Create function
     * @param Request $request  data from input
     * @return Application|Factory|View
     */
    public function store(Request $request){
        $data = $request->all();

        //viet form request

        $team = $this->teamsRepo->create($data);
        //check this output.

        return view('teams.search');
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

        $team = $this->teamsRepo->update($id, $request);
        //check this output
        return view('team.update');

    }

    /**
     * Search(Read) function
     * @return Application|Factory|View
     * basically an array of result from TEAMS table
     */
    public function index() {
        $teams = $this->teamsRepo->getAll();
        //check this output
        return view('teams.search', ['teams'=>$teams]);
    }

    /**
     * Search one by name
     * @param Request $request
     * @return Application|Factory|View
     */
    public function show(Request $request)
    {
        $teams = $this->teamsRepo->findByName($request->get('name'));

        return view('teams.search', ['teams', $teams]);
    }

    /**
     * Delete function by ID
     * @param $id
     * @return Application|Factory|View
     */
    public function destroy($id){
        $result = $this->teamsRepo->delete($id);
        //check this $result
        return view('teams.search');
    }

}
