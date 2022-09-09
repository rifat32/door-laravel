<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Country;
use Exception;

trait CountryService
{
    use ErrorUtil;
    public function createCountryService($request)
    {

        try{
            $insertableData = $request->validated();
            $data['data'] =   Country::create($insertableData);
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateCountryService($request)
    {

        try{
            $updatableData = $request->validated();
            $data['data'] = tap(Country::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getCountrysService($request)
    {

        try{
            $data['data'] =   Country::paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getAllCountrysService($request)
    {

        try{
            $data['data'] =   Country::all();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getCountryByIdService($id,$request)
    {

        try{
            $data['data'] =   Country::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchCountryService($term,$request)
    {
        try {

            $data['data'] =   Country::where(function($query) use ($term){
        $query->where("name", "like", "%" . $term . "%");
    })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchExactCountryService($term,$request)
    {
        try{
            $data['data'] =   Country::
        where("name","=",$term)
        ->first();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }


    public function deleteCountryService($id,$request)
    {
        try{
            Country::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
