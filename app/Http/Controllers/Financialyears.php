<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class Financialyears extends Controller
{
    public function index(){
        return view('financialyear');
    }

    public function result(Request $request){
        $country = $request->input('country');
        $year = $request->input('year');

        [$start,$end] = $this->getDates($country,$year);

        return response()->json([
            'startDate' => $start,
            'endDate' => $end
        ]);
    }

    public function getDates($country,$year){
        if($country==='ireland'){
            $startDate = Carbon::createFromDate($year, 1, 1);
            $endDate = Carbon::createFromDate($year, 12, 31);
        }elseif($country==='uk'){
            $startDate = Carbon::createFromDate($year, 4, 6);
            $endDate = Carbon::createFromDate($year+1, 4, 5);
        }
        
        while ($startDate->isWeekend()) {
            $startDate->addDay(); // Move to the next day
        }

        while ($endDate->isWeekend()) {
            $endDate->subDay(); // Move to the previous day
        }

        return [$startDate->format('j F Y'),$endDate->format('j F Y')];
    }
}
