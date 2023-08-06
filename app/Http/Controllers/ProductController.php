<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Service\ProductModelService;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $productModelService;

    public function __construct(productModelService $productModelService)
    {
        $this->productModelService = $productModelService;
    }


    public function index(Request $request)
    {

        $products = ($request->type == "All") ? $this->productModelService->GetAllProducts()
                                                : $this->productModelService->GetProductById($request);
        return response()->json([
            "data" => $products
        ]);
    }

    public function store(Request $request)
    {
        if($request->hasFile('image')) {
            $image = $request->image->store('cakes');
            $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            $img->save();
        }

        $prodObj= (object)([
            "name" => $request->name,
            "type" => $request->type,
            "image" => $image,
            "description" => $request->description,
            "price" => $request->price,
        ]);

        $product = $this->productModelService->SaveProduct($prodObj);

        return response()->json([
            "message" => "Product has been added.",
            "data" => $product,
        ], 201);
    }


    public function show(Product $product)
    {
        return response()->json([
            "data" => $product,
        ], 200);
    }

    public function update(Request $request, Product $product)
    {
        if($request->hasFile("image")) {
            Storage::disk()->delete($product->image);
            $image = $request->image->store('cakes');
            $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            $img->save();
            $product->image = $image;
            $product->save();
        }

        $this->productModelService->UpdateProduct($request, $product);

        return response()->json([
            "message" => "Product has been updated.",
            "data" => $product
        ], 200);
    }

    public function destroy(Request $request, Product $product)
    {
        Storage::disk()->delete($product->image);
        $this->productModelService->DeleteProduct($product);

        return response()->json([
            "message" => "Product has been deleted.",
        ], 200);
    }
}
