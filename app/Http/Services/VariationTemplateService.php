<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\VariationTemplate;
use App\Models\Member;
use App\Models\VariationValueTemplate;
use Exception;

trait VariationTemplateService
{
    use ErrorUtil;
    public function createVariationTemplateService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/VariationTemplate'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;

            $insertableData = $request->validated();

            $inserted_variation_template =   VariationTemplate::create($insertableData);
            $insertedVariationValueTemplateArray = [];
            foreach ($insertableData["variation_value_template"] as $value) {
                $value["variation_template_id"] = $inserted_variation_template->id;
                $insertedVariationValue = $inserted_variation_template->variation_value_template()->create($value);
                array_push($insertedVariationValueTemplateArray, $insertedVariationValue);
            }

            $data['data'] = $inserted_variation_template;
            $data['data']["variation_value_template"] = $insertedVariationValueTemplateArray;
            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateVariationTemplateService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/VariationTemplate'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->validated();


            foreach ($updatableData["variation_value_template"] as $variation_value_template) {


                $updated_variation_value_template =  VariationValueTemplate::upsert(
                    [
                        collect($variation_value_template)->only([
                          "id",
                          "name",
                          "variation_template_id"

                        ])
                            ->toArray()
                    ],
                    ['id', 'variation_template_id'],
                    collect($variation_value_template)->only([
                        "name",
                        "variation_template_id"
                    ])
                        ->toArray()
                );
            }



            $data['data'] = tap(VariationTemplate::where(["id" =>  $updatableData["id"]]))->update(
                collect($updatableData)->only([
                    "name"
                ])
                    ->toArray()
            )

                ->with("variation_value_template")
                ->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getVariationTemplateService($request)
    {

        try {
            $data['data'] =   VariationTemplate::with("variation_value_template")->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getAllVariationTemplateService($request)
    {

        try {
            $data['data'] =   VariationTemplate::with("variation_value_template")->get();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function getVariationTemplateByIdService($id, $request)
    {

        try {
            $data['data'] =   VariationTemplate::with("variation_value_template")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchVariationTemplateService($term, $request)
    {
        try {
            $data['data'] =   VariationTemplate::with("variation_value_template")
                ->where("name", "like", "%" . $term . "%")
                ->get();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function deleteVariationTemplateService($id, $request)
    {
        try {
            VariationTemplate::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
}
