<?php

namespace App\Http\Utils;

use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationTemplate;
use Exception;

trait ProductUtil
{
    // this function do all the task and returns transaction id or -1
    public function createSingleVariationUtil($insertableData,$inserted_product)
    {
        $inserted_product->product_variations()->create([
            "name" => "Dummy",
        ]);
        $variation_data = [

            "price" => $insertableData["price"],
            "qty" => $insertableData["qty"],
            'name' => 'DUMMY',
        ];
        $inserted_product->variations()->create($variation_data);

    }
    public function createVariationProductUtil($insertableData,$inserted_product)
    {
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
                    "qty" => $variationValue["qty"],
                    "product_id" => $inserted_product->id,
                    "product_variation_id" => $product_variation->id
                ];
                $product_variation->variations()->create($variation_data);
            }
        }

    }
    public function updateSingleVariationUtil($updatableData,$updated_product)
    {
        $variation_data = [
            "price" => $updatableData["price"],
            "qty" => $updatableData["qty"],
        ];
        $updated_product->variations()->update($variation_data);

    }
    public function updateVariationProductUtil($updatableData,$updated_product)
    {
        foreach ($updatableData["variation"] as $varation) {

            $variationTemplate = VariationTemplate::where([
                "id" => $varation["variation_template_id"]
            ])
                ->first();
                // $product_variation_data = [
                //     "id" => $varation["id"],
                //     'name' =>  $variationTemplate->name,
                //     'variation_template_id' => $variationTemplate->id,
                //     'product_id' => $updated_product->id,
                // ];
                // $productVariation =    ProductVariation::upsert(
                //     [
                //        $product_variation_data
                //     ],
                //     ['id'],
                //   [
                //     "variation_template_id",
                //     "name"
                //   ]
                // );

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
                $productVariation =     $updated_product->product_variations()->create($product_variation_data);
            }


            foreach ($varation["variation_value_template"] as $variationValue) {

                //     $variation_data = [
                //         'name' => $variationValue["name"],
                //         "price" => $variationValue["price"],
                //         "product_id" => $updated_product->id,
                //         "product_variation_id" => $productVariation->id
                //     ];
                // $variation =     Variation::upsert(
                //     [
                //        $variation_data
                //     ],
                //     ['id'],
                //   $variation_data
                // );


                $variation = Variation::where([
                    "id" => $variationValue["id"],
                    'product_variation_id' => $productVariation->id
                ])->first();


                if ($variation) {

                    $variation->name = $variationValue["name"];
                    $variation->price = $variationValue["price"];
                    $variation->qty = $variationValue["qty"];
                    $variation->product_id = $updated_product->id;
                    $variation->product_variation_id = $productVariation->id;
                    $variation->save();
                } else {


                    $variation_data = [
                        'name' => $variationValue["name"],
                        "price" => $variationValue["price"],
                        "qty" => $variationValue["qty"],
                        "product_id" => $updated_product->id,
                        "product_variation_id" => $productVariation->id
                    ];
                    $productVariation->variations()->create($variation_data);

                }
            }
        }

    }
}
