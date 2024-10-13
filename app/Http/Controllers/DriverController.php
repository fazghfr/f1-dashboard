<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Team;


class DriverController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            $drivers = Driver::all();
            $response = [
                'message' => 'Success get all drivers',
                'data' => $drivers
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat membaca data Driver'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function show($id)
    {
        try {
            $driver = Driver::find($id);
            if (!$driver) {
                $response = [
                    'message' => 'Driver not found'
                ];
                $jsonResponse = response($response, 404);

                return $jsonResponse;
            }
            $response = [
                'message' => 'Success',
                'data' => $driver
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat membaca data Driver'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function showbyTeamID($id)
    {
        try {
            $team = Team::find($id);
            if (!$team) {
                $response = [
                    'message' => 'Invalid Team'
                ];
                $jsonResponse = response($response, 404);

                return $jsonResponse;
            }

            $drivers = Driver::where('racing_team_id', $id)->get();
            $response = [
                'message' => 'Success',
                'data' => $drivers
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat membaca data Driver'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);
        if (!$driver) {
            $response = [
                'message' => 'Driver not found'
            ];
            $jsonResponse = response($response, 404);

            return $jsonResponse;
        }
        $fields = $request->validate([
            'name' => 'nullable|string',
            'driver_number' => 'nullable|integer',
            'origin' => 'nullable|string',
            'age' => 'nullable|integer',
            'points' => 'nullable|string',
            'racing_team_id' => 'nullable|integer',
            'role' => 'nullable|string'
        ]);

        if (empty(array_filter($fields))) {
            $response = [
                'message' => 'Bad Request'
            ];

            $jsonResponse = response($response, 400);

            return $jsonResponse;
        }

        try {
            foreach ($fields as $field => $value) {
                if ($value) {
                    $driver->$field = $value;
                }
            }
            $driver->save();
            $response = [
                'message' => 'Success update driver',
                'data' => $driver
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat mengupdate data Driver'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);
        if (!$driver) {
            $response = [
                'message' => 'Driver not found'
            ];
            $jsonResponse = response($response, 404);

            return $jsonResponse;
        }

        try {
            $driver->delete();
            $response = [
                'message' => 'Success delete driver'
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat menghapus data Driver'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'driver_number' => 'required|integer',
            'origin' => 'required|string',
            'age' => 'required|integer',
            'points' => 'required|integer',
            'racing_team_id' => 'required|integer',
            'role' => 'required|string'
        ]);

        try {
            $checkDriver = Driver::where('driver_number', $fields['driver_number'])->first();
            if ($checkDriver) {
                $response = [
                    'message' => 'Driver sudah ada dengan nomor tersebut'
                ];
                $jsonResponse = response($response, 400);

                return $jsonResponse;
            }
            $driver = Driver::create($fields);
            $response = [
                'message' => 'Success',
                'data' => $driver
            ];

            $jsonResponse = response($response, 201);

            return $jsonResponse;
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error saat menambahkan data Driver'
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }

    public function search(Request $request)
    {
        $fields = $request->validate([
            'search' => 'required|string'
        ]);

        try {
            $drivers = Driver::where('name', 'like', '%' . $fields['search'] . '%')
                ->orWhere('origin', 'like', '%' . $fields['search'] . '%')
                ->orWhere('role', 'like', '%' . $fields['search'] . '%')
                ->get();
            $response = [
                'message' => 'Success',
                'data' => $drivers
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } catch (\Exception $e) {

            $response = [
                'message' => $e->getMessage()
            ];
            $jsonResponse = response($response, 500);

            return $jsonResponse;
        }
    }
}
