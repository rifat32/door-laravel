<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Http\Utils\ProductUtil;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationTemplate;
use Illuminate\Support\Facades\DB;
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

      return      DB::transaction(function ()use(&$insertableData) {
                $inserted_product =   Product::create($insertableData);

                if(!empty($insertableData["images"])){
                    foreach($insertableData["images"] as  $image){
                        $inserted_product->images()->create(["file" => $image]);
                    }

                }

                if(!empty($insertableData["colors"])){

                    foreach($insertableData["colors"] as  $color){
                        if($color["color_id"] && $color["color_image"]){
                            $inserted_product->colors()->create($color);
                        }

                    }

                }

                if(!empty($insertableData["options"])){
                    foreach($insertableData["options"] as  $option){

                        $inserted_product->options()->create([
                            "option_id" => $option["option_id"],
                            "color_id" => $option["color_id"],
                            "is_required" => $option["is_required"],
                            "product_id" => $inserted_product->id
                            ]);
                    }

                }

                if ($insertableData["type"] == "single") {
                    $this->createSingleVariationUtil($insertableData, $inserted_product);
                }
                else if($insertableData["type"] == "panel") {
                    $insertableData["price"] = 0;
                    $this->createSingleVariationUtil($insertableData, $inserted_product);
                }

                else {

                    $this->createVariationProductUtil($insertableData, $inserted_product);

                }
                $data['data'] = $inserted_product;

                return response()->json($data, 201);
            });


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
                    "is_featured",
                    "length_lower_limit",
                    "length_upper_limit",
                    "length_is_required"
                ])
                    ->toArray()

            )
            ->first();
            if(!empty($updated_product["images"])){


        $updated_product->images()->delete();
    foreach(collect($updatableData["images"])->toArray() as  $key=>$image){
    $updated_product->images()->create(["file" => $image]);
    }

            }
            $updated_product->options()->delete();

            foreach(collect($updatableData["options"])->toArray() as  $key=>$option){


                 $updated_product->options()->create([
                    "option_id" => $option["option_id"],
                    "color_id" => $option["color_id"],
                    "is_required" => $option["is_required"],
                    "product_id"=> $updated_product->id
                ]);

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

            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')
            ->leftJoin('styles as s', 'products.style_id', '=', 's.id');
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
            's.name as style',
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
            ->leftJoin('product_colors as co', 'products.id', '=', 'co.product_id')

            ->leftJoin('colors', 'co.color_id', '=', 'colors.id');
$term = $request->term;
            if($term) {
         $query =   $query->where(function($query) use ($term){
                    $query->where("products.name", "like", "%" . $term . "%");
                    $query->orWhere("products.type", "like", "%" . $term . "%");
                    $query->orWhere("products.sku", "like", "%" . $term . "%");
                    $query->orWhere("c.name", "like", "%" . $term . "%");
                    $query->orWhere("s.name", "like", "%" . $term . "%");
                    $query->orWhere("colors.name", "like", "%" . $term . "%");
                });
            }









            // ->leftJoin('product_variations', 'variations.product_variation_id', '=', 'product_variations.id');

            if(!empty($request->category)){
                $query
                ->where([
               "c.id" => $request->category
                ]);
            }
            if(!empty($request->category_name)){
                if(!empty($request->category)){
                    $query
                    ->where([
                   "c.id" => $request->category
                    ]);
                }else {
                    $category =  Category::
                    where("name","=",$request->category_name)
                    ->first();
                    $query
                    ->where([
                   "c.id" => $category->id
                    ]);
                }

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
            "products.style_id",
            "products.slug",

        )
        ->join('variations', 'products.id', '=', 'variations.product_id')
        ;
        if(!empty($request->priceHighToLow)) {
            if($request->priceHighToLow == "default") {
                $products = $products->orderByDesc("products.id");
            }
            if($request->priceHighToLow == "priceHighToLow") {
                $products = $products->orderByDesc("variations.price");
            }
            if($request->priceHighToLow == "priceLowToHigh") {
                $products = $products->orderBy("variations.price");
            }

        } else {
            $products = $products->orderByDesc("products.id");
        }

        $products =    $products->paginate($perPage);




    return response()->json([
        "products" => $products
    ], 200);


        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getRelatedProductServiceClient($request)
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
            if(!empty($request->category_name)){
              $category =  Category::
                where("name","=",$request->category_name)
                ->first();
                $query
                ->where([
               "c.id" => $category->id
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
        ->inRandomOrder()
        ->take(9)
        ->get();




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
            "products.style_id",
            "products.slug"
        )
        ->orderByDesc("id")

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
            $product =   Product::with("product_variations.variations", "variations","colors.color","options")->where([
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
    public function getProductByIdServiceClient($request, $slug)
    {
        try{
            $product =   Product::with("product_variations.variations","product_variations.color", "variations","colors.color","category","images","style","options.option.option_value_template",
            "options.color"
            )->where([
                "slug" => $slug
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


    public function searchProductService($term, $request)
    {
        try {
            $data['products'] =   Product::with("variations","images","colors")
            ->leftJoin('categories as c', 'products.category_id', '=', 'c.id')
            ->leftJoin('styles as s', 'products.style_id', '=', 's.id')
            ->leftJoin('product_colors as co', 'products.id', '=', 'co.product_id')
            ->leftJoin('colors', 'co.color_id', '=', 'colors.id')

            ->where(function($query) use ($term){
        $query->where("products.name", "like", "%" . $term . "%");
        $query->orWhere("products.type", "like", "%" . $term . "%");
        $query->orWhere("products.sku", "like", "%" . $term . "%");
        $query->orWhere("c.name", "like", "%" . $term . "%");
        $query->orWhere("s.name", "like", "%" . $term . "%");
        $query->orWhere("colors.name", "like", "%" . $term . "%");
    })
    ->select(
        'products.id',
            'products.name',
            'products.type',
            'c.name as category',
            'products.sku',
            'products.image',
            'products.status',
            'products.is_featured',
            "products.style_id",
            "products.slug"

    )

                ->orderByDesc("products.id")
                ->paginate(12);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
}
