<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\Citizen;
use App\Models\Member;
use Exception;

trait CitizenService
{
    use ErrorUtil;
    public function createCitizenService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/citizen'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $insertableData = $request->validated();
            $insertableData["image"] = "image";
            $citizen =   Citizen::create($insertableData);
            $insertedMembersArray = [];
            foreach ($insertableData["members"] as $member) {
                $member["image"] = "image2";
                $member["citizen_id"] = $citizen->id;
                $insertedMember =  Member::create($member);
            }
            array_push($insertedMembersArray, $insertedMember);
            $data['data'] = $citizen;
            $data['data']["members"] = $insertedMembersArray;
            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateCitizenService($request)
    {

        try {
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/citizen'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->validated();
            $updatableData["image"] = "image";

            foreach ($updatableData["members"] as $member) {
                $member["image"] = "image";
                $member["citizen_id"] = $updatableData["id"];
                $updatedMember =  Member::upsert(
                    [
                        collect($member)->only([
                            "id",
                            "citizen_id",
                            "name",
                            "father_name",
                            "mother_name",
                            "village_name",
                            "post_office",
                            "upazila",
                            "district",
                            "nid",
                            "image"

                        ])
                            ->toArray()
                    ],
                    ['id', 'citizen_id'],
                    collect($member)->only([
                        "citizen_id",
                        "name",
                        "father_name",
                        "mother_name",
                        "village_name",
                        "post_office",
                        "upazila",
                        "district",
                        "nid",
                        "image"
                    ])
                        ->toArray()
                );
            }



            $data['data'] = tap(Citizen::where(["id" =>  $updatableData["id"]]))->update(
                collect($updatableData)->only([
                    "union_id",
                    "ward_id",
                    "village_id",
                    "post_office_id",
                    "upazila_id",
                    "district_id",

                    "holding_no",
                    "thana_head_name",
                    "thana_head_religion",
                    "thana_head_gender",
                    "thana_head_occupation",
                    "mobile",
                    "guardian",
                    "c_mother_name",
                    "nid_no",
                    "is_tubewell",
                    "latrin_type",
                    "type_of_living",
                    "type_of_organization",
                    "previous_due",
                    "tax_amount",
                    "male",
                    "female",
                    "annual_price",
                    "gov_advantage",
                    "image",
                    "current_year",
                    "raw_house",
                    "half_building_house",
                    "building_house"
                ])
                    ->toArray()
            )

                ->with("union", "ward", "village", "postOffice", "upazila", "district", "member")
                ->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getCitizenService($request)
    {

        try {
            $data['data'] =   Citizen::with("union", "ward", "village", "postOffice", "upazila", "district")->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getCitizenByIdService($id, $request)
    {

        try {
            $data['data'] =   Citizen::with("union", "ward", "village", "postOffice", "upazila", "district")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchCitizenService($term, $request)
    {
        try {
            $data['data'] =   Citizen::with("union", "ward", "village", "postOffice", "upazila", "district")
                ->where("holding_no", "like", "%" . $term . "%")
                ->orWhere("thana_head_name", "like", "%" . $term . "%")
                ->orWhere("thana_head_religion", "like", "%" . $term . "%")
                ->orWhere("thana_head_occupation", "like", "%" . $term . "%")
                ->orWhere("mobile", "like", "%" . $term . "%")
                ->orWhere("c_mother_name", "like", "%" . $term . "%")
                ->orWhere("guardian", "like", "%" . $term . "%")
                ->orWhere("nid_no", "like", "%" . $term . "%")
                ->get();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function deleteCitizenService($id, $request)
    {
        try {
            Citizen::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
}
