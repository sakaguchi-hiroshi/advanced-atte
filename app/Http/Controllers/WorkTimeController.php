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

    public function create(Request $request)
    {
        $user = Auth::user();
        $latestWorkTime = Work_Time::where('user_id', $user->id)->latest()->first();

        $oldDay = '';

        if($latestWorkTime) {
            $startTime = new Carbon($latestWorkTime->start_time);
            $oldDay = $startTime->startOfDay();
        }

        $today = Carbon::today();

        if(($oldDay == $today) && (empty($latestWorkTime->end_time))) {
            return redirect()->back()->with('message', '出勤打刻済みです');
        }

        $date = new Carbon();
        $date = [
            'date' => Carbon::today(),
            'start_time' => Carbon::now(),
        ];
        $this->validate($request, $date, Work_Time::$rules);
        Work_Time::create([
            'user_id' => $request->user_id,
            'date' => $date['date'],
            'start_time' => $date['start_time'],
        ]);
        return redirect('/work/stamp');
    }
    
    public function update(Request $request)
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
        // dd($latestBreakTime);
        
        if($latestWorkTime) {
            if(empty($latestWorkTime->end_time)) {
                if(empty($latestBreakTime->break_in) && empty($latestBreakTime->break_out) && $addDay == $today){
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $endOfDay = new Carbon($oldDay->copy()->endOfDay());
                    $diffStayHours = $workStart->diffInHours($endOfDay);
                    $diffStayMinutes = $workStart->diffInMinutes($endOfDay);
                    $diffStaySeconds = $workStart->diffInSeconds($endOfDay);
                    $totalHoursWorked = $oldDay->copy()->setTime($diffStayHours, $diffStayMinutes, $diffStaySeconds);
                    $date = [
                        'date' => $oldDay,
                        'end_time' => $endOfDay,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $oldDay,
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    $latestWorkTime->update([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'end_time' => $date['end_time'],
                        'total_hours_worked' => $date['total_hours_worked'],
                        'total_break_time' => $date['total_break_time'],
                    ]);
                    $todaysStayHours = $today->diffInHours($now);
                    $todaysStayMinutes = $today->diffInMinutes($now);
                    $todaysStaySeconds = $today->diffInSeconds($now);

                    $todaysTotalHoursWorked = $today->copy()->setTime($todaysStayHours, $todaysStayMinutes, $todaysStaySeconds);

                    $date = [
                        'date' => $today,
                        'start_time' => $today,
                        'end_time' => $now,
                        'total_hours_worked' => $todaysTotalHoursWorked,
                        'total_break_time' => $today,
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    Work_Time::create([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'start_time' => $date['start_time'],
                        'end_time' => $date['end_time'],
                        'total_hours_worked' => $date['total_hours_worked'],
                        'total_break_time' => $date['total_break_time'],
                    ]);
                    return redirect('/work/stamp');
                }elseif (empty($latestBreakTime->break_in) && empty($latestBreakTime->break_out)) {
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $diffStayHours = $workStart->diffInHours($now);
                    $diffStayMinutes = $workStart->diffInMinutes($now);
                    $diffStaySeconds = $workStart->diffInSeconds($now);

                    $totalHoursWorked = $today->copy()->setTime($diffStayHours, $diffStayMinutes, $diffStaySeconds);

                    $date = [
                        'date' => $today,
                        'start_time' => $latestWorkTime->start_time,
                        'end_time' => $now,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $today,
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    $latestWorkTime->update([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'start_time' => $date['start_time'],
                        'end_time' => $date['end_time'],
                        'total_hours_worked' => $date['total_hours_worked'],
                        'total_break_time' => $date['total_break_time'],
                    ]);
                    return redirect('/work/stamp');
                }elseif ($latestBreakTime->break_in && !($latestBreakTime->break_out)){
                    return redirect()->back()->with('message', '休憩終了が打刻されていません');
                }elseif ($addDay == $today) {
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $endOfDay = new Carbon($oldDay->copy()->endOfDay());
                    $diffStayHours = $workStart->diffInHours($endOfDay);
                    $diffStayMinutes = $workStart->diffInMinutes($endOfDay);
                    $diffStaySeconds = $workStart->diffInSeconds($endOfDay);
                    foreach($breakTimes as $breakTime){
                        $breakStart = new Carbon($breakTime->break_in);
                        $breakEnd = new Carbon($breakTime->break_out);
                        $diffBreakHours[] = $breakStart->diffInHours($breakEnd);
                        $diffBreakMinutes[] = $breakStart->diffInMinutes($breakEnd);
                        $diffBreakSeconds[] = $breakStart->diffInSeconds($breakEnd);
                    }
                    $totalBreakHours = array_sum($diffBreakHours);
                    $totalBreakMinutes = array_sum($diffBreakMinutes);
                    $totalBreakSeconds = array_sum($diffBreakSeconds);

                    $workTimeHours = $diffStayHours - $totalBreakHours;
                    $workTimeMinutes = $diffStayMinutes - $totalBreakMinutes;
                    $workTimeSeconds = $diffStaySeconds - $totalBreakSeconds;

                    $totalHoursWorked = $oldDay->copy()->setTime($workTimeHours, $workTimeMinutes, $workTimeSeconds);
                    $totalBreakTime = $oldDay->copy()->setTime($totalBreakHours, $totalBreakMinutes, $totalBreakSeconds);
                    $date = [
                        'date' => $oldDay,
                        'end_time' => $endOfDay,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $totalBreakTime,
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    $latestWorkTime->update([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'end_time' => $date['end_time'],
                        'total_hours_worked' => $date['total_hours_worked'],
                        'total_break_time' => $date['total_break_time'],
                    ]);

                    $todaysStayHours = $today->diffInHours($now);
                    $todaysStayMinutes = $today->diffInMinutes($now);
                    $todaysStaySeconds = $today->diffInSeconds($now);

                    $todaysTotalHoursWorked = $today->copy()->setTime($todaysStayHours, $todaysStayMinutes, $todaysStaySeconds);

                    $date = [
                        'date' => $today,
                        'start_time' => $today,
                        'end_time' => $now,
                        'total_hours_worked' => $todaysTotalHoursWorked,
                        'total_break_time' => $today,
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    Work_Time::create([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'start_time' => $date['start_time'],
                        'end_time' => $date['end_time'],
                        'total_hours_worked' => $date['total_hours_worked'],
                        'total_break_time' => $date['total_break_time'],
                    ]);
                    return redirect('/work/stamp');
                }else {
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $diffStayHours = $workStart->diffInHours($now);
                    $diffStayMinutes = $workStart->diffInMinutes($now);
                    $diffStaySeconds = $workStart->diffInSeconds($now);

                    foreach($breakTimes as $breakTime){
                        $breakStart = new Carbon($breakTime->break_in);
                        $breakEnd = new Carbon($breakTime->break_out);
                        $diffBreakHours[] = $breakStart->diffInHours($breakEnd);
                        $diffBreakMinutes[] = $breakStart->diffInMinutes($breakEnd);
                        $diffBreakSeconds[] = $breakStart->diffInSeconds($breakEnd);
                    }
                    $totalBreakHours = array_sum($diffBreakHours);
                    $totalBreakMinutes = array_sum($diffBreakMinutes);
                    $totalBreakSeconds = array_sum($diffBreakSeconds);

                    $workTimeHours = $diffStayHours - $totalBreakHours;
                    $workTimeMinutes = $diffStayMinutes - $totalBreakMinutes;
                    $workTimeSeconds = $diffStaySeconds - $totalBreakSeconds;

                    $totalHoursWorked = $today->copy()->setTime($workTimeHours, $workTimeMinutes, $workTimeSeconds);
                    $totalBreakTime = $today->copy()->setTime($totalBreakHours, $totalBreakMinutes, $totalBreakSeconds);

                    $date = [
                        'date' => $today,
                        'start_time' => $latestWorkTime->start_time,
                        'end_time' => $now,
                        'total_hours_worked' => $totalHoursWorked,
                        'total_break_time' => $totalBreakTime,
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    $latestWorkTime->update([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'start_time' => $date['start_time'],
                        'end_time' => $date['end_time'],
                        'total_hours_worked' => $date['total_hours_worked'],
                        'total_break_time' => $date['total_break_time'],
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
