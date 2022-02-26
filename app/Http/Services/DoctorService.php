<?php

namespace App\Http\Services;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Product;


trait DoctorService
{
    public function createDoctorService($request)
    {
        $data['data'] =   Doctor::create($request->all());
        return response()->json($data, 201);
    }
    public function updateDoctorService($request)
    {
        $data['data'] = tap(Doctor::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "name",
                "email",
                "address",
                "phone",

            )
        )->first();
        return response()->json($data, 200);
    }
    public function deleteDoctorService($request)
    {
        Doctor::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getDoctorsService($request)
    {
        $data['data'] =   Doctor::paginate(10);
        return response()->json($data, 200);
    }
    public function getAllDoctorsService($request)
    {
        $data['data'] =   Doctor::all();
        return response()->json($data, 200);
    }

}
