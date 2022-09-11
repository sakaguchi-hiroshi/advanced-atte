<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work_Time;
use App\Models\Break_Time;
use Carbon\Carbon;

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
    
    public function show($date, Work_Time $work_time, Request $request)
    {
        if($request->session()->missing('date')){
            $dt = new Carbon(time: "{$date}");
            $attendances = $work_time->whereDate('date', $dt)->with('user')->paginate(1);
            $params = [
                'date' => $dt,
                'attendances' => $attendances,
            ];
            return view('attendance', $params);
        }else{
            $dt = new Carbon($request->session()->get('date'));
            $attendances = $work_time->whereDate('date', $dt)->with('user')->paginate(1);
            $params = [
                'date' => $dt,
                'attendances' => $attendances,
            ];
            $request->session()->forget('date');
            return view('attendance', $params);
        }
    }

    public function operationDate(Request $request)
    {
        session(['date' => $request->date]);
        return redirect()->back();
    }
}
