<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\CitizenTax;
use Exception;

trait CitizenTaxService
{
    use ErrorUtil;
    public function createCitizenTaxService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/CitizenTax'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $insertableData = $request->toArray();
            $data['data'] =   CitizenTax::create($insertableData);

            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateCitizenTaxService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/CitizenTax'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->toArray();
            $data['data'] = tap(CitizenTax::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )
            ->with("union","citizen","ward")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getCitizenTaxService($request)
    {

        try{
            $data['data'] =   CitizenTax::with("union","citizen","ward")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getCitizenTaxByIdService($id,$request)
    {

        try{
            $data['data'] =   CitizenTax::with("union","citizen","ward")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchCitizenTaxService($term,$request)
    {
        try{
            $data['data'] =   CitizenTax::with("union","citizen","ward")
            ->leftJoin('citizens', 'citizen_taxes.citizen_id', '=', 'citizens.id')
            ->where(
                "citizens.mobile","like","%".$term."%"
            )
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteCitizenTaxService($id,$request)
    {
        try{
            CitizenTax::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
