<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\TradeLicense;
use Exception;

trait TradeLicenseService
{
    use ErrorUtil;
    public function createTradeLicenseService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/TradeLicense'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $insertableData = $request->toArray();
            $data['data'] =   TradeLicense::create($insertableData);

            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateTradeLicenseService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/TradeLicense'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->toArray();
            $data['data'] = tap(TradeLicense::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )
            ->with("union","ward")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getTradeLicenseService($request)
    {

        try{
            $data['data'] =   TradeLicense::with("union","ward")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getTradeLicenseByIdService($id,$request)
    {

        try{
            $data['data'] =   TradeLicense::with("union","ward")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchTradeLicenseService($term,$request)
    {
        try{
            $data['data'] =   TradeLicense::with("union","ward")
        ->where("institute","like","%".$term."%")
        ->orWhere("owner","like","%".$term."%")
        ->orWhere("guadian","like","%".$term."%")
        ->orWhere("license_no","like","%".$term."%")
        ->orWhere("mobile_no","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteTradeLicenseService($id,$request)
    {
        try{
            TradeLicense::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
