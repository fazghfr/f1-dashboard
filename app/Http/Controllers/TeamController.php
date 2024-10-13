<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        /* Get all teams */
        try {
            $teams = Team::all();
            $response = [
                'message' => 'Success get all teams',
                'data' => $teams
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat membaca data Team'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function show($id)
    {
        /* Get team by id */
        try {
            $team = Team::find($id);
            $response = [
                'message' => 'Success',
                'data' => $team
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat membaca data Team'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function update(Request $request, $id)
    {
        /* Update team by id */
        $fields = $request->validate([
            'name' => 'nullable|string',
            'origin' => 'nullable|string',
            'livery_color' => 'nullable|string',
            'team_chief' => 'nullable|string'
        ]);

        if (empty(array_filter($fields))) {
            $response = [
                'message' => 'Bad Request'
            ];

            $jsonResponse = response($response, 400);
            return $jsonResponse;
        }
        try {
            $team = Team::find($id);
            foreach ($fields as $key => $value) {
                if (!is_null($value)) {
                    $team->$key = $value;
                }
            }
            $team->save();
            $response = [
                'message' => 'Success',
                'data' => $team
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat mengupdate data Team'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function store(Request $request)
    {
        /* Store new team */
        $fields = $request->validate([
            'name' => 'required|string',
            'origin' => 'required|string',
            'livery_color' => 'required|string',
            'team_chief' => 'required|string'
        ]);

        try {
            $team_check = Team::where('name', $fields['name'])->first();
            if ($team_check) {
                $response = [
                    'message' => 'Team sudah ada'
                ];
                $jsonResponse = response($response, 400);

                return $jsonResponse;
            }
            $team = Team::create($fields);
            $response = [
                'message' => 'Success',
                'data' => $team
            ];

            $jsonResponse = response($response, 201);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat menambahkan data Team'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function destroy($id)
    {
        /* Delete team by id */
        try {
            $team = Team::find($id);
            if (!$team) {
                $response = [
                    'message' => 'Team tidak ditemukan'
                ];
                $jsonResponse = response($response, 404);

                return $jsonResponse;
            }
            $team->delete();
            $response = [
                'message' => 'Success',
                'data' => $team
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat menghapus data Team'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function getAllNames()
    {
        /* Get all team names */
        try {
            $teams = Team::all('name');
            $response = [
                'message' => 'Success',
                'data' => $teams
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat membaca data Team'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function search(Request $request)
    {
        /* Search team by name */
        $fields = $request->validate([
            'search' => 'required|string'
        ]);

        try {
            $teams = Team::where('name', 'like', '%' . $fields['search'] . '%')
                         ->orWhere('origin', 'like', '%' . $fields['search'] . '%')
                         ->get();

            $response = [
                'message' => 'Success',
                'data' => $teams
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat mencari data Team'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }
}
