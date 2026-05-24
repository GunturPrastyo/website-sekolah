<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function track(Request $request)
    {
        $ip = $request->ip();
        $date = Carbon::today()->toDateString();

        // Cek apakah IP ini sudah tercatat hari ini, jika belum maka tambahkan ke database
        Visitor::firstOrCreate([
            'ip_address' => $ip,
            'visited_date' => $date,
        ], [
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['success' => true]);
    }
}