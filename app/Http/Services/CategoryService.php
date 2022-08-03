<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Category;
use Exception;

trait CategoryService
{
    use ErrorUtil;
    public function createCategoryService($request)
    {

        try{
            $insertableData = $request->validated();
            $data['data'] =   Category::create($insertableData);
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateCategoryService($request)
    {

        try{
            $updatableData = $request->validated();
            $data['data'] = tap(Category::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getCategorysService($request)
    {

        try{
            $data['data'] =   Category::paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getAllCategorysService($request)
    {

        try{
            $data['data'] =   Category::all();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getCategoryByIdService($id,$request)
    {

        try{
            $data['data'] =   Category::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchCategoryService($term,$request)
    {
        try {

            $data['data'] =   Category::where(function($query) use ($term){
        $query->where("name", "like", "%" . $term . "%");
    })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchExactCategoryService($term,$request)
    {
        try{
            $data['data'] =   Category::
        where("name","=",$term)
        ->first();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }


    public function deleteCategoryService($id,$request)
    {
        try{
            Category::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
