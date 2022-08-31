<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work_Time;
use App\Models\Break_Time;
use Carbon\Carbon;
use DateTime;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $dt = Carbon::now();
        if($request->session()->missing('date')) {
            $year = $dt->year;
            $month = $dt->month;
            $date = new Carbon(time: "{$year}-{$month}-01");
            $addDay = ($date->copy()->endOfMonth()->isSunday()) ? 7 : 0;
            $count = 31 + $addDay + $date->dayOfWeek ;
            $date->subDay($date->dayOfWeek);
            $count = ceil($count / 7) * 7;
            $dates = [];
            
            for($i = 0; $i < $count; $i++, $date->addDay()) {
                $dates[] = $date->copy();
            }
            $params = [
                'dt' => $dt,
                'dates' => $dates,
            ];
            return view('calendar', $params);
        }else{
            $dt = new Carbon($request->session()->get('date'));
            $year = $dt->year;
            $month = $dt->month;
            $date = new Carbon(time: "{$year}-{$month}-01");
            $addDay = ($date->copy()->endOfMonth()->isSunday()) ? 7 : 0;
            $count = 31 + $addDay + $date->dayOfWeek;
            $date->subDay($date->dayOfWeek);
            $count = ceil($count / 7) * 7;
            $dates = [];
            
            for($i = 0; $i < $count; $i++, $date->addDay()) {
                $dates[] = $date->copy();
            }
            $params = [
                'dt' => $dt,
                'dates' => $dates,
            ];
            $request->session()->forget('date');
            return view('calendar', $params);
        }
    }
    
    public function selectedCalendar(Request $request) {
        session(['date' => $request->date]);
        return redirect()->back();
    }
    
    public function selectedMonthCalendar(Request $request) {
        $date = new Carbon(time: "{$request->date}-01");
        session(['date' => $date]);
        return redirect()->back();
    }

    public function show($date, Work_Time $work_time)
    {
        $dt = new Carbon(time: "{$date}");
        $attendances = $work_time->whereDate('date', $dt)->with('user', 'break_times')->get();
        foreach($attendances as $workTime){
            $startTime = $workTime->start_time;
            $endTime = $workTime->end_time;
            $objStartTime = new DateTime($startTime);
            $objEndTime = new DateTime($endTime);
            // $startTime = new Carbon($workTime->start_time);
            // $endTime = new Carbon($workTime->end_time);
            $diffWorkTime = $objStartTime->diff($objEndTime);
            
            
            foreach($workTime->break_times as $breakTime){
                $breakIn = $breakTime->break_in;
                $breakOut = $breakTime->break_out;
                $objBreakIn = new DateTime($breakIn);
                $objBreakOut = new DateTime($breakOut);
                // $breakIn = new Carbon($breakTime->break_in);
                // $breakOut = new Carbon($breakTime->break_out);
                $diffBreakTime = $objBreakIn->diff($objBreakOut);
            }
        }
        $totalWorkTimeDiff = $diffWorkTime->diff($diffBreakTime);
        dd($totalWorkTimeDiff);
        $params = [
            'date' => $dt,
            'attendances' => $attendances,
        ];
        return view('attendance', $params);
    }
    public function operationDate(Request $request)
    {
        //
    }
}
