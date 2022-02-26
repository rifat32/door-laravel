<?php

namespace App\Http\Services;

use App\Models\Wing;

trait WingServices
{
    public function createWingServices($request)
    {
        $wing =   Wing::create($request->toArray());
        return response()->json(["wing" => $wing], 201);
    }
    public function updateWingServices($request)
    {

        $data["wing"] =   tap(Wing::where(["id" => $request->id]))->update($request->only("name"))->first();
        return response()->json($data, 200);
    }
    public function deleteWingServices($request, $id)
    {
        Wing::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }
    public function getWingsServices($request)
    {
        $wings =   Wing::paginate(10);
        return response()->json([
            "wings" => $wings
        ], 200);
    }
    public function getAllWingsServices($request)
    {
        $wings =   Wing::all();
        return response()->json([
            "wings" => $wings
        ], 200);
    }
}
