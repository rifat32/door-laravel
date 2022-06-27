<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Http\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use MenuService;
    public function createMenu(MenuRequest $request)
    {
        return $this->createMenuService($request);
    }

    public function updateMenu(MenuUpdateRequest $request)
    {
        return $this->updateMenuService($request);
    }

    public function getMenu(Request $request)
    {
        return $this->getMenuService($request);
    }
    public function getAllMenu(Request $request)
    {
        return $this->getAllMenuService($request);
    }

    public function getMenuById($id,Request $request)
    {

        return $this->getMenuByIdService($id,$request);
    }

    public function searchMenu($term,Request $request)
    {
        return $this->searchMenuService($term,$request);
    }
    public function deleteMenu($id,Request $request)
    {
        return $this->deleteMenuService($id,$request);
    }
}
