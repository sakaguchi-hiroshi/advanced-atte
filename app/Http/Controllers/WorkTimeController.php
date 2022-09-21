<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_Time;
use App\Models\Break_Time;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkTimeController extends Controller
{
    public function index()
    {
        $authuser = Auth::user();
        return view('stamp', ['authuser' => $authuser]);
    }

    public function create()
    {
        $user = Auth::user();
        $latestWorkTime = Work_Time::where('user_id', $user->id)->latest()->first();
        $oldDay = '';
        $today = Carbon::today();

        if($latestWorkTime) {
            $startTime = new Carbon($latestWorkTime->start_time);
            $oldDay = $startTime->copy()->startOfDay();
        }

        if(($oldDay == $today) && (empty($latestWorkTime->end_time))) {
            return redirect()->back()->with('message', '出勤打刻済みです');
        }

        Work_Time::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => Carbon::now(),
        ]);
        return redirect('/work/stamp');
    }
    
    public function update()
    {
        $user = Auth::user();
        $latestWorkTime = Work_Time::where('user_id', $user->id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $latestWorkTime->id)->latest()->first();
        $breakTimes = Break_Time::where('work__time_id', $latestWorkTime->id)->get();
        $latestWorkStart = new Carbon($latestWorkTime->start_time);
        $oldDay = $latestWorkStart->copy()->startOfDay();
        $addDay = $oldDay->copy()->addDay();
        $today = Carbon::today();
        $now = Carbon::now();

        if($latestWorkTime) {
            if(empty($latestWorkTime->end_time)) {
                if(!$latestBreakTime && $addDay == $today){
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $endOfDay = new Carbon($oldDay->copy()->endOfDay());
                    $diffStaySeconds = $workStart->copy()->diffInSeconds($endOfDay);
                    $totalHoursWorked = new Carbon($oldDay);
                    $totalHoursWorked->second = $diffStaySeconds;

                    $latestWorkTime->update([
                        'end_time' => $endOfDay,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $oldDay,
                    ]);

                    $todaysStaySeconds = $today->diffInSeconds($now);
                    $todaysTotalHoursWorked = new Carbon($today);
                    $todaysTotalHoursWorked->second = $todaysStaySeconds;

                    Work_Time::create([
                        'user_id' => $user->id,
                        'date' => $today,
                        'start_time' => $today,
                        'end_time' => $now,
                        'total_hours_worked' => $todaysTotalHoursWorked,
                        'total_break_time' => $today,
                    ]);
                    return redirect('/work/stamp');
                }elseif (!$latestBreakTime) {
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $diffStaySeconds = $workStart->diffInSeconds($now);
                    $totalHoursWorked = new Carbon($today);
                    $totalHoursWorked->second = $diffStaySeconds;

                    $latestWorkTime->update([
                        'end_time' => $now,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $today,
                    ]);
                    return redirect('/work/stamp');
                }elseif ($latestBreakTime->break_in && !($latestBreakTime->break_out)){
                    return redirect()->back()->with('message', '休憩終了が打刻されていません');
                }elseif ($addDay == $today) {
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $endOfDay = new Carbon($oldDay->copy()->endOfDay());
                    $diffStaySeconds = $workStart->diffInSeconds($endOfDay);

                    foreach($breakTimes as $breakTime){
                        $breakStart = new Carbon($breakTime->break_in);
                        $breakEnd = new Carbon($breakTime->break_out);
                        $diffBreakSeconds[] = $breakStart->diffInSeconds($breakEnd);
                    }

                    $totalBreakSeconds = array_sum($diffBreakSeconds);
                    $workTimeSeconds = $diffStaySeconds - $totalBreakSeconds;
                    $totalHoursWorked = new Carbon($oldDay);
                    $totalHoursWorked->second = $workTimeSeconds;
                    $totalBreakTime = new Carbon($oldDay);
                    $totalBreakTime->second = $totalBreakSeconds;

                    $latestWorkTime->update([
                        'end_time' => $endOfDay,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $totalBreakTime,
                    ]);

                    $todaysStaySeconds = $today->diffInSeconds($now);
                    $todaysTotalHoursWorked = new Carbon($today);
                    $todaysTotalHoursWorked->second = $todaysStaySeconds;

                    Work_Time::create([
                        'user_id' => $user->id,
                        'date' => $today,
                        'start_time' => $today,
                        'end_time' => $now,
                        'total_hours_worked' => $todaysTotalHoursWorked,
                        'total_break_time' => $today,
                    ]);
                    return redirect('/work/stamp');
                }else {
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $diffStaySeconds = $workStart->diffInSeconds($now);
                    
                    foreach($breakTimes as $breakTime){
                        $breakStart = new Carbon($breakTime->break_in);
                        $breakEnd = new Carbon($breakTime->break_out);
                        $diffBreakSeconds[] = $breakStart->diffInSeconds($breakEnd);
                    }

                    $totalBreakSeconds = array_sum($diffBreakSeconds);
                    $workTimeSeconds = $diffStaySeconds - $totalBreakSeconds;
                    $totalHoursWorked = new Carbon($today);
                    $totalHoursWorked->second = $workTimeSeconds;
                    $totalBreakTime = new Carbon($today);
                    $totalBreakTime->second = $totalBreakSeconds;
                    
                    $latestWorkTime->update([
                        'end_time' => $now,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $totalBreakTime,
                    ]);
                    return redirect('/work/stamp');
                }
            }else{
                return redirect()->back()->with('message','出勤打刻がされていません');
            }
        }
    }
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function __construct(){
        $this->middleware('auth');
    }
}
