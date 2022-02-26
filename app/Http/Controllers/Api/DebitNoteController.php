<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DebitNoteRequest;
use App\Models\Balance;
use App\Models\DebitNote;
use Illuminate\Http\Request;
use App\Http\Services\DebitNoteServices;

class DebitNoteController extends Controller
{

    use DebitNoteServices;

    public function createDebitNote(DebitNoteRequest $request)
    {
        return $this->createDebitNoteService($request);
    }
    public function getDebitNotes(Request $request)
    {
        return $this->getDebitNotesService($request);
    }
    public function approveDebitNote(Request $request)
    {
        return $this->approveDebitNoteService($request);
    }
}
