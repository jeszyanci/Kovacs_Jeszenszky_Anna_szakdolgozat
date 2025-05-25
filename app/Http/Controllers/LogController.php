<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function getLogData(Request $request) {
        $logs = DB::table('logs')
            ->select()
            ->get();

        return response()->json(['logs' => $logs]);
    }
}