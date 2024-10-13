<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as RequestHTTP;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request as RequestFacade;

class PageController extends Controller
{
    const web_prefix = "http://localhost:8000/api/";

    private function getAuthenticatedUser()
    {
        $token = session('auth_token');
        if (!$token) {
            return null;
        }

        $apiReq = RequestFacade::create(self::web_prefix . 'user', 'GET');
        $apiReq->headers->set('Authorization', 'Bearer ' . $token);
        $apiReq->headers->set('Accept', 'application/json');

        $responseData = json_decode(app()->handle($apiReq)->getContent());
        $retryCount = 0;
        $maxRetries = 60;
        while (is_null($responseData) && $retryCount < $maxRetries) {
            sleep(0.5);
            $responseData = json_decode(app()->handle($apiReq)->getContent());
            $retryCount++;
        }
        if (is_null($responseData)) {
            return null;
        } else {
            if (!isset($responseData->user)) {
                dd($responseData);
            }
            $userData = $responseData->user;
        }

        $authenticatedUser = [
            'id' => $userData->id,
            'name' => $userData->name,
            'email' => $userData->email,
            'created_at' => $userData->created_at,
            'updated_at' => $userData->updated_at,
        ];

        return $authenticatedUser;
    }

    public function login(RequestHTTP $request)
    {
        $apiReq = RequestFacade::create(self::web_prefix . 'login', 'POST', [
            'email' => $request->all()['email'],
            'password' => $request->all()['password']
        ]);

        $apiResponse = Route::dispatch($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());


        if($apiResponse->getStatusCode() != 201){
            // TODO : send error message
            return redirect('/login');
        }
        session(['auth_token' => $dataResponse->token]);

        return redirect('/');
    }

    public function logoutPage()
    {
        if(!session('auth_token')){
            return redirect('/');
        }

        // call api logout and handle using app()->handle
        $apiReq = RequestFacade::create(self::web_prefix . 'logout', 'POST');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));

        $apiResponse = app()->handle($apiReq);

        if($apiResponse->getStatusCode() != 200){
            dd($apiResponse);
            return redirect('/');
        }

        session()->forget('auth_token');

        return redirect('/');
    }

    public function loginPage()
    {
        return view('login');
    }

    public function homePage()
    {
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }

        # call in api to get all teams
        $apiReq = RequestFacade::create(self::web_prefix . 'teams', 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');

        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if ($apiResponse->getStatusCode() == 401) {
            $homeData['teams'] = null;
        }

        else {
            $homeData['teams'] = $dataResponse->data;
        }
        return view('home', compact('homeData'));
    }

    public function registerPage()
    {
        return view('register');
    }

    public function register(RequestHTTP $request)
    {
        // call api register and handle using app()->handle
        $apiReq = RequestFacade::create(self::web_prefix . 'register', 'POST', [
            'name' => $request->all()['name'],
            'email' => $request->all()['email'],
            'password' => $request->all()['password'],
            'password_confirmation' => $request->all()['password_confirmation'],
        ]);

        $apiResponse = app()->handle($apiReq);

        if($apiResponse->getStatusCode() != 201){
            // TODO : send error message
            return redirect('/register');
        }

        return redirect('/login');
    }

    public function teamFormPage()
    {
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        return view('team-form', compact('homeData'));
    }

    public function searchTeam(RequestHTTP $request)
    {

        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'search/teams?search=' . $request->all()['search'], 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');

        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());


        $homeData['teams'] = $dataResponse->data;


        return view('home', compact('homeData'));
    }

    public function teamForm(RequestHTTP $request)
    {
        $apiReq = RequestFacade::create(self::web_prefix . 'teams', 'POST', [
            'name' => $request->all()['name'],
            'origin' => $request->all()['origin'],
            'livery_color' => $request->all()['livery_color'],
            'team_chief' => $request->all()['team_chief'],
        ]);


        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));

        $apiResponse = app()->handle($apiReq);

        if($apiResponse->getStatusCode() != 201){
            // TODO : send error message
            return redirect('/form/team');
        }

        return redirect('/');
    }

    public function addTeam(RequestHTTP $request)
    {
        $apiReq = RequestFacade::create(self::web_prefix . 'teams', 'POST', [
            'name' => $request->all()['name'],
            'origin' => $request->all()['origin'],
            'livery_color' => $request->all()['livery_color'],
            'team_chief' => $request->all()['team_chief'],
        ]);

        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));

        $apiResponse = app()->handle($apiReq);

        if($apiResponse->getStatusCode() != 201){
            // TODO : send error message
            return redirect('/form/team');
        }

        return redirect('/');
    }

    public function deleteTeam($id)
    {
        $apiReq = RequestFacade::create(self::web_prefix . 'teams/'.$id, 'DELETE');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));

        $apiResponse = app()->handle($apiReq);

        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/');
        }

        return redirect('/');
    }

    public function updateTeamPage($id){
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'teams/'.$id, 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/');
        }

        $homeData['team_id'] = $dataResponse->data->id;
        $homeData['team_name'] = $dataResponse->data->name;
        return view('update-team-form', compact('homeData'));
    }

    public function updateTeam(RequestHTTP $request, $id){
        if ($request->all()['name'] == null && $request->all()['origin'] == null && $request->all()['livery_color'] == null && $request->all()['team_chief'] == null) {
            return redirect('/');
        }

        $apiReq = RequestFacade::create(self::web_prefix . 'teams/'.$id, 'PUT', $request->all());
        $apiReq->headers->set('Accept', 'application/json');

        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));

        $apiResponse = app()->handle($apiReq);
        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/edit/teams');
        }

        return redirect('/');

    }

    public function viewTeamPage($id){
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'teams/'.$id, 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/');
        }

        $homeData['team'] = $dataResponse->data;


        $apiReq = RequestFacade::create(self::web_prefix . 'drivers/team/'.$id, 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/');
        }

        if (!isset($dataResponse->data)) {
            dd($dataResponse);
        }
        $homeData['drivers'] = $dataResponse->data;
        return view('team-detail', compact('homeData'));
    }

    public function driversPage(){
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'drivers', 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/');
        }

        $homeData['drivers'] = $dataResponse->data;

        # get team name for each driver
        foreach ($homeData['drivers'] as $driver) {
            $apiReq = RequestFacade::create(self::web_prefix . 'teams/'.$driver->racing_team_id, 'GET');
            $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
            $apiReq->headers->set('Accept', 'application/json');
            $apiResponse = app()->handle($apiReq);
            $dataResponse = json_decode($apiResponse->getContent());

            if($apiResponse->getStatusCode() != 200){
                dd($apiResponse);
                $driver->team_name = "NaN";
            }
            else {
                $driver->team_name = $dataResponse->data->name;
            }
        }

        return view('drivers-home', compact('homeData'));
    }

    public function searchDriver(RequestHTTP $request){
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'search/drivers?search=' . $request->all()['search'], 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');

        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if (!isset($dataResponse->data)) {
            dd($dataResponse, $request->all());
        }

        $homeData['drivers'] = $dataResponse->data;

        # get team name for each driver
        foreach ($homeData['drivers'] as $driver) {
            $apiReq = RequestFacade::create(self::web_prefix . 'teams/'.$driver->racing_team_id, 'GET');
            $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
            $apiReq->headers->set('Accept', 'application/json');
            $apiResponse = app()->handle($apiReq);
            $dataResponse = json_decode($apiResponse->getContent());

            if($apiResponse->getStatusCode() != 200){
                $driver->team_name = "NaN";
            }
            else {
                $driver->team_name = $dataResponse->data->name;
            }
        }


        return view('drivers-home', compact('homeData'));
    }

    public function updateDriverPage($id){
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'drivers/'.$id, 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/');
        }

        $homeData['driver'] = $dataResponse->data;

        $apiReq = RequestFacade::create(self::web_prefix . 'teams/', 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        # loop through all teams and get the team name and id, store it in a dict
        $homeData['teams'] = [];
        foreach ($dataResponse->data as $team) {
            $homeData['teams'][$team->id] = $team->name;
        }

        return view('update-driver-form', compact('homeData'));
    }

    public function updateDriver(RequestHTTP $request, $id){
        if ($request->all()['name'] == null && $request->all()['driver_number'] == null && $request->all()['racing_team_id'] == null) {
            return redirect('/');
        }

        $apiReq = RequestFacade::create(self::web_prefix . 'drivers/'.$id, 'PUT', $request->all());
        $apiReq->headers->set('Accept', 'application/json');

        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));

        $apiResponse = app()->handle($apiReq);
        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/edit/drivers');
        }

        return redirect('/drivers');
    }

    public function deleteDriver($id){

        $apiReq = RequestFacade::create(self::web_prefix . 'drivers/'.$id, 'DELETE');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiResponse = app()->handle($apiReq);
        if ($apiResponse->getStatusCode() != 200) {
            // TODO : send error message
            return redirect('/drivers');
        }

        return redirect('/drivers');
    }

    public function addDriver(RequestHTTP $request){
        if (!$request->all()['points']) {
            $request->merge(['points' => 0]);
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'drivers', 'POST', [
            'name' => $request->all()['name'],
            'origin' => $request->all()['origin'],
            'age' => $request->all()['age'],
            'points' => $request->all()['points'],
            'role' => $request->all()['role'],
            'driver_number' => $request->all()['driver_number'],
            'racing_team_id' => $request->all()['racing_team_id'],
        ]);



        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');

        $apiResponse = app()->handle($apiReq);


        if($apiResponse->getStatusCode() != 201){

            dd($apiResponse);
            return redirect('/drivers');
        }

        return redirect('/drivers');
    }

    public function viewDriverPage($id){
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'drivers/'.$id, 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if($apiResponse->getStatusCode() != 200){
            // TODO : send error message
            return redirect('/');
        }

        $homeData['driver'] = $dataResponse->data;

        $apiReq = RequestFacade::create(self::web_prefix . 'teams/'.$homeData['driver']->racing_team_id, 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        if($apiResponse->getStatusCode() != 200){
            $homeData['driver']->team_name = "NaN";
        }

        $homeData['driver']->team_name = $dataResponse->data->name;

        return view('driver-detail', compact('homeData'));
    }

    public function driverFormPage(){
        $homeData = $this->getAuthenticatedUser();
        if (!$homeData) {
            return redirect('/login');
        }
        $apiReq = RequestFacade::create(self::web_prefix . 'teams/', 'GET');
        $apiReq->headers->set('Authorization', 'Bearer '.session('auth_token'));
        $apiReq->headers->set('Accept', 'application/json');
        $apiResponse = app()->handle($apiReq);
        $dataResponse = json_decode($apiResponse->getContent());

        # loop through all teams and get the team name and id, store it in a dict
        $homeData['teams'] = [];
        foreach ($dataResponse->data as $team) {
            $homeData['teams'][$team->id] = $team->name;
        }

        return view('driver-form', compact('homeData'));
    }
}
