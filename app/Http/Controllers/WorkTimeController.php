<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_Time;
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
        // dd($date['date']);
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
        $oldTimeOut = Work_Time::with('book_time')->where('user_id', $request->id)->latest()->first();
        
        if($oldTimeOut) {
            if(empty($oldTimeOut->end_time)) {
                if($oldTimeOut->book_time->break_in && !$oldTimeOut->book_time->break_out) {
                    return redirect()->back()->with('message', '休憩終了が打刻されていません');
                } elseif()
            }
        }
    }
}
