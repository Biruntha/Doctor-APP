<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function order(){
        return $this->hasOne('App\Models\Order','id','order_id');
    }

    public function performUser(){
        return $this->hasOne('App\Models\User','id','perform_by');
    }
}
