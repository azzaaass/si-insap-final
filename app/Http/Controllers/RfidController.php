<?php

namespace App\Http\Controllers;

use App\Models\Rfid;
use Illuminate\Http\Request;

class RfidController extends Controller
{
    public function index()
    {
        $rfids = Rfid::with('logs')->get();
        
        $data = [
            'rfids' => $rfids
        ];
        return view('admin.rfid', $data);
    }

    public function getIndex()
    {
        $rfids = Rfid::all();

        return response()->json($rfids, 200);
    }

    
}
