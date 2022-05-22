<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StyleRequest;
use App\Http\Requests\StyleUpdateRequest;
use App\Http\Services\StyleService;
use Illuminate\Http\Request;

class StyleController extends Controller
{
    use StyleService;
    public function createStyle(StyleRequest $request)
    {
        return $this->createStyleService($request);
    }

    public function updateStyle(StyleUpdateRequest $request)
    {
        return $this->updateStyleService($request);
    }

    public function getStyle(Request $request)
    {
        return $this->getStylesService($request);
    }
    public function getAllStyle(Request $request)
    {
        return $this->getAllStylesService($request);
    }

    public function getStyleById($id,Request $request)
    {

        return $this->getStyleByIdService($id,$request);
    }

    public function searchStyle($term,Request $request)
    {
        return $this->searchStyleService($term,$request);
    }
    public function deleteStyle($id,Request $request)
    {
        return $this->deleteStyleService($id,$request);
    }
}
