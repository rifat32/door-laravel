<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Http\Utils\ProductUtil;
use App\Models\Product;
use App\Models\ProductColor;
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
            if(!empty($insertableData["colors"])){

                foreach($insertableData["colors"] as  $color){
                    $inserted_product->colors()->create($color);
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
                    "style_id",
                    "sku",
                    "image",
                    "description",
                    "status",
                    "is_featured"
                ])
                    ->toArray()

            )
            ->first();
            if(!empty($updated_product["images"])){



    foreach(collect($updatableData["images"])->toArray() as  $key=>$image){

   if($key == 0){
    $updated_product->images()->delete();
   }
    $updated_product->images()->create(["file" => $image]);


    }




            }

            if(!empty($updated_product["colors"])){


                $updated_product->colors()->delete();

               foreach($updatableData["colors"] as  $color){

                   $updated_product->colors()->create($color);
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
           foreach( $updatableData["products"] as $product){
               if( $type == "fixed"){
                Variation::where([
                    "product_id" => $product["id"]
                ])
                ->increment("price",$amount);

               } else {
               $updatableVariations =  Variation::where([
                    "product_id" => $product["id"]
                ])->get();

foreach($updatableVariations as $updatableVariation){
    $increaseAmount = $updatableVariation->price * ($amount / 100);
    $finalAmount = $updatableVariation->price + $increaseAmount;
    $updatableVariation->price =  $finalAmount;
    $updatableVariation->save();

}



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
            foreach($request["products"] as $product){
                Product::where(["id" => $product["id"]])->delete();
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
            'products.is_featured',

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
            $query = Product::with("variations","images","colors")
            // ->join('variations', 'products.id', '=', 'variations.product_id')

            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id');

            // ->leftJoin('product_variations', 'variations.product_variation_id', '=', 'product_variations.id');

            if(!empty($request->category)){
                $query
                ->where([
               "c.id" => $request->category
                ]);
            }


        //     $query
        //     ->where([
        //    "products.status" => "active"
        //     ]);

        $products =  $query
        ->select(
            'products.id',
            'products.name',
            'products.type',
            'c.name as category',
            'products.sku',
            'products.image',
            'products.status',
            'products.is_featured',
            "products.style_id"

        )
        ->orderByDesc("id")
        ->paginate($perPage);




    return response()->json([
        "products" => $products
    ], 200);


        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getProductPaginationServiceClient($perPage,$request)
    {
        try{
            // $products =   Variation::with("product.category")->paginate(10);
            $query = Product::with("variations","images","colors.color")
            // ->join('variations', 'products.id', '=', 'variations.product_id')

            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')
            ->leftJoin('styles as s', 'products.style_id', '=', 's.id')
            ->leftJoin('product_colors as co', 'products.id', '=', 'co.product_id');
            // ->leftJoin('product_variations', 'variations.product_variation_id', '=', 'product_variations.id');

            if(!empty($request->category)){
                $query
                ->where([
               "c.id" => $request->category
                ]);
            }
            if(!empty($request->style)){
                $query
                ->where([
               "s.id" => $request->style
                ]);
            }
            if(!empty($request->color)){
                $query
                ->where([
               "co.color_id" => $request->color
                ]);
            }



            $query
            ->where([
           "products.status" => "active"
            ])
            ->distinct("products.id");

        $products =  $query
        ->select(
            'products.id',
            'products.name',
            'products.type',
            'c.name as category',
            'products.sku',
            'products.image',
            'products.status',
            'products.is_featured',
            "products.style_id"

        )
        ->orderByDesc("id")
        ->paginate($perPage);




    return response()->json([
        "products" => $products
    ], 200);


        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getFeaturedProductServiceClient($request)
    {
        try{
            // $products =   Variation::with("product.category")->paginate(10);
            $query = Product::with("variations","images","colors.color")
            // ->join('variations', 'products.id', '=', 'variations.product_id')

            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')
            ->leftJoin('styles as s', 'products.style_id', '=', 's.id')
            ->leftJoin('product_colors as co', 'products.id', '=', 'co.product_id');
            // ->leftJoin('product_variations', 'variations.product_variation_id', '=', 'product_variations.id');

            // if(!empty($request->category)){
            //     $query
            //     ->where([
            //    "c.id" => $request->category
            //     ]);
            // }
            // if(!empty($request->style)){
            //     $query
            //     ->where([
            //    "s.id" => $request->style
            //     ]);
            // }
            // if(!empty($request->color)){
            //     $query
            //     ->where([
            //    "co.color_id" => $request->color
            //     ]);
            // }



            $query
            ->where([
           "products.status" => "active",
           "products.is_featured" => 1
            ])

            ->distinct("products.id");

        $products =  $query
        ->select(
            'products.id',
            'products.name',
            'products.type',
            'c.name as category',
            'products.sku',
            'products.image',
            'products.status',
            'products.is_featured',
            "products.style_id"

        )
        ->orderByDesc("id")
        ->take(5)
        ->get();




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
            $product =   Product::with("product_variations.variations", "variations","colors.color")->where([
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
    public function getProductByIdServiceClient($request, $id)
    {
        try{
            $product =   Product::with("product_variations.variations", "variations","colors.color","category","images","style")->where([
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
            Product::where(["id" => $request["id"]])->delete();
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
