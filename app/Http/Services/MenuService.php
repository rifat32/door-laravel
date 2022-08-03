<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;

use App\Models\Member;
use App\Models\Menu;

use App\Models\MenuValue;
use App\Models\MenuValueTemplate;
use Exception;

trait MenuService
{
    use ErrorUtil;
    public function createMenuService($request)
    {

        try {


            $insertableData = $request->validated();

            $inserted_data =   Menu::create($insertableData);

            if($inserted_data->type == "dropdown"){
                foreach($insertableData["children"] as $child){
$inserted_data->children()->create([
    "name" => $child["name"],
    "url"      => $child["url"],
    "type"     => $child["type"],
    "is_active"      => 1,
    "parent_id" => $inserted_data->id

]);
                }


            }



            $data["data"] = $inserted_data;
            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateMenuService($request)
    {

        try {


            $updatableData = $request->validated();





            $data['data'] = tap(Menu::where(["id" =>  $updatableData["id"]]))->update(
                collect($updatableData)->only([
                    "name",
                    "url",
                    "type",
                    "is_active"
                ])
                    ->toArray()
            )

            ->first();

                if($updatableData["type"] == "dropdown"){
                    $data['data']->children()->delete();
                    foreach($updatableData["children"] as $child){
                        $data['data']->children()->create([
                            "name" => $child["name"],
                            "url"      => $child["url"],
                            "type"     => $child["type"],
                            "is_active"      => 1,
                            "parent_id" => $data['data']->id

                        ]);
                                        }
                }


            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getMenuService($request)
    {

        try {
            $data['data'] =   Menu::
            where(
                "type", "!=", "children"
            )
            ->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getAllMenuService($request)
    {

        try {
            $data['data'] =   Menu::with("children")
            ->where([
                "parent_id" => NULL,
                "is_active" => 1
            ])

            ->get();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function getMenuByIdService($id, $request)
    {

        try {
            $data['data'] =   Menu::with("children")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchMenuService($term, $request)
    {
        try {

            $data['data'] =   Menu:: where(
                "type", "!=", "children"
            )

    ->where(function($query) use ($term){
        $query->where("name", "like", "%" . $term . "%");
        $query->orWhere("type", "like", "%" . $term . "%");
        $query->orWhere("url", "like", "%" . $term . "%");
    })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function deleteMenuService($id, $request)
    {
        try {
            Menu::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
}
