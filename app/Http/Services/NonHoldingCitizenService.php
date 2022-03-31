<?php

namespace App\Http\Services;


use App\Http\Utils\ErrorUtil;
use App\Models\Citizen;
use App\Models\NonHoldingCitizen;
use Exception;

trait NonHoldingCitizenService
{
    use ErrorUtil;
    public function createCitizenService($request)
    {

        try{

            $data['data'] =   NonHoldingCitizen::create($request->all());

            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateCitizenService($request)
    {

        try{

            $data['data'] = tap(NonHoldingCitizen::where(["id" =>  $request["id"]]))->update(
                $request->only(
                    "union_id",
                    "ward_id",
                    "village_id",
                    "post_office_id",
                    "upazila_id",
                    "district_id",
                    "institute_name",
                    "business_address",
                    "license_no",
                    "license_user_name",
                    "guardian",
                    "mother_name",
                    "nid",
                    "mobile",
                    "parmanent_address",
                    "type",
                    "current_year",
                    "tax_amount",
                    "previous_due",
                    "holding_no",
                )
            )
            ->with("union","ward","village","postOffice","upazila","district")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getCitizenService($request)
    {

        try{
            $data['data'] =   NonHoldingCitizen::with("union","ward","village","postOffice","upazila","district")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getCitizenByIdService($id,$request)
    {

        try{
            $data['data'] =   NonHoldingCitizen::with("union","ward","village","postOffice","upazila","district")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchCitizenService($term,$request)
    {
        try{
            $data['data'] =   NonHoldingCitizen::with("union","ward","village","postOffice","upazila","district")
        ->where("institute_name","like","%".$term."%")
        ->orWhere("business_address","like","%".$term."%")
        ->orWhere("license_no","like","%".$term."%")
        ->orWhere("license_user_name","like","%".$term."%")
        ->orWhere("guardian","like","%".$term."%")
        ->orWhere("nid","like","%".$term."%")
        ->orWhere("mobile","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteCitizenService($id,$request)
    {
        try{
            NonHoldingCitizen::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
