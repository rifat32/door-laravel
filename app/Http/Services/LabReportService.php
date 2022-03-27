<?php

namespace App\Http\Services;


use App\Models\LabReportTemplate;



trait LabReportService
{
    public function createLabReportService($request)
    {
        $data['data'] =   LabReportTemplate::create($request->all());
        return response()->json($data, 201);
    }
    public function updateLabReportService($request)
    {
        $data['data'] = tap(LabReportTemplate::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "name",
                "template"

            )
        )->first();
        return response()->json($data, 200);
    }
    public function deleteLabReportService($request)
    {
        LabReportTemplate::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getLabReportsService($request)
    {
        $data['data'] =   LabReportTemplate::paginate(10);
        return response()->json($data, 200);
    }


}
