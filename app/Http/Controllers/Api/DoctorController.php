<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRequest;
use App\Http\Services\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    use DoctorService;
    public function createDoctor(DoctorRequest $request)
    {
        return $this->createDoctorService($request);
    }
    public function updateDoctor(DoctorRequest $request)
    {

        return $this->updateDoctorService($request);
    }
    public function deleteDoctor(Request $request)
    {
        return $this->deleteDoctorService($request);
    }

    public function getDoctors(Request $request)
    {

        return $this->getDoctorsService($request);
    }
    public function getAllDoctors(Request $request)
    {

        return $this->getAllDoctorsService($request);
    }

}
