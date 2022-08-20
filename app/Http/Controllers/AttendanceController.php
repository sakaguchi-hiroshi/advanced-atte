<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index($year, $month)
    {
        $date = new Carbon(time: "{$year}-{$month}-01");
        $addDay = ($date->copy()->endOfMonth()->isSunday()) ? 7 : 0;
        $date->subDay($date->dayOfWeek);
        $count = 31 + $addDay + $date->dayOfWeek;
        $count = ceil(value: $count / 7) * 7;
        $dates = [];

        for($i = 0; $i < $count; $i++, $date->addDay()) {
            $dates[] = $date->copy();
        }
        return $dates;
        return view('calendar', ['dates' => $dates]);
    }

    public function show()
    {

    }
}
