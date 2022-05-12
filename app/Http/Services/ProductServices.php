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
                    'variations.qty',
                    'products.sku',
                    'products.image'
                )
                ->get();




            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateProductBulkPriceService($request)
    {
        try{
            $updatableData = $request->validated();
            $amount = $updatableData["amount"];
            $type = $updatableData["type"];
           foreach( $updatableData["variations"] as $variation){
               if( $type == "fixed"){
                Variation::where([
                    "id" => $variation["vid"]
                ])
                ->increment("price",$amount);

               } else {
               $updatableVariation =  Variation::where([
                    "id" => $variation["vid"]
                ])->first();

                $increaseAmount = $updatableVariation->price * ($amount / 100);
                $finalAmount = $updatableVariation->price + $increaseAmount;
                $updatableVariation->price =  $finalAmount;
                $updatableVariation->save();



               }


           }




                    return response()->json([
                        "ok" => true
                    ], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function bulkDeleteService($request)
    {

        try{
            foreach($request["variations"] as $variation){
                Variation::where(["id" => $variation["vid"]])->delete();
            }

            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getProductService($request)
    {
        try{
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
            'products.image',

        )
        ->orderByDesc("id")
        ->paginate(10);




    return response()->json([
        "products" => $products
    ], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getProductPaginationService($perPage,$request)
    {
        try{
            // $products =   Variation::with("product.category")->paginate(10);
            $query = Product::with("images")
            ->join('variations', 'products.id', '=', 'variations.product_id')


            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')
            ->leftJoin('product_variations', 'variations.product_variation_id', '=', 'product_variations.id');

            if(!empty($request->category)){
                $query
                ->where([
               "c.id" => $request->category
                ]);
            }

        $products =  $query
        ->select(
            'products.id',
            'products.name',
            'products.type',
            'c.name as category',
            'variations.price',
            'variations.qty',
            'products.sku',
            'products.image',
            'variations.id as vid',
            'variations.name as vvalue',
            'product_variations.name as vname'
        )
        ->orderByDesc("vid")
        ->paginate($perPage);




    return response()->json([
        "products" => $products
    ], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getProductByIdService($request, $id)
    {
        try{
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
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function deleteProductServices($request)
    {

        try{
            Variation::where(["id" => $request["id"]])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

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
