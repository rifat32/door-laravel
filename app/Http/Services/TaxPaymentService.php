<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\TaxPayment;
use Exception;

trait TaxPaymentService
{
    use ErrorUtil;
    public function createTaxPaymentService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/TaxPayment'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $insertableData = $request->toArray();
            $data['data'] =   TaxPayment::create($insertableData);

            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateTaxPaymentService($request)
    {

        try{
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('img/TaxPayment'), $imageName);
            // $imageName = "img/restaurant/" . $imageName;
            $updatableData = $request->toArray();
            $data['data'] = tap(TaxPayment::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )
            ->with("union","citizen","method")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getTaxPaymentService($request)
    {

        try{
            $data['data'] =   TaxPayment::with("union","citizen","method")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getTaxPaymentByIdService($id,$request)
    {

        try{
            $data['data'] =   TaxPayment::with("union","citizen","method")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchTaxPaymentService($term,$request)
    {
        try{
            $data['data'] =   TaxPayment::with("union","citizen","method")
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

    public function deleteTaxPaymentService($id,$request)
    {
        try{
            TaxPayment::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
