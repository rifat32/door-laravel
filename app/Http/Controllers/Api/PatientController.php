<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    //

    use PatientService;
    public function createPatient(PatientRequest $request)
    {
        return $this->createPatientService($request);
    }
    public function updatePatient(PatientRequest $request)
    {

        return $this->updatePatientService($request);
    }
    public function deletePatient(Request $request)
    {
        return $this->deletePatientService($request);
    }

    public function getPatients(Request $request)
    {

        return $this->getPatientsService($request);
    }
    public function getAllPatients(Request $request)
    {

        return $this->getAllPatientsService($request);
    }

}
