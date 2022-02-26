<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use AppointmentService;
    public function createAppointment(AppointmentRequest $request)
    {
        return $this->createAppointmentService($request);
    }
    public function updateAppointment(AppointmentRequest $request)
    {

        return $this->updateAppointmentService($request);
    }
    public function deleteAppointment(Request $request)
    {
        return $this->deleteAppointmentService($request);
    }

    public function getAppointments(Request $request)
    {

        return $this->getAppointmentsService($request);
    }
}
