<?php

namespace App\Http\Utils;

use Exception;

trait ErrorUtil
{
    // this function do all the task and returns transaction id or -1
    public function sendError(Exception $e,$statusCode)
    {
        $data["message"] = $e->getMessage();
        if(env("APP_DEBUG") == false){
            $data["message"] = "something went wrong";
        }
        $data["StatucCode"] = $statusCode;
        $data["line"] = $e->getLine();
        $data["file"] = $e->getFile();
return response()->json($data,$statusCode);

    }
}
