<?php

namespace App\Http\Service;

use App\Http\Repositories\CustomCakeRepository;
use Illuminate\Http\Request;

class CustomCakeModelService
{
    private $customCakeRepository;
    public function __construct(CustomCakeRepository $customCakeRepository)
    {
        $this->customCakeRepository = $customCakeRepository;
    }

    public function GetCustomCake(Request $request)
    {
        return $this->customCakeRepository->queryGetCustomCake($request);
    }

    public function CreateCustomCake(Object $request)
    {
        return $this->customCakeRepository->queryCreateCustomeCake($request);
    }

    public function CustomCakeByUser($id)
    {
        return $this->customCakeRepository->queryCustomCakeByUser($id);
    }

    public function CustomCakeById($id)
    {
        return $this->customCakeRepository->queryGetCustomCakeById($id);
    }

    public function UpdateCake(Object $request, $id)
    {
        return $this->customCakeRepository->queryUpdateCustomeCake($request, $id);
    }

    public function DeleteCustomCake($id)
    {
        return $this->customCakeRepository->queryDeleteCustomeCake($id);
    }

    public function UserCustomeCake($id, $status)
    {
        return $this->customCakeRepository->getUserCustomCake($id, $status);
    }
}
