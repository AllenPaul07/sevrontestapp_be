<?php

namespace App\Http\Repositories;

use App\Models\Product;
use Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{
    public function queryGetAllProducts()
    {
        return $products = Product::orderByDesc("id")->get();
    }

    public function queryGetProductById(Object $request)
    {
        return Product::orderByDesc("id")->where('type', $request->type)->get();
    }

    public function querySaveProduct(Object $request)
    {
        return Product::create([
            "name" => $request->name,
            "type" => $request->type,
            "image" => $request->image,
            "description" => $request->description,
            "price" => $request->price,
        ]);
    }

    public function queryUpdateProduct(Object $request, Product $product)
    {
        return $product->update([
            "name" => $request->name,
            "type" => $request->type,
            "description" => $request->description,
            "price" => $request->price,
        ]);
    }

    public function queryDeletePoduct(Product $product)
    {
        $product->delete();
    }

}
