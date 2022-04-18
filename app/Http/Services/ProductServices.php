<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Http\Utils\ProductUtil;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationTemplate;
use Exception;

trait ProductServices
{
    use ErrorUtil, ProductUtil;
    public function createProductService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/VariationTemplate'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;

            $insertableData = $request->validated();



            $inserted_product =   Product::create($insertableData);

            if(!empty($insertableData["images"])){
                foreach($insertableData["images"] as  $image){
                    $inserted_product->images()->create(["file" => $image]);
                }

            }



            if ($insertableData["type"] == "single") {
                $this->createSingleVariationUtil($insertableData, $inserted_product);
            } else {
                $this->createVariationProductUtil($insertableData, $inserted_product);
            }
            $data['data'] = $inserted_product;

            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateProductService($request)
    {
        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/VariationTemplate'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;

            $updatableData = $request->validated();
            $updated_product =  tap(Product::where(["id" => $updatableData["id"]]))->update(
                collect($updatableData)->only([
                    "name",
                    "type",
                    "category_id",
                    "sku",
                    "image",
                    "description",
                ])
                    ->toArray()

            )
            ->first();
            if(!empty($updated_product["images"])){

     $updated_product->images()->delete();

    foreach($updatableData["images"] as  $image){
        $updated_product->images()->create(["file" => $image]);
    }




            }

            if ($updatableData["type"] == "single") {
                $this->updateSingleVariationUtil($updatableData, $updated_product);
            } else {
                $this->updateVariationProductUtil($updatableData, $updated_product);
            }




            $data['data'] =   Product::where(["products.id" => $updatableData["id"]])
                ->join('variations', 'products.id', '=', 'variations.product_id')


                ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')

                ->select(
                    'products.id',
                    'products.name',
                    'products.type',
                    'c.name as category',
                    'variations.price',
                    'products.sku',
                    'products.image'
                )
                ->get();




            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getProductService($request)
    {
        // $products =   Variation::with("product.category")->paginate(10);
        $products =    Product::join('variations', 'products.id', '=', 'variations.product_id')


            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')

            ->select(
                'products.id',
                'products.name',
                'products.type',
                'c.name as category',
                'variations.price',
                'products.sku',
                'products.image'
            )
            ->orderByDesc("id")
            ->paginate(10);




        return response()->json([
            "products" => $products
        ], 200);
    }
    public function getProductByIdService($request, $id)
    {
        $product =   Product::with("product_variations.variations", "variations")->where([
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
    public function deleteProductServices($request)
    {
        Product::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
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
}
