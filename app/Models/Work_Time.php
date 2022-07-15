<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_Time extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $rules = array(
        'user_id' => 'required',
        'date' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
    );

    public function break_times(){
        return $this->hasMany('App\Models\Break_Time');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
