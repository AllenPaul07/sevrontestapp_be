<?php

namespace App\Http\Controllers;

use App\Models\CustomCake;
use Illuminate\Http\Request;
use App\Http\Service\CustomCakeModelService;
use Illuminate\Support\Facades\Auth;
use Image;

class CustomCakeController extends Controller
{

    private $customeCakeModelService;
    public function __construct(CustomCakeModelService $customeCakeModelService)
    {
        $this->customeCakeModelService = $customeCakeModelService;
    }

    public function index(Request $request) {
        $data = $this->customeCakeModelService->GetCustomCake($request);
        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request) {
        $image = "";
        if($request->hasFile('image')) {
            $image = $request->image->store('custom-cakes');
            $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            $img->save();
        }

        $customeCake = (object)([
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'message' => $request->message,
            'remarks' => $request->remarks,
            "image" => $image,
            "status" => $request->status,
        ]);
        $data = $this->customeCakeModelService->CreateCustomCake($customeCake);

        return response()->json([
            'message' => "Custom cake has been added.",
            'data' => $data
        ]);
    }

    public function show($id) {
        $data = $this->customeCakeModelService->CustomCakeByUser($id);

        return response()->json([
            'data' => $data
        ]);
    }


    public function update(Request $request, $id) {
        $data = $this->customeCakeModelService->UpdateCake($request, $id);

        return response()->json([
            'message' => "Custom cake has been updated.",
            'data' => $data
        ]);
    }

    public function delete($id) {
        $data = $this->customeCakeModelService->DeleteCustomCake($id);

        return response()->json([
            'message' => "Custom cake has been deleted.",
        ]);
    }

    public function getUsersCake(Request $request) {
        $id = Auth::user()->id;
        $status = $request->status;

        $data = $this->customeCakeModelService->UserCustomeCake($id, $status);

        return response()->json([
            'message' => "Fetch Success",
            'data' => $data,
            'status' => $status
        ]);
    }
}
