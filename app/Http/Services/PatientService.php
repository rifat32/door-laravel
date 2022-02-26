<?php

namespace App\Http\Services;

use App\Models\Patient;
use App\Models\Product;


trait PatientService
{
    public function createPatientService($request)
    {
        $patient =   Patient::create($request->all());
        return response()->json(["patient" => $patient], 201);
    }
    public function updatePatientService($request)
    {
        $data['data'] = tap(Patient::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "name",
                "email",
                "address",
                "phone",
                "sex",
                "birth_date",
                "blood_group"
            )
        )->first();
        return response()->json($data, 200);
    }
    public function deletePatientService($request)
    {
        Patient::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getPatientsService($request)
    {
        $patients =   Patient::paginate(10);
        return response()->json([
            "data" => $patients
        ], 200);
    }
    public function getAllPatientsService($request)
    {
        $patients =   Patient::all();
        return response()->json([
            "data" => $patients
        ], 200);
    }

}
