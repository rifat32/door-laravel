<?php

namespace App\Http\Services;

use App\Models\Product;

trait ProductServices
{
    public function createProductService($request)
    {
        $product =   Product::create($request->all());
        return response()->json(["product" => $product], 201);
    }
    public function updateProductService($request)
    {

        $product = tap(Product::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "name",
                "brand",
                "category",
                "sku",
                "price",
                "wing_id",
            )
        )->with("wing")->first();
        return response()->json(["product" => $product], 200);
    }
    public function deleteProductServices($request)
    {
        Product::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getProductsService($request)
    {
        $products =   Product::with("wing")->paginate(10);
        return response()->json([
            "products" => $products
        ], 200);
    }
    public function searchProductByNameService($request)
    {
        $product =   Product::where([
            "name" => $request->search
        ])->with("wing")->first();
        if (!$product) {
            return response()->json([
                "message" => "No product is found"
            ], 404);
        }
        return response()->json([
            "product" => $product
        ], 200);
    }
    public function getProductByIdService($request, $id)
    {
        $product =   Product::with("wing")->where([
            "id" => $id
        ])->first();
        if (!$product) {
            return response()->json([
                "message" => "No product is found"
            ], 404);
        }
        return response()->json([
            "product" => $product
        ], 200);
    }
}
