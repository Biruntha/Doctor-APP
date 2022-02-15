<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    public function order(){
        return $this->hasOne('App\Models\Order','id','order_id');
    }

    public function activityUser(){
        return $this->hasOne('App\Models\User','id','user');
    }
}
