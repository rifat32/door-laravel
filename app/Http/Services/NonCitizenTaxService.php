<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\NonCitizenTax;
use Exception;

trait NonCitizenTaxService
{
    use ErrorUtil;
    public function createNonCitizenTaxService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/NonCitizenTax'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $insertableData = $request->toArray();
            $data['data'] =   NonCitizenTax::create($insertableData);

            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateNonCitizenTaxService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/NonCitizenTax'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->toArray();
            $data['data'] = tap(NonCitizenTax::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )
            ->with("union","noncitizen","ward")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getNonCitizenTaxService($request)
    {

        try{
            $data['data'] =   NonCitizenTax::with("union","noncitizen","ward")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getNonCitizenTaxByIdService($id,$request)
    {

        try{
            $data['data'] =   NonCitizenTax::with("union","noncitizen","ward")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchNonCitizenTaxService($term,$request)
    {
        try{
            $data['data'] =   NonCitizenTax::with("union","noncitizen","ward")
            ->leftJoin('non_holding_citizens', 'non_citizen_taxes.non_citizen_id', '=', 'non_holding_citizens.id')
            ->where(
                "non_holding_citizens.nid","like","%".$term."%"
            )
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteNonCitizenTaxService($id,$request)
    {
        try{
            NonCitizenTax::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
