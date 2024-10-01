<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $holidays = $this->getHolidays($country,$year,$start,$end);

        return response()->json([
            'startDate' => $start,
            'endDate' => $end,
            'holiDays' => $holidays
        ]);
    }

    public function getDates($country,$year){
        if($country==='IE'){
            $startDate = Carbon::createFromDate($year, 1, 1);
            $endDate = Carbon::createFromDate($year, 12, 31);
        }elseif($country==='GB'){
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

    public function getHolidays($country,$year,$start,$end){
        $apiKey = '0239240d-515a-4be1-8ca4-79d54dca8d60';
        $url = "https://holidayapi.com/v1/holidays?pretty&key=$apiKey&country=$country&year=$year";
        $startDate = strtotime($start);
        $endDate = strtotime($end);
        
        $holidays = [];

        try {
            $response = file_get_contents($url);
            if ($response !== false) {
                // Decode the JSON response
                $data = json_decode($response, true);
            
                if (isset($data['holidays'])) {
                    $holidays = $data['holidays'];
                }
            }
        } catch (\Exception $e) {
            // Log the error message (optional)
            Log::error("Failed to fetch holidays: " . $e->getMessage());
        }

        if($country==='GB'){
            $nextyear = $year+1;
            $nexturl = "https://holidayapi.com/v1/holidays?pretty&key=$apiKey&country=$country&year=$nextyear";

            try {
                $nextresponse = @file_get_contents($nexturl);

                if ($nextresponse !== false) {
                    $nextdata = json_decode($nextresponse, true);
    
                    if (!empty($nextdata) && isset($nextdata['holidays'])) {
                        $holidays = array_merge($holidays, $nextdata['holidays']);
                    }
                }
            } catch (\Exception $e) {
                // Log the error message (optional)
                Log::error("Failed to fetch next year's holidays: " . $e->getMessage());
            }
        }
        $filteredHolidays = array_filter($holidays, function($holiday) use ($startDate, $endDate) {
            $holidayDate = strtotime($holiday['date']);
            return $holidayDate >= $startDate && $holidayDate <= $endDate;
        });

        return  $filteredHolidays;
    }
}
