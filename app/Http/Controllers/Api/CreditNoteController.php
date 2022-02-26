<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreditNoteRequest;
use App\Models\Balance;
use App\Models\CreditNote;
use Illuminate\Http\Request;
use App\Http\Services\CreditNoteServices;

class CreditNoteController extends Controller
{
    use CreditNoteServices;
    public function createCreditNote(CreditNoteRequest $request)
    {
        return $this->createCreditNoteService($request);
    }
    public function updateCreditNote(CreditNoteRequest $request)
    {
        return $this->updateCreditNoteService($request);
    }

    public function getCreditNotes(Request $request)
    {
        return $this->getCreditNotesService($request);
    }
    public function approveCreditNote(Request $request)
    {
        return $this->approveCreditNoteService($request);
    }
    public function deleteCreditNote(Request $request, $id)
    {
        return $this->deleteCreditNoteService($request, $id);
    }
}
