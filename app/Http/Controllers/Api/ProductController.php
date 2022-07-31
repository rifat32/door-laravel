<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductPriceUpdateRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
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
    public function updateProduct(ProductUpdateRequest $request)
    {
        return $this->updateProductService($request);
    }
    public function updateBulkPrice(ProductPriceUpdateRequest $request)
    {
        return $this->updateProductBulkPriceService($request);
    }
    public function bulkDelete(Request $request)
    {
        return $this->bulkDeleteService($request);
    }


    public function getProduct(Request $request)
    {
        return $this->getProductService($request);
    }
    public function getProductPagination($perPage,Request $request)
    {
        return $this->getProductPaginationService($perPage,$request);
    }
    public function getProductPaginationClient($perPage,Request $request)
    {
        return $this->getProductPaginationServiceClient($perPage,$request);
    }
    public function getRelatedProductClient(Request $request)
    {

        return $this->getRelatedProductServiceClient($request);
    }

    public function getFeatutedProductClient(Request $request)
    {
        return $this->getFeaturedProductServiceClient($request);
    }

    public function getProductById(Request $request, $id)
    {
        return $this->getProductByIdService($request, $id);
    }
    public function getProductByIdClient(Request $request, $slug)
    {
        return $this->getProductByIdServiceClient($request, $slug);
    }

    public function deleteProduct(Request $request)
    {
        return $this->deleteProductServices($request);
    }


    public function searchProductByName(Request $request)
    {
        return $this->searchProductByNameService($request);
    }

}
