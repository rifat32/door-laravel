<?php

namespace App\Http\Services;


use App\Models\LabReportTemplate;



trait ReportTemplateService
{
    public function createReportTemplateService($request)
    {
        $data['data'] =   LabReportTemplate::create($request->all());
        return response()->json($data, 201);
    }
    public function updateReportTemplateService($request)
    {
        $data['data'] = tap(LabReportTemplate::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "name",
                "template"

            )
        )->first();
        return response()->json($data, 200);
    }
    public function deleteReportTemplateService($request)
    {
        LabReportTemplate::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getReportTemplatesService($request)
    {
        $data['data'] =   LabReportTemplate::paginate(10);
        return response()->json($data, 200);
    }
    public function getAllReportTemplatesService($request)
    {
        $data['data'] =   LabReportTemplate::all();
        return response()->json($data, 200);
    }

}
