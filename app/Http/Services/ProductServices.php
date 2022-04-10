<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\Product;
use Exception;

trait ProductServices
{
    use ErrorUtil;
    public function createProductService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/VariationTemplate'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;

            $insertableData = $request->validated();
            $inserted_product =   Product::create($insertableData);
            if($insertableData["type"] == "single"){
                $inserted_product->product_variations()->create([
                    "name" => "Dummy",
                ]);
                $variation_data = [

                    "price" => $insertableData["price"],
                    'name' => 'DUMMY',
                ];
            $inserted_product->variations()->create($variation_data);
            }






            // $insertedVariationValueTemplateArray = [];
            // foreach ($insertableData["variation_value_template"] as $value) {
            //     $value["variation_template_id"] = $inserted_variation_template->id;
            //     $insertedVariationValue = $inserted_variation_template->variation_value_template()->create($value);
            //     array_push($insertedVariationValueTemplateArray, $insertedVariationValue);
            // }

             $data['data'] = $inserted_product;
            // $data['data']["variation_value_template"] = $insertedVariationValueTemplateArray;
            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
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
