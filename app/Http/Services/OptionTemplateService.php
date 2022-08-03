<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;

use App\Models\Member;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\OptionValueTemplate;
use Exception;

trait OptionTemplateService
{
    use ErrorUtil;
    public function createOptionTemplateService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/OptionTemplate'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;

            $insertableData = $request->validated();

            $inserted_Option_template =   Option::create($insertableData);
            $insertedOptionValueTemplateArray = [];
            foreach ($insertableData["option_value_template"] as $value) {
                $value["Option_template_id"] = $inserted_Option_template->id;
                $insertedOptionValue = $inserted_Option_template->Option_value_template()->create($value);
                array_push($insertedOptionValueTemplateArray, $insertedOptionValue);
            }

            $data['data'] = $inserted_Option_template;
            $data['data']["Option_value_template"] = $insertedOptionValueTemplateArray;
            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateOptionTemplateService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/OptionTemplate'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->validated();


            foreach ($updatableData["option_value_template"] as $Option_value_template) {


                $updated_Option_value_template =  OptionValue::upsert(
                    [
                        collect($Option_value_template)->only([
                          "id",
                          "name",
                          "option_id"

                        ])
                            ->toArray()
                    ],
                    ['id', 'option_template_id'],
                    collect($Option_value_template)->only([
                        "name",
                        "option_id"
                    ])
                        ->toArray()
                );
            }



            $data['data'] = tap(Option::where(["id" =>  $updatableData["id"]]))->update(
                collect($updatableData)->only([
                    "name"
                ])
                    ->toArray()
            )

                ->with("option_value_template")
                ->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getOptionTemplateService($request)
    {

        try {
            $data['data'] =   Option::with("option_value_template")->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getAllOptionTemplateService($request)
    {

        try {
            $data['data'] =   Option::with("option_value_template")->get();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function getOptionTemplateByIdService($id, $request)
    {

        try {
            $data['data'] =   Option::with("option_value_template")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchOptionTemplateService($term, $request)
    {
        try {

            $data['data'] =   Option::with("option_value_template")
            ->leftJoin('option_values', 'options.id', '=', 'option_values.option_id')
            ->where(function($query) use ($term){
        $query->where("options.name", "like", "%" . $term . "%");
        $query->orWhere("option_values.name", "like", "%" . $term . "%");
    })
    ->select("options.id as id","options.name as name")
    ->groupBy("id","name")

                ->orderByDesc("options.id")
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function deleteOptionTemplateService($id, $request)
    {
        try {
            Option::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
}
