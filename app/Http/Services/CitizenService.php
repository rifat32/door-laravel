<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\Citizen;
use Exception;

trait CitizenService
{
    use ErrorUtil;
    public function createCitizenService($request)
    {

        try{
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('img/citizen'), $imageName);
            $imageName = "img/restaurant/" . $imageName;
            $insertableData = [
                "union_id"=>$request->union_id,
        "ward_id"=>$request->ward_id,
        "village_id"=>$request->village_id,
        "post_office_id"=>$request->post_office_id,
        "upazila_id"=>$request->upazila_id,
        "district_id"=>$request->district_id,

        "holding_no"=>$request->holding_no,
        "thana_head_name"=>$request->thana_head_name,
        "thana_head_religion"=>$request->thana_head_religion,
        "thana_head_gender"=>$request->thana_head_gender,
        "thana_head_occupation"=>$request->thana_head_occupation,
        "mobile"=>$request->mobile,
        "guardian"=>$request->guardian,
        "c_mother_name"=>$request->c_mother_name,
        "nid_no"=>$request->nid_no,
        "is_tubewell"=>$request->is_tubewell,
        "latrin_type"=>$request->latrin_type,
        "type_of_living"=>$request->type_of_living,
        "type_of_organization"=>$request->type_of_organization,
        "previous_due"=>$request->previous_due,
        "tax_amount"=>$request->tax_amount,
        "male"=>$request->male,
        "female"=>$request->female,
        "annual_price"=>$request->annual_price,
        "gov_advantage"=>$request->gov_advantage,
        "image"=>$imageName,
        "current_year"=>$request->current_year,
        "raw_house"=>$request->raw_house,
        "half_building_house"=>$request->half_building_house,
        "building_house"=>$request->building_house
            ];
        
            $data['data'] =   Citizen::create($insertableData);

            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateCitizenService($request)
    {

        try{
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('img/citizen'), $imageName);
            $imageName = "img/restaurant/" . $imageName;
            $insertableData = [
                "union_id"=>$request->union_id,
        "ward_id"=>$request->ward_id,
        "village_id"=>$request->village_id,
        "post_office_id"=>$request->post_office_id,
        "upazila_id"=>$request->upazila_id,
        "district_id"=>$request->district_id,

        "holding_no"=>$request->holding_no,
        "thana_head_name"=>$request->thana_head_name,
        "thana_head_religion"=>$request->thana_head_religion,
        "thana_head_gender"=>$request->thana_head_gender,
        "thana_head_occupation"=>$request->thana_head_occupation,
        "mobile"=>$request->mobile,
        "guardian"=>$request->guardian,
        "c_mother_name"=>$request->c_mother_name,
        "nid_no"=>$request->nid_no,
        "is_tubewell"=>$request->is_tubewell,
        "latrin_type"=>$request->latrin_type,
        "type_of_living"=>$request->type_of_living,
        "type_of_organization"=>$request->type_of_organization,
        "previous_due"=>$request->previous_due,
        "tax_amount"=>$request->tax_amount,
        "male"=>$request->male,
        "female"=>$request->female,
        "annual_price"=>$request->annual_price,
        "gov_advantage"=>$request->gov_advantage,
        "image"=>$imageName,
        "current_year"=>$request->current_year,
        "raw_house"=>$request->raw_house,
        "half_building_house"=>$request->half_building_house,
        "building_house"=>$request->building_house
            ];
            $data['data'] = tap(Citizen::where(["id" =>  $request["id"]]))->update(
                $insertableData
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
            $data['data'] =   Citizen::with("union","ward","village","postOffice","upazila","district")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getCitizenByIdService($id,$request)
    {

        try{
            $data['data'] =   Citizen::with("union","ward","village","postOffice","upazila","district")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchCitizenService($term,$request)
    {
        try{
            $data['data'] =   Citizen::with("union","ward","village","postOffice","upazila","district")
        ->where("holding_no","like","%".$term."%")
        ->orWhere("thana_head_name","like","%".$term."%")
        ->orWhere("thana_head_religion","like","%".$term."%")
        ->orWhere("thana_head_occupation","like","%".$term."%")
        ->orWhere("mobile","like","%".$term."%")
        ->orWhere("c_mother_name","like","%".$term."%")
        ->orWhere("guardian","like","%".$term."%")
        ->orWhere("nid_no","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteCitizenService($id,$request)
    {
        try{
            Citizen::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
