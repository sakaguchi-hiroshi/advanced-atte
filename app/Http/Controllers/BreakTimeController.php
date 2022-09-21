<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_Time;
use App\Models\Break_Time;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class BreakTimeController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $latestWorkTime = Work_Time::where('user_id', $user->id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $latestWorkTime->id)->latest()->first();

        if($latestWorkTime->start_time && !($latestWorkTime->end_time)) {
            if(empty($latestBreakTime->break_in)) {
                Break_Time::create([
                    'user_id' => $latestWorkTime->user_id,
                    'work__time_id' => $latestWorkTime->id,
                    'break_in' => Carbon::now(),
                ]);
                return redirect()->back();
            }elseif($latestBreakTime->break_in && $latestBreakTime->break_out) {
                Break_Time::create([
                    'user_id' => $latestWorkTime->user_id,
                    'work__time_id' => $latestWorkTime->id,
                    'break_in' => Carbon::now(),
                ]);
                return redirect()->back();
            }else{
                return redirect()->back()->with('message', '休憩開始が実行されていません');
            }
        }
        return redirect()->back()->with('message', '休憩開始が実行できません');
    }
    
    public function update()
    {
        $user = Auth::user();
        $latestWorkTime = Work_Time::where('user_id', $user->id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $latestWorkTime->id)->latest()->first();
        $latestWorkStart = new Carbon($latestWorkTime->start_time);
        $oldDay = $latestWorkStart->copy()->startOfDay();
        $addDay = $oldDay->copy()->addDay();
        $today = Carbon::today();
        $now = Carbon::now();

        if($latestBreakTime) {
            if($latestBreakTime->break_in && empty($latestBreakTime->break_out)) {
                if($addDay == $today){
                    $workStart = new Carbon($latestWorkTime->start_time);
                    $endOfDay = new Carbon($oldDay->copy()->endOfDay());

                    $latestBreakTime->update([
                        'work__time_id' => $latestWorkTime->id,
                        'break_out' => $endOfDay,
                    ]);

                    $breakTimes = Break_Time::where('work__time_id', $latestWorkTime->id)->get();
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

                    Work_Time::create([
                        'user_id' => $user->id,
                        'date' => $today,
                        'start_time' => $today,
                    ]);
                    
                    $moreLatestWorkTime = Work_Time::where('user_id', $user->id)->latest()->first();
                    Break_Time::create([
                        'user_id' => $moreLatestWorkTime->user_id,
                        'work__time_id' => $moreLatestWorkTime->id,
                        'break_in' => $today,
                        'break_out' => $now,
                    ]);
                    return redirect()->back();
                }else {
                    $latestBreakTime->update([
                        'work__time_id' => $latestWorkTime->id,
                        'break_out' => Carbon::now(),
                    ]);
                    return redirect()->back();
                }
            }else {
                return redirect()->back()->with('message', '休憩終了が実行できません');
            }
        }else {
            return redirect()->back()->with('message', '休憩終了が実行できません');
        }
    }
}
