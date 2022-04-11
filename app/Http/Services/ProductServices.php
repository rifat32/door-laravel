<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationTemplate;
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
            if ($insertableData["type"] == "single") {
                $inserted_product->product_variations()->create([
                    "name" => "Dummy",
                ]);
                $variation_data = [

                    "price" => $insertableData["price"],
                    'name' => 'DUMMY',
                ];
                $inserted_product->variations()->create($variation_data);
            } else {

                foreach ($insertableData["variation"] as $varation) {

                    $variationTemplate = VariationTemplate::where([
                        "id" => $varation["variation_template_id"]
                    ])
                        ->first();
                    $product_variation_data = [
                        'name' =>  $variationTemplate->name,
                        'variation_template_id' => $variationTemplate->id
                    ];
                    $product_variation =     $inserted_product->product_variations()->create($product_variation_data);
                    foreach ($varation["variation_value_template"] as $variationValue) {



                        $variation_data = [
                            'name' => $variationValue["name"],
                            "price" => $variationValue["price"],
                            "product_id" => $inserted_product->id,
                            "product_variation_id" => $product_variation->id
                        ];
                        $product_variation->variations()->create($variation_data);
                    }
                }
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
            $inserted_product =  tap(Product::where(["id" =>  $updatableData["id"]]))->update(
                collect($updatableData)->only([
                    "name",
                    "type",
                    "category_id",
                    "sku",
                    "image",
                    "description",

                ])
                    ->toArray()

            )->first();


            if ($updatableData["type"] == "single") {

                $variation_data = [
                    "price" => $updatableData["price"],
                ];
                $inserted_product->variations()->update($variation_data);
            } else {

                foreach ($updatableData["variation"] as $varation) {

                    $variationTemplate = VariationTemplate::where([
                        "id" => $varation["variation_template_id"]
                    ])
                        ->first();


                    $productVariation = ProductVariation::where([
                        "id" => $varation["id"],
                        'variation_template_id' => $variationTemplate->id
                    ])->first();
                    if ($productVariation) {
                        $productVariation->name = $variationTemplate->name;
                        $productVariation->variation_template_id = $variationTemplate->id;
                        $productVariation->save();
                    } else {
                        $product_variation_data = [
                            "id" => $varation["id"],
                            'name' =>  $variationTemplate->name,
                            'variation_template_id' => $variationTemplate->id
                        ];
                        $productVariation =     $inserted_product->product_variations()->create($product_variation_data);
                    }



                    foreach ($varation["variation_value_template"] as $variationValue) {


                        $variation = Variation::where([
                            "id" => $variationValue["id"],
                            'product_variation_id' => $productVariation->id
                        ])->first();


                        if ($variation) {

                            $variation->name = $variationValue["name"];
                            $variation->price = $variationValue["price"];
                            $variation->product_id = $inserted_product->id;
                            $variation->product_variation_id = $productVariation->id;
                            $variation->save();
                        } else {


                            $variation_data = [
                                'name' => $variationValue["name"],
                                "price" => $variationValue["price"],
                                "product_id" => $inserted_product->id,
                                "product_variation_id" => $productVariation->id
                            ];
                            $productVariation->variations()->create($variation_data);

                        }
                    }
                }
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
