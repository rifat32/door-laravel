<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Utils\ErrorUtil;
use App\Models\State;
use Exception;
use Illuminate\Http\Request;

class StateController extends Controller
{
    use ErrorUtil;
    public function getStateById(Request $request, $countryId) {
        try{
            $data['data'] =   State::where([
                "country_id" => $countryId
            ])
            ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
}
