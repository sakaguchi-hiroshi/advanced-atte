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
        $oldTimeIn = Work_Time::with('break_times')->where('user_id', $request->user_id)->latest()->first();
        dd($oldTimeIn->break_times);
        if($oldTimeIn->start_time && !$oldTimeIn->end_time && !($oldTimeIn->break_times->break_in)) {
            $oldTimeIn->break_times->update([
                'work__time_id' => $oldTimeIn->id,
                'break_in' => Carbon::now(),
            ]);
            return redirect()->back();
        }
        return redirect()->back()->with('message', '休憩開始が実行できません');
    }

    public function update()
    {
        $oldTimeIn = Work_Time::with('break_times')->where('user_id', $request->user_id)->latest()->first();
        if($oldTimeIn->break_times->break_in && !($oldTimeIn->break_times->break_out)) {
            $oldTimeIn->break_times->update([
                'work__time_id' => $oldTimeIn->id,
                'break_out' => Carbon::now(),
            ]);
            return redirect()->back();
        }
        return redirect()->back()->with('message', '休憩開始が実行できません');
    }
}
