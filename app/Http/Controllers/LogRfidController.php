<?php

namespace App\Http\Controllers;

use App\Models\LogRfid;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;

class LogRfidController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rfid_id' => 'required|exists:rfids,id',
            'status' => 'required',
        ]);

        $logRfid = LogRfid::create($validatedData);
        return response()->json($logRfid, 200);
    }
}
