<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Services\ProductServices;

class ProductController extends Controller
{
    use ProductServices;
    public function createProduct(ProductRequest $request)
    {

        return $this->createProductService($request);
    }
    public function updateProduct(ProductRequest $request)
    {

        return $this->updateProductService($request);
    }
    public function deleteProduct(Request $request)
    {
        return $this->deleteProductServices($request);
    }

    public function getProducts(Request $request)
    {

        return $this->getProductsService($request);
    }
    public function searchProductByName(Request $request)
    {
        return $this->searchProductByNameService($request);
    }
    public function getProductById(Request $request, $id)
    {
        return $this->getProductByIdService($request, $id);
    }
}
