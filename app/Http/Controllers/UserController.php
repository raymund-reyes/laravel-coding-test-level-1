<?php

namespace App\Http\Controllers;

use Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $data = [];
        if (Auth::attempt($credentials)) {

            $success = true;
            $message = "Welcome, " . auth()->user()->name;
            $data = auth()->user();
            $data['token'] = auth()->user()->createToken('API TOKEN')->plainTextToken;
            $response = [
                'message' => $message,
                'data' => $data
            ];
            return response()->json($response);
        } else {
            $message = 'Unauthorised';
            return response()->json(['message' => $message], 400);
        }

    }

    public function logout()
    {
        try {
            Session::flush();
            $success = true;
            $message = 'Successfully logged out';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }
}
