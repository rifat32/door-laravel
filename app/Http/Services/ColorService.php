<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\Color;
use App\Models\ProductColor;
use App\Models\VariationValueTemplate;
use Exception;

trait ColorService
{
    use ErrorUtil;
    public function createColorService($request)
    {

        try {


            $insertableData = $request->validated();

            $data["color"] =   Color::create($insertableData);

            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateColorService($request)
    {

        try {

            $updatableData = $request->validated();





            $data['data'] = tap(Color::where(["id" =>  $request["id"]]))->update(
                collect($updatableData)->only([
                    "name",
                    "code"
                ])
                    ->toArray()
            )
                ->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getColorService($request)
    {

        try {
            $data['data'] =   Color::paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getAllColorService($request)
    {

        try {
            $data['data'] =   Color::get();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function getColorByIdService($id, $request)
    {

        try {
            $data['data'] =   Color::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchColorService($term, $request)
    {
        try {

            $data['data'] =   Color::where(function($query) use ($term){
        $query->where("name", "like", "%" . $term . "%");
        $query->orWhere("code", "like", "%" . $term . "%");
    })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function deleteColorService($id, $request)
    {
        try {
            $colorExists =      ProductColor::where([
                "color_id"=>$id
            ])->first();
            if(!$colorExists) {
                Color::where(["id" => $id])->delete();
                return response()->json(["ok" => true], 200);
            }

            return response()->json(["ok" => true], 500);

        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
}
