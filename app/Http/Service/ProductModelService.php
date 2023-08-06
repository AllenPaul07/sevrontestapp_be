<?php

namespace App\Http\Service;

use App\Http\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductModelService
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function GetAllProducts()
    {
        return $this->productRepository->queryGetAllProducts();
    }

    public function GetProductById(Object $request)
    {
        return $this->productRepository->queryGetProductById($request);
    }

    public function SaveProduct(Object $request)
    {
        return $this->productRepository->querySaveProduct($request);
    }

    public function UpdateProduct(Object $request, Product $product)
    {
        return $this->productRepository->queryUpdateProduct($request, $product);
    }

    public function DeleteProduct(Product $product)
    {
        return $this->productRepository->queryDeletePoduct($product);
    }
}
