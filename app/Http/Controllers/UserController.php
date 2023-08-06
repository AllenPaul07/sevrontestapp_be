<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Service\UserModelService;


class UserController extends Controller
{
    private $userModelService;

    public function __construct(UserModelService $userModelService)
    {
        $this->userModelService = $userModelService;
    }

    public function index()
    {
        $users = $this->userModelService->GetAllUser();

        return response()->json([
            "data" => $users
        ]);
    }

    public function store(UserRequest $request)
    {
        $user = $this->userModelService->SaveUser($request);

        return response()->json([
            "message" => "User has been added.",
            "data" => $user
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            "data" => $user
        ], 200);
    }



    public function update(Request $request, User $user)
    {
        $this->userModelService->UpdateUser($request, $user);
        return response()->json([
            "message" => "User has been updated.",
            "data" => $user
        ], 200);
    }


    public function destroy(User $user)
    {
        $this->userModelService->DeleteUser($user);

        return response()->json([
                "message" => "User has been deleted.",
        ], 200);
    }
}
