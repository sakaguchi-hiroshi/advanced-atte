<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_Time;
use App\Models\Break_Time;
use Carbon\Carbon;


class BreakTimeController extends Controller
{
    public function create(Request $request)
    {
        $latestWorkTime = Work_Time::where('user_id', $request->user_id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $latestWorkTime->id)->latest()->first();
        if($latestWorkTime->start_time && !($latestWorkTime->end_time)) {
            if(empty($latestBreakTime->break_in)) {
                $data = [
                    'break_in' => Carbon::now(),
                ];
                Break_Time::create([
                    'user_id' => $latestWorkTime->user_id,
                    'work__time_id' => $latestWorkTime->id,
                    'break_in' => $data['break_in'],
                ]);
                return redirect()->back();
            }elseif($latestBreakTime->break_in && $latestBreakTime->break_out) {
                $data = [
                    'break_in' => Carbon::now(),
                ];
                Break_Time::create([
                    'user_id' => $latestWorkTime->user_id,
                    'work__time_id' => $latestWorkTime->id,
                    'break_in' => $data['break_in'],
                ]);
                return redirect()->back();
            }
        }
        return redirect()->back()->with('message', '休憩開始が実行できません');
    }
    
    public function update(Request $request)
    {
        $latestWorkTime = Work_Time::where('user_id', $request->user_id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $latestWorkTime->id)->latest()->first();
        $latestWorkStart = new Carbon($latestWorkTime->start_time);
        $oldDay = $latestWorkStart->copy()->startOfDay();
        $addDay = $oldDay->copy()->addDay();
        $today = Carbon::today();
        $now = Carbon::now();

        if($latestBreakTime->break_in && empty($latestBreakTime->break_out)) {
            if($addDay == $today){
                $workStart = new Carbon($latestWorkTime->start_time);
                $endOfDay = new Carbon($oldDay->copy()->endOfDay());
                
                $latestBreakTime->update([
                'work__time_id' => $latestBreakTime->work__time_id,
                'break_out' => $endOfDay,
                ]);
                $breakTimes = Break_Time::where('work__time_id', $latestWorkTime->id)->get();
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
                $items = [
                        'date' => $today,
                        'start_time' => $today,
                ];
                $this->validate($request, $items, Work_Time::$rules);
                Work_Time::create([
                    'user_id' => $request->user_id,
                    'date' => $items['date'],
                    'start_time' => $items['start_time'],
                ]);

                $moreLatestWorkTime = Work_Time::where('user_id', $request->user_id)->latest()->first();
                Break_Time::create([
                    'user_id' => $moreLatestWorkTime->user_id,
                    'work__time_id' => $moreLatestWorkTime->id,
                    'break_in' => $today,
                    'break_out' => $now,
                ]);
                return redirect()->back();
            }else {
                $latestBreakTime->update([
                'work__time_id' => $latestBreakTime->work__time_id,
                'break_out' => Carbon::now(),
                ]);
                return redirect()->back();
            }
        }else {
            return redirect()->back()->with('message', '休憩終了が実行できません');
        }
    }
}


// $latestBreakTime->update([
//                 'work__time_id' => $latestBreakTime->work__time_id,
//                 'break_out' => Carbon::now(),
//             ]);
//             return redirect()->back();