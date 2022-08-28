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
        $oldTimeIn = Work_Time::where('user_id', $request->user_id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $oldTimeIn->id)->latest()->first();
        if($oldTimeIn->start_time && !($oldTimeIn->end_time)) {
            if(empty($latestBreakTime->break_in)) {
                $data = [
                    'break_in' => Carbon::now(),
                ];
                Break_Time::create([
                    'user_id' => $oldTimeIn->user_id,
                    'work__time_id' => $oldTimeIn->id,
                    'break_in' => $data['break_in'],
                ]);
                return redirect()->back();
            }elseif($latestBreakTime->break_in && $latestBreakTime->break_out) {
                $data = [
                    'break_in' => Carbon::now(),
                ];
                Break_Time::create([
                    'user_id' => $oldTimeIn->user_id,
                    'work__time_id' => $oldTimeIn->id,
                    'break_in' => $data['break_in'],
                ]);
                return redirect()->back();
            }
        }
        return redirect()->back()->with('message', '休憩開始が実行できません');
    }
    
    public function update(Request $request)
    {
        $oldTimeIn = Work_Time::where('user_id', $request->user_id)->latest()->first();
        $latestBreakTime = Break_Time::where('work__time_id', $oldTimeIn->id)->latest()->first();
        if($latestBreakTime->break_in && empty($latestBreakTime->break_out)) {
            $latestBreakTime->update([
                'work__time_id' => $latestBreakTime->work__time_id,
                'break_out' => Carbon::now(),
            ]);
            return redirect()->back();
        }
        return redirect()->back()->with('message', '休憩終了が実行できません');
    }
}
