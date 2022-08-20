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
        $oldTimeIn = Work_Time::where('user_id', $user->id)->latest()->first();

        $oldDay = '';

        if($oldTimeIn) {
            $oldStartTime = new Carbon($oldTimeIn->start_time);
            $oldDay = $oldStartTime->startOfDay();
        }

        $today = Carbon::today();

        if(($oldDay == $today) && (empty($oldTimeIn->end_time))) {
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
        $oldTimeOut = Work_Time::where('user_id', $request->user_id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $oldTimeOut->id)->latest()->first();
        $latestWorkTimeStartTime = new Carbon($oldTimeOut->start_time);
        $oldDay = $latestWorkTimeStartTime->startOfDay();
        $addDay = $oldDay->addDay();
        $today = Carbon::today();
        
        if($oldTimeOut) {
            if(empty($oldTimeOut->end_time)) {
                if($latestBreakTime->break_in && !($latestBreakTime->break_out)) {
                    return redirect()->back()->with('message', '休憩終了が打刻されていません');
                }elseif($addDay == $today) {
                    $endOfDay = new Carbon($oldDay->endOfDay());
                    $date = [
                        'date' => $oldDay,
                        'end_time' => $endOfDay,
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    $oldTimeOut->update([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'end_time' => $date['end_time'],
                    ]);
                    $date = new Carbon();
                    $date = [
                        'date' => Carbon::today(),
                        'start_time' => Carbon::today(),
                        'end_time' => Carbon::now(),
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    Work_Time::create([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'start_time' => $date['start_time'],
                        'end_time' => $date['end_time'],
                    ]);
                    return redirect('/work/stamp');
                }else {
                    $date = new Carbon();
                    $date = [
                        'date' => Carbon::today(),
                        'start_time' => $oldTimeOut->start_time,
                        'end_time' => Carbon::now(),
                    ];
                    $this->validate($request, $date, Work_Time::$rules);
                    $oldTimeOut->update([
                        'user_id' => $request->user_id,
                        'date' => $date['date'],
                        'start_time' => $date['start_time'],
                        'end_time' => $date['end_time'],
                    ]);
                    return redirect('/work/stamp');
                }
            }else{
                return redirect()->back()->with('message','出勤打刻がされていません');
            }
        }
    }
}
