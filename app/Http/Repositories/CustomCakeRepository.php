<?php

namespace App\Http\Repositories;

use App\Models\CustomCake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class CustomCakeRepository
{
    public function queryGetCustomCake(Object $request)
    {
        return CustomCake::orderByDesc('id')->with('user')->where('status', $request->status)->get();
    }

    public function queryCreateCustomeCake($request)
    {
        return CustomCake::create([
            'user_id' => $request->id,
            'quantity' => $request->quantity,
            'message' => $request->message,
            'remarks' => $request->remarks,
            "image" => $request->image,
            "status" => $request->status,
        ]);
    }

    public function queryCustomCakeByUser($id)
    {
        return CustomCake::where('id', $id)->with('user')->first();
    }

    public function queryGetCustomCakeById($id)
    {
        return CustomCake::where('id', $id)->first();
    }

    public function queryUpdateCustomeCake(Object $request, $id)
    {
        $data = $this->queryGetCustomCakeById($id);
        return $data->update($request->all());
    }

    public function queryDeleteCustomeCake($id)
    {
        $data = $this->queryGetCustomCakeById($id);
        return $data->delete();
    }

    public function getUserCustomCake($id, $status)
    {
        return CustomCake::where('user_id', $id)->orderByDesc('id')->where('status', $status)->get();
    }

}
