<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\NonCitizenTaxPayment;
use Exception;

trait NonCitizenTaxPaymentService
{
    use ErrorUtil;
    public function createNonCitizenTaxPaymentService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/NonCitizenTaxPayment'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $insertableData = $request->toArray();
            $data['data'] =   NonCitizenTaxPayment::create($insertableData);

            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateNonCitizenTaxPaymentService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/NonCitizenTaxPayment'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->toArray();
            $data['data'] = tap(NonCitizenTaxPayment::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )
            ->with("union","method")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getNonCitizenTaxPaymentService($request)
    {

        try{
            $data['data'] =   NonCitizenTaxPayment::with("union","method")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getNonCitizenTaxPaymentByIdService($id,$request)
    {

        try{
            $data['data'] =   NonCitizenTaxPayment::with("union","method")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchNonCitizenTaxPaymentService($term,$request)
    {
        try{
            $data['data'] =   NonCitizenTaxPayment::with("union","method")
            ->leftJoin('citizens', 'tax_payments.citizen_id', '=', 'citizens.id')
            ->where(
                "citizens.mobile","like","%".$term."%"
            )
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteNonCitizenTaxPaymentService($id,$request)
    {
        try{
            NonCitizenTaxPayment::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
