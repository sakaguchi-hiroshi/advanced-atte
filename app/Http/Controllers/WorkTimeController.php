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
        $date = new Carbon('today');
        $datetime = Carbon::now();
        $params = [
            'authuser' => $authuser,
            'date' => $date,
            'datetime' => $datetime,
        ];
        return view('stamp', $params);
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $this->validate($request, Work_Time::$rules);
        Work_Time::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
        ]);
        return redirect('/work/stamp');
    }
    
    // public function update(Request $request)
    // {
    //     $this->validate($request, WorkTime::$rules);
    //     $data = $request->all();
    //     WorkTime::where('user_id', $request->user_id)
    //     ->where('date', $request->date)->get();
    // }
}
