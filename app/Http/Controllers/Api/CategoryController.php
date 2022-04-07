<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use CategoryService;
    public function createCategory(CategoryRequest $request)
    {
        return $this->createCategoryService($request);
    }

    public function updateCategory(CategoryUpdateRequest $request)
    {
        return $this->updateCategoryService($request);
    }

    public function getCategory(Request $request)
    {
        return $this->getCategorysService($request);
    }
    public function getAllCategory(Request $request)
    {
        return $this->getAllCategorysService($request);
    }

    public function getCategoryById($id,Request $request)
    {

        return $this->getCategoryByIdService($id,$request);
    }

    public function searchCategory($term,Request $request)
    {
        return $this->searchCategoryService($term,$request);
    }
    public function deleteCategory($id,Request $request)
    {
        return $this->deleteCategoryService($id,$request);
    }
}
