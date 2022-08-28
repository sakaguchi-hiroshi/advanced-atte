<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Break_Time extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $rules = array(
        'user_id' => 'required',
        'work__time_id' => 'required',
        'break_in' => 'required',
    );

    public function work_time(){
        return $this->belongsTo('App\Models\Work_Time', 'work__time_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
