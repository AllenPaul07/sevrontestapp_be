<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Http\Service\UserModelService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $userModelService;

    public function __construct(UserModelService $userModelService)
    {
        $this->userModelService = $userModelService;
    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|max:50',
            'middle_name' => '',
            'last_name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'role' => 'required|string',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => [
                'required',
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed']
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = $this->userModelService->RegisterUser($request);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => "Register Successful.",
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token =  $user->createToken('token')->plainTextToken;
            return response()->json([
                'message' => "Login Successfull",
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }
        else{
            return response()->json(['message'=>'Invalid Credentials'], 401);
        }
    }

    public function logout(Request $request) {
        Auth::user()->tokens()->delete();
        return response()->json([
            "message" => "Logged Out"
        ]);
    }
}
