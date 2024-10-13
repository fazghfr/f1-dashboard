<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        /*
            login user dari request

            1. validasi request
            2. coba buat user
            3. jika berhasil, kirim response berhasil
        */
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        try {
            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password'])
            ]);

            $response = [
                'data' => $user,
                'message' => 'User Register Berhasil'
            ];
            $jsonResponse = response($response, 201);
        } catch (\Exception $e) {
            $response = [
                'message' => 'User Register Gagal'
            ];
            Log::error("User Register Error: " . $e->getMessage());
            $jsonResponse = response($response, 400);
        }


        return $jsonResponse;
    }

    public function login(Request $request) {
        /*
            login user dari request

            1. validasi request
            2. cek user di database
            3. jika user ada, buat token
            4. jika gaada, kirim response error dan log
            5. kirim response token
        */
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        try {
            $user = User::where('email', $fields['email'])->first();
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                throw new \Exception("User tidak ditemukan");
            }
            $token = $user->createToken('myapptoken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];

            $jsonResponse = response($response, 201);
        } catch (\Exception $e) {
            $response = [
                'message' => 'User Login Gagal'
            ];
            Log::error("User Login Error: " . $e->getMessage());
            $jsonResponse = response($response, 400);
        }

        return $jsonResponse;
    }

    public function logout(Request $request){
        // Access the authenticated user
        $user = $request->user();

        // Check if the user is authenticated
        if ($user) {
            // Delete all tokens for the authenticated user
            $user->tokens()->delete();

            $response = [
                'message' => 'User Logout Berhasil'
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } else {
            // If no user is authenticated
            $response = [
                'message' => 'User Logout Gagal'
            ];
            $jsonResponse = response($response, 404);

            return $jsonResponse;
        }
    }

    public function me(Request $request){
        // Access the authenticated user
        $user = $request->user();

        // Check if the user is authenticated
        if ($user) {
            $response = [
                'user' => $user
            ];

            $jsonResponse = response($response, 200);

            return $jsonResponse;
        } else {
            // If no user is authenticated
            $response = [
                'message' => 'User tidak ditemukan'
            ];
            $jsonResponse = response($response, 404);

            return $jsonResponse;
        }
    }
}
