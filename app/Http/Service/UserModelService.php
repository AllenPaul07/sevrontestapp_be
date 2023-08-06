<?php

namespace App\Http\Service;

use App\Models\User;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserModelService
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function RegisterUser(Request $request)
    {
        return $this->userRepository->queryRegisterUser($request);
    }

    public function GetUserById($userId)
    {
        return $this->userRepository->queryGetUserById($userId);
    }

    public function SaveUser(Object $request)
    {
        return $this->userRepository->querySaveUser($request);
    }

    public function GetAllUser()
    {
        return $this->userRepository->queryGetAllUser();
    }

    public function UpdateUser(Object $request, User $user)
    {
        return $this->userRepository->queryUpdateUser($request, $user);
    }

    public function DeleteUser(User $user)
    {
        return $this->userRepository->queryDeleteUser($user);
    }

}
