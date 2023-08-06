<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function queryRegisterUser(Object $request)
    {
        return User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'role' => $request->role,
            'contact_num' => $request->contact_num,
            'email' => $request->email,
            'password' => Hash::make($request->password)
     ]);
    }

    public function queryGetUserById($userId)
    {
        return User::where("id", $userId)->first();
    }

    public function querySaveUser(Object $request)
    {
        return User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'role' => $request->role,
            'contact_num' => $request->contact_num,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    public function queryGetAllUser()
    {
        return User::orderBy('id','desc')->get();
    }

    public function queryUpdateUser(Object $request, User $user)
    {
        return $user->update($request->all());
    }

    public function queryDeleteUser(User $user)
    {
        return $user->delete();
    }
}
