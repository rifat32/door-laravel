<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostOfficeRequest;
use App\Http\Services\PostOfficeService;
use Illuminate\Http\Request;

class PostOfficeController extends Controller
{
    use PostOfficeService;
    public function createPostOffice(PostOfficeRequest $request)
    {
        return $this->createPostOfficeService($request);
    }

    public function updatePostOffice(Request $request)
    {
        return $this->updatePostOfficeService($request);
    }

    public function getPostOffice(Request $request)
    {
        return $this->getPostOfficeService($request);
    }
    public function getPostOfficeById($id,Request $request)
    {

        return $this->getPostOfficeByIdService($id,$request);
    }

    public function searchPostOffice($term,Request $request)
    {
        return $this->searchPostOfficeService($term,$request);
    }
    public function deletePostOffice($id,Request $request)
    {
        return $this->deletePostOfficeService($id,$request);
    }
}
