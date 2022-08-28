<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work_Time;
use App\Models\Break_Time;
use Carbon\Carbon;
use App\Http\Controllers\AttendanceController;

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
            $date->subDay($date->dayOfWeek);
            $count = 31 + $addDay + $date->dayOfWeek;
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
            $date->subDay($date->dayOfWeek);
            $count = 31 + $addDay + $date->dayOfWeek;
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
        $attendance = $work_time->with(['break_times' => function($q){
            $q = with('user');
        }])->whereDate('date', $dt)->get();
        // $attendance = $work_time->with(['break_times' => function($q){
        //     $q = with('user');
        // }])->whereDate('date', $dt)->get();
        // $attendance = $break_time->with(['work_time' => function($q){
        //     $q = with('user');
        // }])->whereDate('date', $dt)->get();
        
        
        $params = [
            'date' => $dt,
            'attendance' => $attendance,
        ];
        return view('attendance', $params);
    }
    public function operationDate(Request $request)
    {
        //
    }
}
